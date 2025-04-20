<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use App\SavedSearch;
use App\SavedSearchNotification;
use App\ConversationNotification;
use App\ExchangeSpaceNotification;
use Tests\Support\HasExchangeSpaceCreators;
use Tests\Support\HasNotificationTestHelpers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Support\ExchangeSpace\MemberRole;

// @codingStandardsIgnoreFile
class UserAdministrationControllerTest extends TestCase
{
    use RefreshDatabase;
    use HasNotificationTestHelpers;
    use HasExchangeSpaceCreators;

    /**
    * @test
    */
    public function it_only_allows_admins_to_delete_users()
    {
        // Only developers/admins can delete users.
        $this->signInWithEvents();

        // Delete a user
        $user = factory('App\User')->create();
        $response = $this->delete(route('user-administration.destroy', ['id' => $user->id]));

        // Make sure everything went ok.
        $user = $user->fresh();
        $response->assertStatus(403);
        $this->assertFalse($user->removed_by_admin);
        $this->assertFalse($user->trashed());
    }

    /**
    * @test
    */
    public function it_allows_admins_to_delete_users()
    {
        // Only developers/admins can delete users.
        $this->signInDeveloperWithEvents();

        // Create a user as a monthly subscriber
        $user = factory('App\User')->create();
        $userEmail = $user->email;
        $this->setUserAsMonthlySubscriber($user);

        // Delete a user
        $response = $this->delete(route('user-administration.destroy', ['id' => $user->id]));

        // Make sure everything went ok.
        $user = $user->fresh();
        $response->assertStatus(302);
        $this->assertTrue($user->removed_by_admin);
        $this->assertTrue($user->trashed());
        $this->assertFalse($user->isSubscribed());
        $this->assertEquals($user->email, $userEmail); // Make sure that the re-sign up with the email ability will not work
        $this->assertFalse(!!$user->onGracePeriod());
    }

    /**
    * @test
    */
    public function it_cancels_the_users_subscription_immediately_if_they_are_on_a_grace_period_when_a_user_is_deleted()
    {
        // Only developers/admins can delete users.
        $this->signInDeveloperWithEvents();

        // Create a user as a monthly subscriber
        $user = factory('App\User')->create();
        $this->setUserAsMonthlySubscriber($user);
        $user->fresh()->currentSubscription()->cancel();
        $this->assertTrue($user->fresh()->onGracePeriod());

        // Delete a user
        $response = $this->delete(route('user-administration.destroy', ['id' => $user->id]));

        // Make sure the user is no longer subscribded
        $user = $user->fresh();
        $this->assertFalse($user->isSubscribed());
        $this->assertFalse($user->onGracePeriod());
    }

    /**
    * @test
    */
    public function it_deletes_all_listings_when_a_user_is_deleted_by_admin()
    {
        $this->signInDeveloperWithEvents();

        // Create a user and few listings.
        $user = factory('App\User')->create();
        $listings = factory('App\Listing', 5)->create(['user_id' => $user->id]);

        // Delete the user
        $response = $this->delete(route('user-administration.destroy', ['id' => $user->id]));

        // Make sure all listings are trashed.
        $listings->each(function ($listing) {
            $listing = $listing->fresh();

            // Make sure the system message was saved.
            $this->assertEquals(
                $listing->user_removed_message,
                $listing->exitSurvey->participant_message
            );

            // Make sure the listing was trashed.
            $this->assertTrue($listing->trashed());
        });
    }

    /**
     * @test
     */
    public function it_deletes_all_watchlists_when_a_user_is_deleted_by_admin()
    {
        $this->signInDeveloperWithEvents();

        // Create a user and few watchlists.
        $user = factory('App\User')->create();
        $watchlists = factory('App\SavedSearch', 5)->create(['user_id' => $user->id]);

        // Delete the user
        $response = $this->delete(route('user-administration.destroy', ['id' => $user->id]));

        // Make sure all watchlists are trashed.
        $this->assertTrue($user->savedSearches()->get()->isEmpty());
    }

    /**
     * @test
     */
    public function it_deletes_all_favorites_when_a_user_is_deleted_by_admin()
    {
        $this->signInDeveloperWithEvents();

        // Create a user and few favorites.
        $user = factory('App\User')->create();
        $favorite = factory('App\Favorite', 5)->create(['user_id' => $user->id]);

        // Delete the user
        $response = $this->delete(route('user-administration.destroy', ['id' => $user->id]));

        // Make sure all favorite are trashed.
        $this->assertTrue($user->favorites()->get()->isEmpty());
    }

