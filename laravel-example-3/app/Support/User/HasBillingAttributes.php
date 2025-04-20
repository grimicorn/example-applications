<?php

namespace App\Support\User;

use Stripe\Stripe;
use Stripe\Customer;
use Illuminate\Support\Carbon;
use App\Support\HasStripeHelpers;
use Illuminate\Support\Facades\Cache;
use App\Support\User\BillingTransactionType;

trait HasBillingAttributes
{
    use HasStripeHelpers;

    public function getAccountStatusLabelAttribute()
    {
        switch ($this->account_status) {
            case 'subscribed':
                return 'Subscribed';
                break;

            case 'per-listing':
                return 'Per-listing';
                break;

            default:
                return 'Not Subscribed';
                break;
        }

        return 'Not Subscribed';
    }

    public function currentSubscription()
    {
        $subscription = optional($this->subscription('default'));

        if (!$subscription->active()) {
            return optional(null);
        }

        return $subscription;
    }

    protected function getCurrentPlan()
    {
        return $this->sparkPlan('default');
    }

    public function getAccountStatusAttribute()
    {
        if (!$this->hasStripeId()) {
            return 'not-subscribed';
        }

        $id = $this->currentSubscription()->id;
        if (!$id) {
            return 'not-subscribed';
        }

        if ($id !== 'free' or $this->onGracePeriod()) {
            return 'subscribed';
        }

        if (intval($this->per_listing_count) > 0) {
            return 'per-listing';
        }

        return 'not-subscribed';
    }

    public function onGracePeriod()
    {
        return !!$this->currentSubscription()->onGracePeriod();
    }

    public function getAccountBalance()
    {
        if (!$this->hasStripeId()) {
            return 0;
        }

        // Get the balance from stripe.
        Stripe::setApiKey(config('services.stripe.secret'));
        $customer = optional(Customer::retrieve($this->stripe_id));

        return $this->convertStripePrice(intval($customer->account_balance));
    }

    public function getSubscriptionRenewalDateAttribute()
    {
        if (!$this->hasStripeId() || !$this->currentSubscription()->asStripeSubscription()) {
            return;
        }

        $end = $this->currentSubscription()->asStripeSubscription()->current_period_end;

        return Carbon::createFromTimestamp($end);
    }

    public function getSubscriptionPaymentAmountAttribute()
    {
        if (!$this->hasStripeId()) {
            return 0;
        }

        $price = optional($this->getCurrentPlan())->price;
        if (is_null($price)) {
            $subscription = $this->currentSubscription();
            $pricing = optional($this->getSubscriptionPricing($subscription->stripe_plan));
            $price = $pricing->get('amount');
        }

        return intval($price);
    }

    public function getPerListingCountAttribute()
    {
        return $this->transactions()->where('type', BillingTransactionType::PER_LISTING)->get()->count();
    }

    public function transactions()
    {
        return $this->hasMany('App\BillingTransaction')->orderByDesc('created_at', 'desc');
    }

    public function isSubscribed()
    {
        return ($this->account_status === 'subscribed');
    }
}
