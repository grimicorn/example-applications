<?php

namespace App\Support\User;

use App\User;
use App\Listing;
use Stripe\Stripe;
use Stripe\Invoice;
use App\BillingTransaction;
use Illuminate\Support\Carbon;
use Laravel\Spark\LocalInvoice;
use App\Support\HasStripePrices;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\Support\User\BillingTransactionType;

class TransactionHistoryInvoices
{
    use HasStripePrices;

    protected $user;
    protected $transactions;
    protected $invoices;
    protected $lastUpdate;

    public function __construct(User $user, $invoices = [], $lastUpdate = null)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $this->user = $user;
        $this->lastUpdate = $lastUpdate;
    }

    public function get()
    {
        return $this->transactions = $this->transactions ?? $this->createBillingTransactions();
    }

    protected function invoices(): Collection
    {
        if (isset($this->invoices)) {
            return $this->invoices;
        }

        // $key = "stripe.transaction-invoices.{$this->user->id}";
        // Cache::forget($key);

        try {
            // return $this->invoices = Cache::rememberForever($key, function () {
            return r_collect(r_collect(Invoice::all([
                'created' => ['gte' => optional($this->lastUpdate)->timestamp],
                'customer' => $this->user->stripe_id,
            ])->data)->map->__toArray());
            // });
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function createBillingTransactions()
    {
        return $this->invoices()->map(function ($invoice) {
            return $this->createBillingTransaction($invoice);
        })->sortByDesc('created_at');
    }

    protected function createBillingTransaction($invoice)
    {
        $date = $invoice->get('date') ? Carbon::createFromTimestampUTC($invoice->get('date')) : now();

        $transaction = BillingTransaction::firstOrNew([
            'user_id' => $this->user->id,
            'provider_id' => $invoice->get('id'),
        ], [
            'invoice_id' => $this->localInvoiceId($invoice),
            'type' => $this->type($invoice),
            'label' => $this->label($invoice),
            'description' => $this->description($invoice),
            'amount' => $this->amount($invoice),
            'start_date' => $date->format('Y-m-d H:i:s'),
            'end_date' => $this->user->subscription_renewal_date,
        ]);

        // Forcibly set the updated at time
        $transaction->created_at = $date->format('Y-m-d H:i:s');
        $transaction->updated_at = $date->format('Y-m-d H:i:s');
        $transaction->save();

        return $transaction;
    }

    protected function localInvoiceId($invoice)
    {
        if ($invoice->get('id')) {
            return optional(LocalInvoice::where(['provider_id' => $invoice->get('id')])->first())->id;
        }

        return null;
    }

    protected function label($invoice)
    {
        return BillingTransactionType::getLabel($this->type($invoice));
    }

    protected function description($invoice)
    {
        $type = $this->type($invoice);

        // Only per-listing transactions store a description.
        if ($type === BillingTransactionType::PER_LISTING) {
            return optional(
                Listing::where(['invoice_provider_id' => $invoice->get('id')])->first()
            )->title;
        }

        return null;
    }

    protected function amount($invoice)
    {
        return $this->convertStripePrice($invoice->get('subtotal'));
    }

    protected function type($invoice)
    {
        switch ($this->planId($invoice)) {
            case config('services.stripe.monthly_plan_id'):
                return BillingTransactionType::MONTHLY_SUBSCRIPTION;
                break;

            case config('services.stripe.monthly_plan_id_small'):
                return BillingTransactionType::MONTHLY_SUBSCRIPTION_SMALL;
                break;

            case config('services.stripe.per_listing_plan_id'):
                return BillingTransactionType::PER_LISTING;
                break;
        }
    }

    protected function planId($invoice)
    {
        return collect($invoice->get('lines')['data'])
        ->map(function ($line) {
            return optional($line->plan)->id;
        })->filter()->first() ?? config('services.stripe.per_listing_plan_id');
    }

    protected function isBalanceDebit($invoice)
    {
        // Stripe tracks credits as negative numbers so debits will be larger and credits will be smaller.
        return floatval($invoice->get('ending_balance'))> floatval($invoice->get('starting_balance'));
    }
}
