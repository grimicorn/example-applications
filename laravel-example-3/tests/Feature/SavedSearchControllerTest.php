<?php

namespace Tests\Feature;

use App\Listing;
use Tests\TestCase;
use App\SavedSearch;
use Laravel\Spark\User;
use App\SavedSearchNotification;
use App\Support\HasSearchInputs;
use Illuminate\Support\Facades\Mail;
use Tests\Support\HasNotificationTestHelpers;
use App\Support\Notification\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\SavedSearchListing;

// @codingStandardsIgnoreStart
class SavedSearchControllerTest extends TestCase
{
    use RefreshDatabase,
        HasNotificationTestHelpers,
        HasSearchInputs;

    /**
     * @test
     */
    public function it_requires_a_user_to_be_logged_in_to_save_a_search()
    {
        // Get search data
        $search = factory(SavedSearch::class)->make()->toArray();

        // Try to save the request data.
        $this->post(route('saved-searches.store'), $search)
               ->assertRedirect(route('login'));

        // Sign in a user to save the search.
        $this->signInWithEvents();

        // Make sure that they where able to access the saved search.
        $this->post(route('saved-searches.store'), $search)
               ->assertStatus(302)
               ->assertSessionHas('status')
               ->assertSessionHas('success', true);
    }

    /**
     * @test
     */
    public function it_stores_a_saved_search()
    {
        $this->withoutExceptionHandling();
        // Sign in a user to save the search.
        $user = $this->signInWithEvents();

        // Get search data
        $search = collect(factory(SavedSearch::class)->make([
            'user_id' => $user->id,
            'keyword' => 'Keword',
        ])->toArray())
        ->only([
            'business_categories',
            'zip_code',
            'zip_code_radius',
            'asking_price_min',
            'asking_price_max',
            'keyword',
            'name',
        ]);

        // Save the request data.
        $response = $this->post(route('saved-searches.store'), $search->toArray());

        // Make sure the response was okay.
        $response->assertStatus(302)
                         ->assertSessionHas('status')
                         ->assertSessionHas('success', true);

        // Make sure the saved search was stored.
        $this->assertCount(1, $user->fresh()->savedSearches);
        $userSavedSearches = $user->fresh()->savedSearches->first()->toArray();
        foreach ($search->toArray() as $key => $value) {
            if ($key !== 'show_url') {
                $this->assertEquals($value, $userSavedSearches[$key]);
            }
        }
    }

    /**
     * @test
     */
    public function it_updates_a_saved_search()
    {
        // Sign in a user to save the search.
        $user = $this->signInWithEvents();

        // Create a search to update.
        $savedSearch = factory(SavedSearch::class)->create(['user_id' => $user->id]);

        // Get search data
        $search = factory(SavedSearch::class)->make(['user_id' => $user->id]);

        // Save the request data.
        $response = $this->post(route('saved-searches.update', ['id' => $savedSearch->id]), $search->toArray());

        // Make sure the response was okay.
        $response->assertStatus(302)
                         ->assertSessionHas('status')
                         ->assertSessionHas('success', true);

        // Make sure the saved search was stored.
        $this->assertCount(1, $user->fresh()->savedSearches);
        $userSavedSearches = $user->fresh()->savedSearches->first()->toArray();
        foreach ($search->toArray() as $key => $value) {
            if ($key !== 'show_url') {
                $this->assertEquals($value, $userSavedSearches[$key]);
            }
        }
    }

    /**
     * @test
     */
    public function it_removes_businesS_categories_if_missing_from_a_saved_search()
    {
        // Sign in a user to save the search.
        $user = $this->signInWithEvents();

        // Create a search to update.
        $savedSearch = factory(SavedSearch::class)->create(['user_id' => $user->id]);

        $this->assertNotNull($savedSearch->business_categories);

        // Get search data
        $search = factory(SavedSearch::class)->make(['user_id' => $user->id]);

        // Save the request data.
        $request = collect($search->toArray())->except(['business_categories'])->toArray();
        $response = $this->post(route('saved-searches.update', ['id' => $savedSearch->id]), $request);

        // Make sure the response was okay.
        $response->assertStatus(302)
                         ->assertSessionHas('status')
                         ->assertSessionHas('success', true);

        // Make sure the saved search was stored.
        $this->assertEquals([], $savedSearch->fresh()->business_categories);
    }

    /**
     * @test
     */
    public function it_only_lists_published_listings_in_watchlist()
    {
        $search = factory('App\SavedSearch')->create();

        $published = factory(SavedSearchListing::class)->create([
            'listing_id' => factory(Listing::class)->states('published')->create()->id,
            'saved_search_id' => $search->id,
        ]);

        $unpublished = factory(SavedSearchListing::class)->create([
            'listing_id' => factory(Listing::class)->states('unpublished')->create()->id,
            'saved_search_id' => $search->id,
        ]);


        $this->assertCount(1, $listings = $search->fresh()->listings);
        $this->assertEquals([$published->id], $listings->pluck('id')->toArray());
    }