    /**
     * @test
     */
    public function it_deletes_all_notifications_when_a_user_is_deleted_by_admin()
    {
        $this->signInDeveloperWithEvents();

        // Create a user and some notifications.
        $user = factory('App\User')->create();
        factory('App\ExchangeSpaceNotification', 4)->create(['user_id' => $user->id]);
        factory('App\ConversationNotification', 4)->create(['user_id' => $user->id]);
        factory('App\SavedSearchNotification', 4)->create(['user_id' => $user->id]);

        // Make sure all notification types currently have notifications.
        $this->assertFalse(ExchangeSpaceNotification::where('user_id', $user->id)->get()->isEmpty());
        $this->assertFalse(ConversationNotification::where('user_id', $user->id)->get()->isEmpty());
        $this->assertFalse(SavedSearchNotification::where('user_id', $user->id)->get()->isEmpty());

        // Delete the user
        $response = $this->delete(route('user-administration.destroy', ['id' => $user->id]));

        $this->assertTrue(ExchangeSpaceNotification::where('user_id', $user->id)->get()->isEmpty());
        $this->assertTrue(ConversationNotification::where('user_id', $user->id)->get()->isEmpty());
        $this->assertTrue(SavedSearchNotification::where('user_id', $user->id)->get()->isEmpty());
    }

    /**
     * @test
     */
    public function it_rejects_all_buyer_inquires_when_a_user_is_deleted_by_admin()
    {
        $this->signInDeveloperWithEvents();

        // Create a user and some business inquiries.
        $user = factory('App\User')->create();
        $inquirySeller = $this->createInquiryConversation([], $seller = $user, $buyer = null)->space;
        $inquiryBuyer = $this->createInquiryConversation([], $seller = null, $buyer = $user)->space;

        // Delete the user
        $response = $this->delete(route('user-administration.destroy', ['id' => $user->id]));

        // Make sure the inquiry is rejected and trashed.
        $rejectionReason = 'User Left Platform';
        $inquirySeller = $inquirySeller->fresh();
        $inquiryBuyer = $inquiryBuyer->fresh();
        $this->assertTrue($inquirySeller->isRejected());
        $this->assertEquals($rejectionReason, $inquirySeller->rejectionReason->reason);
        $this->assertTrue($inquirySeller->trashed());
        $this->assertTrue($inquiryBuyer->isRejected());
        $this->assertTrue($inquiryBuyer->trashed());
        $this->assertEquals($rejectionReason, $inquiryBuyer->rejectionReason->reason);
    }

    /**
     * @test
     */
    public function it_closes_all_exchange_spaces_when_a_user_is_deleted_by_admin()
    {
        $this->signInDeveloperWithEvents();

        // Create a user and some exchange spaces.
        $user = factory('App\User')->create();
        $spaceSeller = $this->createSpaceConversation([], $seller = $user, $buyer = null)->space;
        $spaceBuyer = $this->createSpaceConversation([], $seller = null, $buyer = $user)->space;
        $spaceBuyerAdvisor = $this->createSpaceConversation([], $seller = null, $buyer = null)->space;
        $this->addExchangeSpaceAdvisor($spaceBuyerAdvisor, $isSellerAdvisor = false, $user);
        $spaceSellerAdvisor = $this->createSpaceConversation([], $seller = null, $buyer = null)->space;
        $this->addExchangeSpaceAdvisor($spaceSellerAdvisor, $isSellerAdvisor = true, $user);

        // Delete the user
        $response = $this->delete(route('user-administration.destroy', ['id' => $user->id]));

        // Make sure the seller exchange spaces where deleted.
        $spaceSeller = $spaceSeller->fresh();
        $this->assertTrue($spaceSeller->trashed());
        $this->assertCount(0, $user->fresh()->spaces);

        // Make sure the user was removed from the exchange space as a buyer
        $spaceBuyer = $spaceBuyer->fresh();
        $spaceBuyerMember = $spaceBuyer->allMembers()->withTrashed()->where('user_id', $user->id)->first();
        $this->assertFalse($spaceBuyerMember->active);
        $this->assertTrue($spaceBuyerMember->trashed());
        $spaceBuyer->allMembers->each(function ($member) {
            if ($member->role === MemberRole::SELLER) {
                $this->assertTrue($member->active);
            } else {
                $this->assertFalse($member->active);
            }
        });
        $this->assertFalse($spaceBuyer->trashed());

        // Make sure the user was removed from the exchange space as a buyer advisor
        $spaceBuyerAdvisor = $spaceBuyerAdvisor->fresh();
        $this->assertFalse($spaceBuyerAdvisor->allMembers()->withTrashed()->where('user_id', $user->id)->first()->active);
        $this->assertFalse($spaceBuyerAdvisor->trashed());

        // Make sure the user was removed from the exchange space as a seller advisor
        $spaceSellerAdvisor = $spaceSellerAdvisor->fresh();
        $this->assertFalse($spaceSellerAdvisor->allMembers()->withTrashed()->where('user_id', $user->id)->first()->active);
        $this->assertFalse($spaceSellerAdvisor->trashed());
    }
}
