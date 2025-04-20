<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class UserCanCloseAccountTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_returns_that_a_user_can_not_close_their_account_if_listings_still_exist()
    {
        $user = $this->signInWithEvents();
        $listings = factory('App\Listing')->create(['user_id' => $user->id]);

        $this->assertFalse($user->canCloseAccount());
    }

    /**
     * @test
     */
    public function it_returns_that_a_user_can_not_close_their_account_if_they_have_not_canceled_their_subscription()
    {
        $this->setUserAsMonthlySubscriber($user = $this->signInWithEvents());

        $this->assertFalse($user->fresh()->canCloseAccount());
    }

    /**
    * @test
    */
    public function it_returns_that_a_user_can_close_their_account_if_they_have_canceled_their_subscription_and_deleted_all_of_their_listings()
    {
        // Setup the user
        $user = $this->signInWithEvents();
        factory('App\Listing')->create(['user_id' => $user->id]);
        $this->setUserAsMonthlySubscriber($user);
        $user = $user->fresh();

        // Make sure the user can not cancel at this point.
        $this->assertFalse($user->fresh()->canCloseAccount());

        // Simulate completing pre-close tasks
        $user->listings->each->delete();
        $user->currentSubscription()->cancel();

        $this->assertTrue($user->fresh()->canCloseAccount());
    }
}
