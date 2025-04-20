<?php

namespace App\Support\User;

use App\User;
use Stripe\Event;
use Stripe\Stripe;
use App\BillingTransaction;
use Illuminate\Support\Carbon;
use App\Support\HasStripePrices;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\Support\User\BillingTransactionType;

class TransactionHistoryBalanceUpdate
{
    use HasStripePrices;

    protected $user;
    protected $userUpdateEvents;
    protected $transactions;
    protected $lastUpdate;

    public function __construct(User $user, $events = [], $lastUpdate = null)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $this->user = $user;
        $this->lastUpdate = $lastUpdate;

        // allow overriding of the events.
        if ($events) {
            $this->userUpdateEvents = is_array($events) ? r_collect($events) : $events;
        }
    }

    public function get()
    {
        return $this->transactions = $this->transactions ?? $this->createBillingTransactions();
    }

    protected function userUpdateEvents(): Collection
    {
        if (isset($this->userUpdateEvents)) {
            return $this->userUpdateEvents;
        }

        // $key = "stripe.transaction-userUpdateEvents.{$this->user->id}";
        // Cache::forget($key);

        try {
            // return $this->userUpdateEvents = Cache::rememberForever($key, function () {
            return r_collect(r_collect(Event::all([
                    'created' => ['gte' => optional($this->lastUpdate)->timestamp],
                    'limit' => 100,
                    'types' => [
                        'customer.updated',
                    ],
                    'related_object' => auth()->user()->stripe_id,
                ])->data)->map->__toArray());
            // });
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    public function createBillingTransactions()
    {
        return $this->userUpdateEvents()->filter(function ($event) {
            return $this->isBalanceUpdate($event) && !$this->isPendingCharge($event);
        })->map(function ($event) {
            return $this->createBillingTransaction($event);
        })->sortByDesc('created_at');
    }

    protected function createBillingTransaction($event)
    {
        // Subtract 10 seconds to simulate it happening after an invoice for debits.
        $date = $event->get('created') ? Carbon::createFromTimestampUTC($event->get('created')) : now();
        $date = $date->addSeconds(10);

        $transaction = BillingTransaction::firstOrNew([
            'user_id' => $this->user->id,
            'provider_id' => $event->get('id'),
            'type' => $this->type($event),
        ], [
            'invoice_id' => null,
            'label' => 'Change in Account Credits',
            'description' => null,
            'amount' => $this->amount($event),
            'created_at' => $date->format('Y-m-d H:i:s'),
            'updated_at' => $date->format('Y-m-d H:i:s'),
            'start_date' => null,
            'end_date' => null,
        ]);

        // Forcibly set the updated at time
        $transaction->created_at = $date->format('Y-m-d H:i:s');
        $transaction->updated_at = $date->format('Y-m-d H:i:s');
        $transaction->save();

        return $transaction;
    }

    protected function type($event)
    {
        return $this->isBalanceDebit($event) ?
        BillingTransactionType::ACCOUNT_DEBIT : BillingTransactionType::ACCOUNT_CREDIT;
    }

    protected function amount($event)
    {
        $eventData = optional($event->get('data', collect([])));
        $startingBalance = floatval(optional($eventData['previous_attributes'])['account_balance']);
        $endingBalance = floatval(optional($eventData['object'])['account_balance']);

        return $this->convertStripePrice($startingBalance - $endingBalance);
    }

    protected function isBalanceCredit($event)
    {
        // We only want to check updates to account balances
        if (!$this->isBalanceUpdate($event)) {
            return false;
        }

        return !$this->isBalanceDebit($event);
    }

    protected function isBalanceDebit($event)
    {
        $eventData = optional($event->get('data', collect([])));
        $startingBalance = optional($eventData['previous_attributes'])['account_balance'];
        $endingBalance = optional($eventData['object'])['account_balance'];

        // We only want to check updates to account balances
        if (!$this->isBalanceUpdate($event)) {
            return false;
        }

        // Stripe tracks credits as negative numbers so debits will be larger and credits will be smaller.
        return floatval($endingBalance) > floatval($startingBalance);
    }

    protected function isPendingCharge($event)
    {
        $eventData = optional($event->get('data', collect([])));
        $startingBalance = optional($eventData['previous_attributes'])['account_balance'];
        $endingBalance = optional($eventData['object'])['account_balance'];

        // If you have a current charge is added the ending balance will be positive
        if (floatval($endingBalance) > 0) {
            return true;
        }


        // If you have a current charge is added the previous balance will be positive
        // and the ending balance will be less than the starting balance.
        if (floatval($startingBalance) > 0 && floatval($startingBalance) > floatval($endingBalance)) {
            return true;
        }

        return false;
    }

    protected function isBalanceUpdate($event)
    {
        $eventData = optional($event->get('data', collect([])));

        // We only want to check updates to account balances
        return !is_null(optional($eventData['previous_attributes'])['account_balance']);
    }
}
