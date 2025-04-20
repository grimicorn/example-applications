<?php

namespace App\Http\Controllers\Application;

use App\Notifications\InvoicePaid;
use App\Support\User\TransactionHistoryInvoices;
use App\Support\User\TransactionHistoryBalanceUpdate;
use Laravel\Spark\Http\Controllers\Settings\Billing\StripeWebhookController as SparkStripeWebhookController;

class StripeWebhookController extends SparkStripeWebhookController
{
    /**
     * Send an invoice notification e-mail.
     *
     * @param  mixed $billable
     * @param  \Laravel\Cashier\Invoice
     * @return void
     */
    protected function sendInvoiceNotification($billable, $invoice)
    {
        // Disables login invoice
    }

    protected function handleInvoicePaymentSucceeded(array $payload)
    {
        $user = $this->getUserByStripeId(
            $payload['data']['object']['customer']
        );

        $invoices = (new TransactionHistoryInvoices($user, [ $payload ]))->createBillingTransactions();

        return parent::handleInvoicePaymentSucceeded($payload);
    }

    protected function handleCustomerUpdated(array $payload)
    {
        $user = $this->getUserByStripeId(
            $payload['data']['object']['id']
        );

        (new TransactionHistoryBalanceUpdate($user, [ $payload ]))->createBillingTransactions();
    }
}
