<?php

namespace Tests\Unit\Application;

use App\User;
use App\Listing;
use Tests\TestCase;
use App\SavedSearch;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Artisan;
use App\Support\Watchlist\WatchlistMatches;
use App\Jobs\RefreshWatchlistMatchesForUser;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Support\HasNotificationTestHelpers;
use App\Support\Notification\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\SavedSearchNotification;

// phpcs:ignorefile
class WatchlistMatchesTest extends TestCase
{
    use RefreshDatabase,
        HasNotificationTestHelpers;

    /**
    * @test
    */
    public function it_retrieves_matches_for_an_individual_watchlist()
    {
        $user = $this->signInWithEvents();
        $watchlist = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);
        $listing = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
        ]);
        factory(Listing::class, 5)->states('published')->create([
            'asking_price' => 800000,
        ]);

        $matches = (new WatchlistMatches)->forSearch($watchlist);
        $this->assertEquals(
            collect([$listing])->pluck('id')->sort(),
            $matches->pluck('id')->sort()
        );
    }

    /**
     * @test
     */
    public function it_refreshes_matches_for_an_individual_watchlist()
    {
        $user = $this->signInWithEvents();
        $watchlist = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);
        $listing = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
        ]);
        factory(Listing::class, 5)->states('published')->create([
            'asking_price' => 800000,
        ]);
        $this->assertNull($watchlist->refreshed_at);

        $matches = (new WatchlistMatches)->refreshForSearch($watchlist);

        $this->assertEquals(
            collect([$listing])->pluck('id')->sort(),
            $watchlist->fresh()->listings->pluck('id')->sort()
        );
        $this->assertNotNull($watchlist->refreshed_at);
    }

    /**
    * @test
    */
    public function it_does_not_update_matches_when_searching_via_business_search()
    {
        $user = $this->signInWithEvents();
        $watchlist = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);
        $listing = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
        ]);
        factory(Listing::class, 5)->states('published')->create([
            'asking_price' => 800000,
        ]);

        $this->assertEmpty(
            $watchlist->fresh()->listings->pluck('id')->sort()
        );

        // $matches = (new WatchlistMatches)->refreshForSearch($watchlist);
        $this->post(route('businesses.index'), [
            'asking_price_min' => $watchlist->asking_price_min,
            'asking_price_max' => $watchlist->asking_price_max,
            'saved_search_id' => $watchlist->id,
        ]);

        $this->assertEmpty(
            $watchlist->fresh()->listings->pluck('id')->sort()
        );
    }

    /**
     * @test
     */
    public function it_does_not_update_searches_when_searching_via_business_search()
    {
        $user = $this->signInWithEvents();
        $watchlist = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => $start_asking_price_min = 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);
        $listing = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
        ]);
        factory(Listing::class, 5)->states('published')->create([
            'asking_price' => 800000,
        ]);

        $this->assertEmpty(
            $watchlist->fresh()->listings->pluck('id')->sort()
        );

        // $matches = (new WatchlistMatches)->refreshForSearch($watchlist);
        $this->post(route('businesses.index'), [
            'asking_price_min' => 200000,
            'asking_price_max' => $watchlist->asking_price_max,
            'saved_search_id' => $watchlist->id,
        ]);

        $this->assertEquals($watchlist->fresh()->asking_price_min, $start_asking_price_min);
    }

    /**
    * @test
    */
    public function it_ignores_matches_for_the_watchlist_owners_listings()
    {
        $user = $this->signInWithEvents();
        $watchlist = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);
        $ownedListing = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
            'user_id' => $user->id,
        ]);
        $notOwnedlisting = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
        ]);

        $matches = (new WatchlistMatches)->refreshForSearch($watchlist);

        $this->assertEquals(
            collect([$notOwnedlisting])->pluck('id')->sort(),
            $watchlist->fresh()->listings->pluck('id')->sort()
        );
    }

    /**
    * @test
    */
    public function it_ignores_unpublished_listings()
    {
        $user = $this->signInWithEvents();
        $watchlist = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);
        $unpublishedListing = factory(Listing::class)->states('unpublished')->create([
            'asking_price' => 500000,
        ]);
        $publishedListing = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
        ]);

        $matches = (new WatchlistMatches)->refreshForSearch($watchlist);

        $this->assertEquals(
            collect([$publishedListing])->pluck('id')->sort(),
            $watchlist->fresh()->listings->pluck('id')->sort()
        );
    }

    /**
    * @test
    */
    public function it_matches_all_listings_no_pagination()
    {
        $user = $this->signInWithEvents();
        $watchlist = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);
        $listings = factory(Listing::class, 50)->states('published')->create([
            'asking_price' => 500000,
        ]);

        $matches = (new WatchlistMatches)->forSearch($watchlist);
        $this->assertEquals(
            $listings->pluck('id')->sort(),
            $matches->pluck('id')->sort()
        );
    }

    /**
     * @test
     */
    public function it_refreshes_matches_for_all_of_a_users_watchlists()
    {
        // Setup
        $user = $this->signInWithEvents();
        $watchlist1 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);
        $watchlist2 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);

        $watchlist3 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);
        factory(SavedSearch::class, 10)->create();
        $listing = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
        ]);
        factory(Listing::class, 5)->states('published')->create([
            'asking_price' => 800000,
        ]);

        // Execute
        (new WatchlistMatches)->refreshForUser($user);

        // Assert
        $this->assertEquals(
            collect([$listing])->pluck('id')->sort(),
            $watchlist1->fresh()->listings->pluck('id')->sort()
        );
        $this->assertEquals(
            collect([$listing])->pluck('id')->sort(),
            $watchlist2->fresh()->listings->pluck('id')->sort()
        );
        $this->assertEquals(
            collect([$listing])->pluck('id')->sort(),
            $watchlist3->fresh()->listings->pluck('id')->sort()
        );
    }

    /**
     * @test
     */
    public function it_sends_a_notification_for_all_of_a_users_watchlists_if_new_matches_exist()
    {
        // Setup
        $user = $this->signInWithEvents();
        $watchlistMatches = new WatchlistMatches;
        $listing1 = factory(Listing::class)->states('published')->create([
            'title' => 'Listing 1',
            'asking_price' => 500000,
        ]);
        $listing2 = factory(Listing::class)->states('published')->create([
            'title' => 'Listing 2',
            'asking_price' => 500000,
        ]);
        $watchlist1 = factory(SavedSearch::class)->states('empty')->create([
            'name' => 'Watchlist 1',
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);
        $watchlistMatches->refreshForSearch($watchlist1); // Sets the listing as already matched
        sleep(1); // Sleep for 1 second to simulate difference in time.
        $watchlist2 = factory(SavedSearch::class)->states('empty')->create([
            'name' => 'Watchlist 2',
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);
        $watchlist3 = factory(SavedSearch::class)->states('empty')->create([
            'name' => 'Watchlist 3',
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist2->user->id,
            'saved_search_id' => $watchlist2->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist3->user->id,
            'saved_search_id' => $watchlist3->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist2->user->id,
            'saved_search_id' => $watchlist2->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist3->user->id,
            'saved_search_id' => $watchlist3->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);

        Mail::fake();

        // Execute
        $watchlistMatches->refreshForUser($user);

        /**
         * Make sure Saved Search Match emails are not sent.
         */
        $this->assertNotificationCount(0, NotificationType::SAVED_SEARCH_MATCH);

        /**
         * Make sure Saved Search Match notifications are saved successfully.
         */
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseHas('saved_search_notifications', [
            'user_id' => $watchlist2->user->id,
            'saved_search_id' => $watchlist2->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseHas('saved_search_notifications', [
            'user_id' => $watchlist3->user->id,
            'saved_search_id' => $watchlist3->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseHas('saved_search_notifications', [
            'user_id' => $watchlist2->user->id,
            'saved_search_id' => $watchlist2->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseHas('saved_search_notifications', [
            'user_id' => $watchlist3->user->id,
            'saved_search_id' => $watchlist3->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);

        /**
         * Make sure the Saved Search Match Digest email was sent correctly.
         * No matter how many matches you get you should still only receive one email.
         */
        $this->assertNotificationCount(1, NotificationType::SAVED_SEARCH_MATCH_DIGEST);

        /**
         * Make sure Saved Search Match Digests are not saved.
         */
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist2->user->id,
            'saved_search_id' => $watchlist2->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist3->user->id,
            'saved_search_id' => $watchlist3->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist2->user->id,
            'saved_search_id' => $watchlist2->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist3->user->id,
            'saved_search_id' => $watchlist3->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
    }

    /**
    * @test
    */
    public function it_does_not_notify_users_with_notifications_disabled()
    {
        // Setup
        $user = $this->signInWithEvents();
        $user->emailNotificationSettings->enable_all = false;
        $user->emailNotificationSettings->save();
        $watchlistMatches = new WatchlistMatches;
        $listing1 = factory(Listing::class)->states('published')->create([
            'title' => 'Listing 1',
            'asking_price' => 500000,
        ]);
        $listing2 = factory(Listing::class)->states('published')->create([
            'title' => 'Listing 2',
            'asking_price' => 500000,
        ]);
        $watchlist1 = factory(SavedSearch::class)->states('empty')->create([
            'name' => 'Watchlist 1',
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);

        Mail::fake();

        // Execute
        $watchlistMatches->refreshForUser($user);

        /**
         * Make sure Saved Search Match emails are not sent.
         */
        $this->assertNotificationCount(0, NotificationType::SAVED_SEARCH_MATCH);

        /**
         * Make sure Saved Search Match notifications are saved successfully.
         */
        $this->assertDatabaseHas('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseHas('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);

        /**
         * Make sure the Saved Search Match Digest email was sent correctly.
         * No matter how many matches you get you should still only receive one email.
         */
        $this->assertNotificationCount(0, NotificationType::SAVED_SEARCH_MATCH_DIGEST);

        /**
         * Make sure Saved Search Match Digests are not saved.
         */
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
    }

    /**
     * @test
     */
    public function it_does_not_send_a_notification_for_all_of_a_users_watchlists_if_no_new_matches_exist()
    {
        // Setup
        $user = $this->signInWithEvents();
        $watchlistMatches = new WatchlistMatches;
        $listing1 = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
        ]);
        $listing2 = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
        ]);
        $watchlist1 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);
        $watchlistMatches->refreshForSearch($watchlist1); // Sets the listing as already matched
        $watchlist2 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);
        $watchlistMatches->refreshForSearch($watchlist2); // Sets the listing as already matched
        $watchlist3 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user->id,
        ]);
        $watchlistMatches->refreshForSearch($watchlist3); // Sets the listing as already matched

        /**
         * Verify that no Saved Search Match notifications currently exist
         */
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist2->user->id,
            'saved_search_id' => $watchlist2->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist3->user->id,
            'saved_search_id' => $watchlist3->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist2->user->id,
            'saved_search_id' => $watchlist2->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist3->user->id,
            'saved_search_id' => $watchlist3->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);

        /**
         * Verify that no Saved Search Match Digest notifications currently exist
         */
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist2->user->id,
            'saved_search_id' => $watchlist2->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist3->user->id,
            'saved_search_id' => $watchlist3->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist2->user->id,
            'saved_search_id' => $watchlist2->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist3->user->id,
            'saved_search_id' => $watchlist3->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);

        Mail::fake();

        // Sleep for 1 second to simulate difference in time.
        sleep(1);

        // Execute
        $watchlistMatches->refreshForUser($user);

        /**
         * Make sure no emails are sent.
         */
        $this->assertNotificationCount(0, NotificationType::SAVED_SEARCH_MATCH);
        $this->assertNotificationCount(0, NotificationType::SAVED_SEARCH_MATCH_DIGEST);

        /**
         * Make sure none of the Saved Search Match notifications are saved
         */
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist2->user->id,
            'saved_search_id' => $watchlist2->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist3->user->id,
            'saved_search_id' => $watchlist3->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist2->user->id,
            'saved_search_id' => $watchlist2->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist3->user->id,
            'saved_search_id' => $watchlist3->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);

        /**
         * Make sure none of the Saved Search Match Digest notifications are saved
         */
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist2->user->id,
            'saved_search_id' => $watchlist2->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist3->user->id,
            'saved_search_id' => $watchlist3->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist1->user->id,
            'saved_search_id' => $watchlist1->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist2->user->id,
            'saved_search_id' => $watchlist2->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $watchlist3->user->id,
            'saved_search_id' => $watchlist3->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH_DIGEST,
        ]);
    }

    /**
     * @test
     */
    public function it_refreshes_matches_for_all_active_users_watchlists()
    {
        // Setup
        $watchlist1 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);
        $watchlist2 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);
        $watchlist3 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);
        factory(SavedSearch::class, 5)->create();
        $listing = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
        ]);
        factory(Listing::class, 5)->states('published')->create([
            'asking_price' => 800000,
        ]);

        // Execute
        (new WatchlistMatches)->refreshForAllUsers();

        // Assert
        $this->assertEquals(
            collect([$listing])->pluck('id')->sort(),
            $watchlist1->fresh()->listings->pluck('id')->sort()
        );
        $this->assertEquals(
            collect([$listing])->pluck('id')->sort(),
            $watchlist2->fresh()->listings->pluck('id')->sort()
        );
        $this->assertEquals(
            collect([$listing])->pluck('id')->sort(),
            $watchlist3->fresh()->listings->pluck('id')->sort()
        );
    }

    /**
    * @test
    */
    public function it_queues_each_user_when_refreshing_all_users()
    {
        // Setup
        $watchlist1 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);
        $watchlist2 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);

        $watchlist3 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);

        Queue::fake();

        // Execute
        (new WatchlistMatches)->refreshForAllUsers();

        // Assert
        Queue::assertPushed(RefreshWatchlistMatchesForUser::class, 3);
    }

    /**
     * @test
     */
    public function it_only_sends_a_notification_for_all_active_users_watchlists_if_new_matches_exist()
    {
        // Setup
        $watchlistMatches = new WatchlistMatches;
        $listing1 = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
        ]);
        $listing2 = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
        ]);
        $user1MissingNotification = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);
        $user1HasNotification = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);
        $watchlistMatches->refreshForSearch($user1MissingNotification); // Sets the listing as already matched
        $user2MissingNotification = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);
        $user2HasNotification = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);
        $watchlistMatches->refreshForSearch($user2MissingNotification); // Sets the listing as already matched
        $user3MissingNotification = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);
        $user3HasNotification = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);
        $watchlistMatches->refreshForSearch($user3MissingNotification); // Sets the listing as already matched

        /**
         * Make sure there are no Saved Search Match notifications for User 1.
         */
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user1MissingNotification->user->id,
            'saved_search_id' => $user1MissingNotification->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user1HasNotification->user->id,
            'saved_search_id' => $user1HasNotification->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user1MissingNotification->user->id,
            'saved_search_id' => $user1MissingNotification->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user1HasNotification->user->id,
            'saved_search_id' => $user1HasNotification->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);

        /**
         * Make sure there are no Saved Search Match notifications for User 2.
         */
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user2MissingNotification->user->id,
            'saved_search_id' => $user2MissingNotification->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user2HasNotification->user->id,
            'saved_search_id' => $user2HasNotification->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user2MissingNotification->user->id,
            'saved_search_id' => $user2MissingNotification->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user2HasNotification->user->id,
            'saved_search_id' => $user2HasNotification->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);

        /**
         * Make sure there are no Saved Search Match notifications for User 3.
         */
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user3MissingNotification->user->id,
            'saved_search_id' => $user3MissingNotification->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user3HasNotification->user->id,
            'saved_search_id' => $user3HasNotification->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user3MissingNotification->user->id,
            'saved_search_id' => $user3MissingNotification->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user3HasNotification->user->id,
            'saved_search_id' => $user3HasNotification->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);

        Mail::fake();

        // Sleep for 1 second to simulate difference in time.
        sleep(1);

        // Execute
        $watchlistMatches->refreshForAllUsers();

        /**
         * Make sure there are no Saved Search Match Digests where sent.
         * There are 3 watchlists that should have notifications and 3 that shouldn't.
         */
        $this->assertNotificationCount(3, NotificationType::SAVED_SEARCH_MATCH_DIGEST);

        /**
         * Make sure the correct Saved Search Match notifications where stored.
         */
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user1MissingNotification->user->id,
            'saved_search_id' => $user1MissingNotification->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseHas('saved_search_notifications', [
            'user_id' => $user1HasNotification->user->id,
            'saved_search_id' => $user1HasNotification->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user2MissingNotification->user->id,
            'saved_search_id' => $user2MissingNotification->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseHas('saved_search_notifications', [
            'user_id' => $user2HasNotification->user->id,
            'saved_search_id' => $user2HasNotification->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user3MissingNotification->user->id,
            'saved_search_id' => $user3MissingNotification->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseHas('saved_search_notifications', [
            'user_id' => $user3HasNotification->user->id,
            'saved_search_id' => $user3HasNotification->id,
            'listing_id' => $listing1->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user1MissingNotification->user->id,
            'saved_search_id' => $user1MissingNotification->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseHas('saved_search_notifications', [
            'user_id' => $user1HasNotification->user->id,
            'saved_search_id' => $user1HasNotification->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user2MissingNotification->user->id,
            'saved_search_id' => $user2MissingNotification->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseHas('saved_search_notifications', [
            'user_id' => $user2HasNotification->user->id,
            'saved_search_id' => $user2HasNotification->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $user3MissingNotification->user->id,
            'saved_search_id' => $user3MissingNotification->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
        $this->assertDatabaseHas('saved_search_notifications', [
            'user_id' => $user3HasNotification->user->id,
            'saved_search_id' => $user3HasNotification->id,
            'listing_id' => $listing2->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
        ]);
    }

    /**
    * @test
    */
    public function it_refreshes_all_users_with_an_artisan_command()
    {
        // Setup
        $watchlist1 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);
        $watchlist2 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);

        $watchlist3 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);
        factory(SavedSearch::class, 5)->create();
        $listing = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
        ]);
        factory(Listing::class, 5)->states('published')->create([
            'asking_price' => 800000,
        ]);

        $this->assertEmpty($watchlist1->fresh()->listings->pluck('id'));
        $this->assertEmpty($watchlist2->fresh()->listings->pluck('id'));
        $this->assertEmpty($watchlist3->fresh()->listings->pluck('id'));

        // Execute
        Artisan::call('fe:watchlist-matches:refresh');

        // Assert
        $this->assertEquals(
            collect([$listing])->pluck('id')->sort(),
            $watchlist1->fresh()->listings->pluck('id')->sort()
        );
        $this->assertEquals(
            collect([$listing])->pluck('id')->sort(),
            $watchlist2->fresh()->listings->pluck('id')->sort()
        );
        $this->assertEquals(
            collect([$listing])->pluck('id')->sort(),
            $watchlist3->fresh()->listings->pluck('id')->sort()
        );
    }

    /**
    * @test
    */
    public function it_refreshes_an_individual_user_with_an_artisan_command()
    {
        // Setup
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $watchlist1 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user1->id,
        ]);
        $watchlist2 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user1->id,
        ]);
        $watchlist3 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
            'user_id' => $user2->id,
        ]);
        factory(SavedSearch::class, 5)->create();
        $listing = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
        ]);
        factory(Listing::class, 5)->states('published')->create([
            'asking_price' => 800000,
        ]);

        $this->assertEmpty($watchlist1->fresh()->listings->pluck('id'));
        $this->assertEmpty($watchlist2->fresh()->listings->pluck('id'));
        $this->assertEmpty($watchlist3->fresh()->listings->pluck('id'));

        // Execute
        Artisan::call('fe:watchlist-matches:refresh', [
            '--user' => $user1->id,
        ]);

        // Assert
        $this->assertEquals(
            collect([$listing])->pluck('id')->sort(),
            $watchlist1->fresh()->listings->pluck('id')->sort()
        );
        $this->assertEquals(
            collect([$listing])->pluck('id')->sort(),
            $watchlist2->fresh()->listings->pluck('id')->sort()
        );
        $this->assertEmpty($watchlist3->fresh()->listings->pluck('id'));
    }

    /**
     * @test
     */
    public function it_refreshes_all_users_without_notifications_an_artisan_command()
    {
        // Setup
        factory(SavedSearch::class, 5)->create();

        $listing = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
        ]);

        factory(Listing::class, 5)->states('published')->create([
            'asking_price' => 800000,
        ]);

        // Run the watchlist without the --dont-notify flag
        $watchlist1 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);

        $this->assertEmpty($watchlist1->fresh()->listings->pluck('id'));
        $this->assertEmpty(SavedSearchNotification::where('saved_search_id', $watchlist1->id)->get());

        Artisan::call('fe:watchlist-matches:refresh');

        $this->assertNotEmpty($watchlist1->fresh()->listings->pluck('id'));
        $this->assertNotEmpty(SavedSearchNotification::where('saved_search_id', $watchlist1->id)->get());

        // Run the watchlist with the --dont-notify flag
        $watchlist2 = factory(SavedSearch::class)->states('empty')->create([
            'asking_price_min' => 300000,
            'asking_price_max' => 600000,
        ]);

        $this->assertEmpty($watchlist2->fresh()->listings->pluck('id'));
        $this->assertEmpty(SavedSearchNotification::where('saved_search_id', $watchlist2->id)->get());

        Artisan::call('fe:watchlist-matches:refresh', [
            '--dont-notify' => null,
        ]);

        $this->assertNotEmpty($watchlist2->fresh()->listings->pluck('id'));
        $this->assertEmpty(SavedSearchNotification::where('saved_search_id', $watchlist2->id)->get());
    }
}
