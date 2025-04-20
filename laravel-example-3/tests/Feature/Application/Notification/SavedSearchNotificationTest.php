<?php

namespace Tests\Feature\Application\Notification;

use Tests\TestCase;
use App\Mail\NewNotification;
use Illuminate\Support\Facades\Mail;
use Tests\Support\HasSavedSearchHelpers;
use Tests\Support\HasNotificationTestHelpers;
use App\Support\Notification\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\SavedSearchNotification as SavedSearchNotificationModel;
use App\Support\Notification\SavedSearchNotification;

// @codingStandardsIgnoreFile
class SavedSearchNotificationTest extends TestCase
{
    use RefreshDatabase;
    use HasSavedSearchHelpers;
    use HasNotificationTestHelpers;

    /**
    * @test
    */
    public function it_sends_a_notification_when_a_matching_listing_is_transitioned_to_publish()
    {
        Mail::fake();

        // Make a listing
        $listing = factory('App\Listing')->make(['business_name' => 'This is a unique business Name']);

        // Create some random saved searches
        factory('App\SavedSearch', 20)->create();

        // Create a saved search to match
        $matchedSearch = $this->matchedSearch($listing);

        // Create a new listing (Updates the saved search)
        $listingData = $this->removeNonFillableToArray($listing);
        $listingData['published'] = true;
        $listing = factory('App\Listing')->create($listingData);

        $attributes = [
            'user_id' => $matchedSearch->user->id,
            'saved_search_id' => $matchedSearch->id,
            'listing_id' => $listing->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
            'rule_name' => $matchedSearch->name,
        ];

        // Make sure the notification was sent.
        Mail::assertSent(NewNotification::class, function ($mail) use ($matchedSearch) {
            return $mail->hasTo($matchedSearch->user->email);
        });

        // Make sure the notification was saved
        $this->assertDatabaseHas('saved_search_notifications', $attributes);

        // Update the listing (This should not send a notification)
        $listing->title = 'New Title';
        $listing->save();
        $this->assertNotificationCount(1, NotificationType::SAVED_SEARCH_MATCH);
        $this->assertCount(1, SavedSearchNotificationModel::where($attributes)->get());

        // Unpublish the listing (This should not send a notification)
        $listing->published = false;
        $listing->save();
        $this->assertNotificationCount(1, NotificationType::SAVED_SEARCH_MATCH);
        $this->assertCount(1, SavedSearchNotificationModel::where($attributes)->get());

        // Re-publish the listing (This should send another notification)
        $listing->published = true;
        $listing->save();
        $this->assertNotificationCount(2, NotificationType::SAVED_SEARCH_MATCH);
        $this->assertCount(2, SavedSearchNotificationModel::where($attributes)->get());
    }

    /**
    * @test
    */
    public function it_only_sends_watchlist_notifcations_for_published_listings()
    {
        Mail::fake();

        // Make a listing
        $listing = factory('App\Listing')->make(['business_name' => 'This is a unique business Name']);

        // Create some random saved searches
        factory('App\SavedSearch', 20)->create();

        // Create a saved search to match
        $matchedSearch = $this->matchedSearch($listing);

        // Create a new listing (Updates the saved search)
        $listingData = $this->removeNonFillableToArray($listing);
        factory('App\Listing')->create($listingData);

        // Make sure the notification was sent.
        $this->assertNotificationCount(0, NotificationType::SAVED_SEARCH_MATCH);

        // Make sure the notification was saved
        $this->assertDatabaseMissing('saved_search_notifications', [
            'user_id' => $matchedSearch->user->id,
            'saved_search_id' => $matchedSearch->id,
            'type' => NotificationType::SAVED_SEARCH_MATCH,
            'listing_id' => $listing->id,
            'rule_name' => $matchedSearch->name,
        ]);
    }

    /**
    * @test
    */
    public function it_deletes_notifications_when_deleting_a_watchlist()
    {
        // Create a search.
        $search = factory('App\SavedSearch')->create();
        $searchId = $search->id;

        // Create some notifications.
        $count = 4;
        $notifications = factory('App\SavedSearchNotification', $count)
        ->create([
            'saved_search_id' => $search->id,
        ]);

        $testNotifications = SavedSearchNotificationModel::where(
            'saved_search_id',
            $searchId
        )->get();
        $this->assertCount($count, $testNotifications);

        // Delete the search
        $search->delete();

        // Make sure the notifications where deleted
        $testNotifications = SavedSearchNotificationModel::where(
            'saved_search_id',
            $searchId
        )->get();
        $this->assertCount(0, $testNotifications);
    }
}