    /**
    * @test
    */
    public function it_updates_search_session_inputs_when_requested_when_creating_a_search()
    {
        $this->withoutExceptionHandling();
        // Sign in a user to save the search.
        $user = $this->signInWithEvents();

        // Set some search inputs
        $sessionKey = 'listing.search.inputs';
        $this->setSearchInputs($this->getSearch($user), $sessionKey);
        $this->assertNotEmpty(session()->get($sessionKey));

        // Save the search
        $search = $this->getSearch($user, ['flush' => true]);
        $response = $this->post(route('saved-searches.store'), $search->toArray());

        $search->put('saved_search_id', SavedSearch::orderBy('updated_at', 'desc')->first()->id);
        $this->assertEquals(
            $search->sortBy(function ($value, $key) {
                return $key;
            })->except('flush'),
            collect(session()->get($sessionKey))->sortBy(function ($value, $key) {
                return $key;
            })
        );
    }

    /**
     * @test
     */
    public function it_does_not_update_search_session_inputs_if_not_requested_when_creating_a_search()
    {
        // Sign in a user to save the search.
        $user = $this->signInWithEvents();

        // Set some search inputs
        $sessionKey = 'listing.search.inputs';
        $this->setSearchInputs($this->getSearch($user), $sessionKey);
        $this->assertNotEmpty($initialInputs = session()->get($sessionKey));

        // Save the search
        $this->post(route('saved-searches.store'), $this->getSearch($user)->toArray());

        $this->assertEquals(
            collect($initialInputs)->sortBy(function ($value, $key) {
                return $key;
            }),
            collect(session()->get($sessionKey))->sortBy(function ($value, $key) {
                return $key;
            })
        );
    }

    /**
     * @test
     */
    public function it_destroys_a_saved_search()
    {
        // Sign in a user to save the search.
        $user = $this->signInWithEvents();

        // Create a search to destroy.
        $search = factory(SavedSearch::class)->create(['user_id' => $user->id]);

        // Save the request data.
        $response = $this->delete(route('saved-searches.destroy', ['id' => $search->id]));

        // Make sure the response was okay.
        $response->assertStatus(302)
                         ->assertSessionHas('status')
                         ->assertSessionHas('success', true);

        // Make sure the saved search was stored.
        $this->assertCount(0, $user->fresh()->savedSearches);
    }

    /**
     * @test
     */
    public function it_updates_found_listings_when_a_watchlist_is_updated()
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

        Mail::fake();

        $this->post(route('saved-searches.update', ['id' => $watchlist->id]), [
            'name' => 'New Name',
        ]);

        $this->assertEquals(
            collect([$listing])->pluck('id')->sort(),
            $watchlist->fresh()->listings->pluck('id')->sort()
        );

        $this->assertNotificationCount(0, NotificationType::SAVED_SEARCH_MATCH);
        $this->assertCount(0, SavedSearchNotification::where([
            'saved_search_id' => $watchlist->id,
            'user_id' => $watchlist->user_id,
        ])->get());
    }

    /**
     * @test
     */
    public function it_updates_found_listings_when_a_watchlist_is_created()
    {
        $user = $this->signInWithEvents();
        $listing = factory(Listing::class)->states('published')->create([
            'asking_price' => 500000,
        ]);
        factory(Listing::class, 5)->states('published')->create([
            'asking_price' => 800000,
        ]);

        Mail::fake();

        $this->post(
            route('saved-searches.store'),
            $attributes = [
                'name' => 'Example Search',
                'asking_price_min' => 300000,
                'asking_price_max' => 600000,
            ]
        );

        $watchlist = SavedSearch::where($attributes)->first();

        $this->assertEquals(
            collect([$listing])->pluck('id')->sort(),
            $watchlist->listings->pluck('id')->sort()
        );

        $this->assertNotificationCount(0, NotificationType::SAVED_SEARCH_MATCH);
        $this->assertCount(0, SavedSearchNotification::where([
            'saved_search_id' => $watchlist->id,
            'user_id' => $watchlist->user_id,
        ])->get());
    }

    protected function getSearch(User $user, $data = [])
    {
        return collect(factory(SavedSearch::class)->make([
            'user_id' => $user->id,
            'keyword' => 'Keword',
        ])->toArray())
        ->only([
            'business_categories',
            'zip_code',
            'zip_code_radius',
            'asking_price_min',
            'asking_price_max',
            'keyword',
            'name',
        ])->merge($data);
    }
}
