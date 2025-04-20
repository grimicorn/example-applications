<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Laravel\Spark\Spark;
use App\BillingTransaction;
use Laravel\Spark\Subscription;
use Illuminate\Support\Facades\Artisan;
use Laravel\Spark\Interactions\Subscribe;
use App\Support\User\CanceledSubscriptions;
use App\Support\User\BillingTransactionType;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class ListingPublishControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_publishes_a_listing_for_a_monthly_subscriber()
    {
        // Check if listing owner
        // Check if unpublishable
        // Check for current subscription
        // If current subscription set published true
        $listing = factory('App\Listing')->create([
            'published' => false,
            'should_display_encouragement_modal' => true,
        ]);
        $this->assertTrue($listing->should_display_encouragement_modal);

        // Give the listing owner a stripe token.
        $stripeToken = $this->stripeTestToken()->id;
        $listing->user->createAsStripeCustomer($stripeToken);

        // Give the listing owner a subscription
        $planId = BillingTransactionType::getPlanId(BillingTransactionType::MONTHLY_SUBSCRIPTION);
        $plan = $listing->user->sparkPlan($planId);
        $listing->user->fresh()->newSubscription('default', $planId)->create($stripeToken);
        $this->assertEquals('subscribed', $listing->user->fresh()->account_status);

        // Sign the listing user in
        $this->signInWithEvents($listing->user->fresh());

        // Attempt to publish the listing.
        $response = $this->json('POST', route('listing.publish', ['id' => $listing->id]));

        // Make sure everything went ok.
        $response->assertStatus(200);
        $listing = $listing->fresh();
        $this->assertNull($listing->invoice_provider_id);
        $this->assertTrue($listing->published);
        $this->assertFalse($listing->should_display_encouragement_modal);

        // Verify the latest transaction has the correct details. Should not match the per-lisiting transaction.
        $transaction = BillingTransaction::latest()->first();
        $this->assertNotEquals(99, optional($transaction)->amount);
        $this->assertNotEquals(BillingTransactionType::PER_LISTING, optional($transaction)->type);
    }

    /**
    * @test
    */
    public function it_charges_per_listing_fee_and_then_publishes_a_listing_for_non_monthly_subscriber()
    {
        $listing = factory('App\Listing')->create([
            'published' => false,
            'name_visible' => false,
            'should_display_encouragement_modal' => true,
        ]);
        $this->assertTrue($listing->should_display_encouragement_modal);

        // Give the listing owner a stripe token.
        $listing->user->createAsStripeCustomer($this->stripeTestToken()->id);

        // Sign the listing user in
        $this->signInWithEvents($listing->user);

        // Attempt to publish the listing.
        $response = $this->json('POST', route('listing.publish', ['id' => $listing->id]));

        // Make sure everything went ok.
        $response->assertStatus(200);
        $listing = $listing->fresh();
        $this->assertNotNull($listing->invoice_provider_id);
        $this->assertTrue($listing->published);
        $this->assertFalse($listing->should_display_encouragement_modal);

        // Verify the latest transaction has the correct details.
        // $transaction = BillingTransaction::latest()->first();
        // $this->assertEquals($listing->user->id, $transaction->id);
        // $this->assertEquals(249, $transaction->amount);
        // $this->assertEquals($listing->business_name, $transaction->description);
        // $this->assertEquals(BillingTransactionType::PER_LISTING, $transaction->type);
    }

    /**
    * @test
    */
    public function it_unpublishes_a_listing()
    {
        // Attempt to delete a listing.
        $listing = factory('App\Listing')->create([
            'published' => true,
        ]);
        $this->signInWithEvents($listing->user);
        $response = $this->json('DELETE', route('listing.unpublish', ['id' => $listing->id]));

        // Make sure everything ent ok
        $response->assertStatus(200);
        $this->assertFalse($listing->fresh()->published);
    }

    /**
    * @test
    * @group failing
    */
    public function it_only_allows_the_listing_owner_to_publish_a_listing()
    {
        // Sign a user other than the listing user in.
        $user = $this->signInWithEvents();

        // Create a listing
        $listing = factory('App\Listing')->create([
            'published' => false,
        ]);

        // Give the listing owner a stripe token.
        $user->createAsStripeCustomer($this->stripeTestToken()->id);

        // Attempt to publish the listing.
        $response = $this->json('POST', route('listing.publish', ['id' => $listing->id]));

        $response->assertStatus(403);
        $this->assertFalse($listing->fresh()->published);
    }

    /**
    * @test
    * @group failing
    */
    public function it_only_allows_the_listing_owner_to_unpublish_a_listing()
    {
        // Sign a user other than the listing user in.
        $user = $this->signInWithEvents();

        // Attempt to delete a listing.
        $listing = factory('App\Listing')->create([
            'published' => true,
        ]);

        $response = $this->json('DELETE', route('listing.unpublish', ['id' => $listing->id]));

        // Make sure everything ent ok
        $response->assertStatus(403);
        $this->assertTrue($listing->fresh()->published);
    }

    /**
    * @test
    * @group failing
    */
    public function it_unpublishes_monthly_listings_for_canceled_subscriptions_after_grace_period()
    {
        $user = $this->signInWithEvents();

        // Give the user a stripe token.
        $stripeToken = $this->stripeTestToken()->id;
        $user->createAsStripeCustomer($stripeToken);

        // Create a listing via one time payment
        $indivdiual_listing = factory('App\Listing')->create([
            'published' => false,
            'user_id' => $user->id,
        ]);

        // Publish the listing.
        $this->json('POST', route('listing.publish', [
            'id' => $indivdiual_listing->id,
        ]));
        $this->assertTrue($indivdiual_listing->fresh()->published);

        // Create listing and subscribe to the monthly plan
        $monthly_listings = factory('App\Listing', 10)->create([
            'published' => true,
            'user_id' => $user->id,
        ]);

        // Make sure the users listings are published.
        $user->fresh()->listings->each(function ($listing) {
            $this->assertTrue($listing->published);
        });

        // Give the listing owner a subscription
        $planId = BillingTransactionType::getPlanId(BillingTransactionType::MONTHLY_SUBSCRIPTION);
        $plan = $user->sparkPlan($planId);
        $user->newSubscription('default', $planId)->create($stripeToken);
        $user = $user->fresh();
        $this->assertEquals('subscribed', $user->account_status);

        // Cancel the subscription
        $user->currentSubscription()->cancelNow();
        $user = $user->fresh();

        // Set the subscription to cancel one day in the past.
        $subscription = Subscription::where('user_id', $user->id)->first();
        $subscription->ends_at = $subscription->ends_at->subDay();
        $subscription->save();

        // Make sure the user is not subscribded
        $this->assertNotEquals('subscribed', $user->fresh()->account_status);

        // Run the canceled subscriptions Artisan command
        Artisan::call('fe:canceled-subscriptions');

        // Make sure the individual listing is not published.
        $this->assertTrue($indivdiual_listing->fresh()->published);

        // Make sure the monthly listings are unpublished
        $monthly_listings->fresh()->each(function ($listing) {
            $this->assertFalse($listing->published);
        });
    }

    /**
    * @test
    * @group failing
    */
    public function it_does_not_unpublish_listings_for_canceled_subscriptions_during_grace_period()
    {
        $user = $this->signInWithEvents();

        $listing = factory('App\Listing', 10)->create([
            'published' => true,
            'user_id' => $user->id,
        ]);

        // Make sure the users listings are published.
        $user->fresh()->listings->each(function ($listing) {
            $this->assertTrue($listing->published);
        });

        // Give the listing owner a stripe token.
        $stripeToken = $this->stripeTestToken()->id;
        $user->createAsStripeCustomer($stripeToken);

        // Give the listing owner a subscription
        $planId = BillingTransactionType::getPlanId(BillingTransactionType::MONTHLY_SUBSCRIPTION);
        $plan = $user->sparkPlan($planId);
        $user->newSubscription('default', $planId)->create($stripeToken);
        $user = $user->fresh();
        $this->assertEquals('subscribed', $user->account_status);

        // Cancel the subscription
        // (They will be in their grace period at this point)
        $user->currentSubscription()->cancelNow();
        $user = $user->fresh();

        // Set the subscription to cancel today in an hour to
        // simulate a user who still has a day left.
        $subscription = Subscription::where('user_id', $user->id)->first();
        $subscription->ends_at = Carbon::now()->addHour();
        $subscription->save();

        // Run the canceled subscriptions Artisan command
        Artisan::call('fe:canceled-subscriptions');

        // Make sure the listings are NOT unpublished
        $user->fresh()->listings->each(function ($listing) {
            $this->assertTrue($listing->published);
        });
    }
}
