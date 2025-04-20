<?php

namespace Tests\Feature\Application;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class FavoriteControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_sorts_favorites_by_completion_score_high_to_low()
    {
        $user = $this->signInWithEvents();
        $scores = $this->getSortByLCSFavoriteScores($user);

        $result = $this->json('POST', route('favorites.index', [
            'sortKey' => 'lcs_high_to_low',
        ]));

        $this->assertEquals(
            $scores->sort()->reverse()->values()->toArray(),
            collect($result->json()['data'])->pluck('current_score_total')->values()->toArray()
        );
    }

    /**
    * @test
    */
    public function it_sorts_favorites_by_completion_score_high_to_low_by_default()
    {
        $user = $this->signInWithEvents();
        $scores = $this->getSortByLCSFavoriteScores($user);

        // Don't send a sort by to test default sorting
        $result = $this->json('POST', route('favorites.index'));

        $this->assertEquals(
            $scores->sort()->reverse()->values()->toArray(),
            collect($result->json()['data'])->pluck('current_score_total')->values()->toArray()
        );
    }

    /**
    * @test
    */
    public function it_sorts_favorites_by_asking_price_high_to_low()
    {
        $prices = collect([
            1200,
            2200,
            1345,
            6000,
            100202,
            5010203,
        ])->shuffle()->values();
        $this->createFavoritesForPrices($prices);

        $result = $this->json('POST', route('favorites.index', [
            'sortKey' => 'asking_price_high_to_low',
        ]));

        $this->assertEquals(
            $prices->sort()->reverse()->values(),
            collect($result->json()['data'])->pluck('asking_price')
        );
    }

    /**
    * @test
    */
    public function it_sorts_favorites_by_asking_price_low_to_high()
    {
        $prices = collect([
            1200,
            2200,
            1345,
            6000,
            100202,
            5010203,
        ])->shuffle()->values();
        $this->createFavoritesForPrices($prices);

        $result = $this->json('POST', route('favorites.index', [
            'sortKey' => 'asking_price_low_to_high',
        ]));

        $this->assertEquals(
            $prices->sort()->values(),
            collect($result->json()['data'])->pluck('asking_price')
        );
    }

    /**
    * @test
    */
    public function it_only_lists_published_listings_in_favorites()
    {
        $user = $this->signInWithEvents();
        $published = factory('App\Favorite')->create([
            'user_id' => $user->id,
            'listing_id' => factory('App\Listing')->states('published')->create()->id,
        ]);
        $unpublished = factory('App\Favorite')->create([
            'user_id' => $user->id,
            'listing_id' => factory('App\Listing')->states('unpublished')->create()->id,
        ]);

        $result = $this->json('POST', route('favorites.index', [
            'sortKey' => 'asking_price_low_to_high',
        ]));

        $this->assertCount(1, $result->json()['data']);
    }

    /**
     * @test
     */
    public function it_favorites_a_listing()
    {
        $listing = factory('App\Listing')->states('published')->create();
        $user = $this->signInWithEvents();

        $response = $this->post(route('favorites.store'), ['listing_id' => $listing->id]);

        $response->assertStatus(302)->assertSessionHas('status')->assertSessionHas('success', true);
        $this->assertDatabaseHas('favorites', ['listing_id' => $listing->id, 'user_id' => $user->id]);
    }

    /**
     * @test
     */
    public function it_unfavorites_a_listing()
    {
        $favorite = factory('App\Favorite')->create();
        $this->signInWithEvents(User::findOrFail($favorite->user_id));

        $this->assertDatabaseHas('favorites', ['listing_id' => $favorite->listing_id, 'user_id' => $favorite->user_id]);

        $response = $this->delete(route('favorites.destroy', ['id' => $favorite->id]));

        $response->assertStatus(302)->assertSessionHas('status')->assertSessionHas('success', true);
        $this->assertDatabaseMissing('favorites', ['listing_id' => $favorite->listing_id, 'user_id' => $favorite->user_id]);
    }

    /**
     * Gets the listing completion score favorites.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getSortByLCSFavoriteScores(User $user)
    {
        $totals = collect([72, 66, 55, 42, 12])->shuffle()->values();

        $favorites = factory('App\Favorite', $totals->count())->create(['user_id' => $user->id]);

        return $favorites->values()->map(function ($favorite, $key) use ($totals) {
            $favorite->listing->current_score_total = $totals->get($key);
            $favorite->listing->save();
            return floatval($favorite->listing->fresh()->current_score_total);
        })->sort()->values();
    }

    /**
     * Gets the favorites for testing asking prices.
     *
     * @param \Illuminate\Support\Collection $prices
     * @return void
     */
    protected function createFavoritesForPrices($prices)
    {
        return factory('App\Favorite', $prices->count())->create([
            'user_id' => $this->signInWithEvents()->id
        ])
        ->each(function ($favorite, $key) use ($prices) {
            $favorite->listing->asking_price = $prices->get($key);
            $favorite->listing->save();
        });
    }
}
