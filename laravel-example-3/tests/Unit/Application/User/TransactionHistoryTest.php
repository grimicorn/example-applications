<?php

namespace Tests\Unit\Application\User;

use Tests\TestCase;
use App\Support\User\TransactionHistory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignoreFile
class TransactionHistoryTest extends TestCase
{
    /**
    * @test
    */
    public function it_retrieves_the_transaction_history_for_a_user()
    {
        $user = factory('App\User')->create();
        $user->createAsStripeCustomer($stripeToken = $this->stripeTestToken()->id);

        $history = (new TransactionHistory($user))->get();
        // $this->assertEquals(r_collect([
        //     [
        //         'created_at' => null,
        //         'type' => 'monthly_payment', // [monthly_payment,balance_credit, balance_debit,individual_listing_payment]
        //         'description' => '',
        //         'amount' => 0,
        //     ]
        // ]), $history);

        $this->markTestIncomplete('Not implemented');
    }
}
