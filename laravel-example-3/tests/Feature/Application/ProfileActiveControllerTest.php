<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use App\SavedSearchNotification;
use App\ConversationNotification;
use App\ExchangeSpaceNotification;
use App\Support\ExchangeSpace\MemberRole;
use Tests\Support\HasExchangeSpaceCreators;
use Tests\Support\HasNotificationTestHelpers;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class ProfileActiveControllerTest extends TestCase
{
    use RefreshDatabase;
    use HasNotificationTestHelpers;
    use HasExchangeSpaceCreators;

    /**
     * @test
     */
    public function it_allows_users_to_close_their_account()
    {
        // Users can only remove themselves
        $user = $this->signInWithEvents();
        $userEmail = $user->email;

        // Delete a user
        $response = $this->delete(route('profile.destroy'));

        // Make sure everything went ok.
        $user = $user->fresh();
        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
        $this->assertFalse($user->removed_by_admin);
        $this->assertTrue(preg_match("/{$userEmail}..+.deleted/us", $user->email) === 1);
        $this->assertTrue($user->trashed());
        $this->assertNull(auth()->user());
    }

    /**
     * @test
     */
    public function it_deletes_all_watchlists_when_a_user_closes_their_account()
    {
        // Create a user and few watchlists.
        $user = $this->signInWithEvents();
        $watchlists = factory('App\SavedSearch', 5)->create(['user_id' => $user->id]);

        // Delete the user
        $response = $this->delete(route('profile.destroy'));

        // Make sure all watchlists are trashed.
        $this->assertTrue($user->savedSearches()->get()->isEmpty());
    }

    /**
     * @test
     */
    public function it_deletes_all_favorites_when_a_user_closes_their_account()
    {
        // Create a user and few favorites.
        $user = $this->signInWithEvents();
        $favorite = factory('App\Favorite', 5)->create(['user_id' => $user->id]);

        // Delete the user
        $response = $this->delete(route('profile.destroy'));

        // Make sure all favorite are trashed.
        $this->assertTrue($user->favorites()->get()->isEmpty());
    }

    /**
     * @test
     */
    public function it_deletes_all_notifications_when_a_user_closes_their_account()
    {
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
        $this->signInWithEvents($user);
        $response = $this->delete(route('profile.destroy'));

        $this->assertTrue(ExchangeSpaceNotification::where('user_id', $user->id)->get()->isEmpty());
        $this->assertTrue(ConversationNotification::where('user_id', $user->id)->get()->isEmpty());
        $this->assertTrue(SavedSearchNotification::where('user_id', $user->id)->get()->isEmpty());
    }

    /**
     * @test
     */
    public function it_does_not_allow_users_to_close_their_account_if_listings_still_exist()
    {
        // Setup the user
        $user = $this->signInWithEvents();
        factory('App\Listing')->create(['user_id' => $user->id]);
        $user = $user->fresh();

        // Try to delete the user
        $this->delete(route('profile.destroy'))
        ->assertStatus(302)
        ->assertSessionHas(
            'general-error',
            $user->cannotCloseAccountBecauseListings()
        );
    }

    /**
     * @test
     */
    public function it_does_not_allow_users_to_close_their_account_if_they_have_not_canceled_their_subscription()
    {
        // Setup the user
        $user = $this->signInWithEvents();
        $this->setUserAsMonthlySubscriber($user);

        // Try to delete the user
        $this->delete(route('profile.destroy'))
            ->assertStatus(302)
            ->assertSessionHas(
                'general-error',
                $user->cannotCloseAccountBecauseSubscribed()
            );
    }
}
