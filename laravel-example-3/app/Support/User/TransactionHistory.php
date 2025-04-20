<?php

namespace App\Support\User;

use App\User;
use App\Support\User\TransactionHistoryEvents;
use App\Support\User\TransactionHistoryBalanceUpdate;

class TransactionHistory
{
    protected $user;
    protected $transactions;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function get()
    {
        if (isset($this->transactions)) {
            return $this->transactions;
        }

        $lastUpdate = optional($this->user->transactions()->latest()->first())->created_at;
        $invoices = (new TransactionHistoryInvoices($this->user, $overrideInvoices = [], $lastUpdate))->get();
        $balances = (new TransactionHistoryBalanceUpdate($this->user, $overrideEvents = [], $lastUpdate))->get();

        return $invoices->concat($balances)
        ->sortByDesc('created_at')->values();
    }
}
