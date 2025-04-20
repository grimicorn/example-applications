<?php

namespace App\Support;

use Laravel\Spark\Spark;
use App\Support\HasStates;
use App\Support\HasSelects;
use App\Support\HasUserPrimaryRoles;
use Illuminate\Support\Facades\Route;
use App\Support\ExchangeSpaceDealType;
use App\Support\HasBusinessCategories;
use App\Support\User\BillingTransactionType;

class HeadVariables
{
    use HasBusinessCategories,
        HasStates,
        HasSelects,
        HasUserPrimaryRoles;


    public function get($errors)
    {
        return array_merge(
            $this->spark($errors)->toArray(),
            $this->custom($errors)->toArray()
        );
    }

    protected function custom($errors)
    {
        // General data
        $custom = collect([
            'statesForSelect' => $this->getStatesForSelect(),
            'primaryRolesForSelect' => $this->convertForSelect($this->getUserPrimaryRoles(), $setValues = true),
            'businessCategories' => $this->getBusinessCategoriesForSelect(),
            'errors' => $errors->getBag('default')->toArray(),
            'old' => session()->getOldInput(),
            'spaceDealTypes' => ExchangeSpaceDealType::getValues(),
            'disableUnloadConfirmation' => config('app.disable_unload_confirmation'),
        ]);

        // Current user specific
        if (auth()->check()) {
            $custom->put('uses2FA', auth()->user()->uses_two_factor_auth);
            $custom->put('userInitials', auth()->user()->initials);
        }

        // Payment specific
        if ($this->isPaymentRoute()) {
            $custom->put('stripe', [
                'monthly_plan_id' => BillingTransactionType::getPlanId(BillingTransactionType::MONTHLY_SUBSCRIPTION),
                'monthly_plan_id_small' => BillingTransactionType::getPlanId(BillingTransactionType::MONTHLY_SUBSCRIPTION_SMALL),
                'per_listing_plan_id' => BillingTransactionType::getPlanId(BillingTransactionType::PER_LISTING),
            ]);
        }

        return $custom->filter();
    }

    protected function spark($errors)
    {
        // These can safely be removed.
        $except = [
            'braintreeMerchantId',
            'braintreeToken',
            'createsAdditionalTeams',
            'csrfToken',
            'currencySymbol',
            'roles',
            'usesApi',
            'usesBraintree',
        ];

        // Thes can safely be removed when it is not a payment screen.
        if (!$this->isPaymentRoute()) {
            $except = array_merge([
                'cardUpFront',
                'collectsBillingAddress',
                'collectsEuropeanVat',
                'stripeKey',
                'teamString',
                'pluralTeamString',
                'usesTeams',
                'usesStripe',
            ], $except);
        }

        $except = array_filter($except);
        return collect(Spark::scriptVariables())->except($except);
    }

    protected function isPaymentRoute()
    {
        return in_array(Route::getFacadeRoot()->current()->getName(), [
            'listing.create',
            'listing.details.edit',
            'profile.payments.edit',
        ]);

        return true;
    }
}
