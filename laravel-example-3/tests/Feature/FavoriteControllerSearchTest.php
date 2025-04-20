<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class FavoriteControllerSearchTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->enableScout();
    }

    public function tearDown()
    {
        $this->disableScout();

        parent::tearDown();
    }

    /**
    * @test
    */
    public function it_searches_favorite_listings_by_title()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $user = $this->signInWithEvents();

        // Matched listing/favorite by listing title.
        $keyword = 'Matched Title';
        $matchedListing = factory('App\Listing')->create([
            'title' => $keyword,
        ]);

        $this->checkFavoriteSearch($matchedListing, $keyword, $user);
    }

    /**
    * @test
    */
    public function it_searches_favorite_listings_by_city()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $user = $this->signInWithEvents();

        // Matched listing/favorite by listing city.
        $keyword = 'Matched City';
        $matchedListing = factory('App\Listing')->create([
            'city' => $keyword,
        ]);

        $this->checkFavoriteSearch($matchedListing, $keyword, $user);
    }

    /**
    * @test
    */
    public function it_searches_favorite_listings_by_state()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $user = $this->signInWithEvents();

        // Matched listing/favorite by listing state.
        $keyword = 'Missouri';
        $matchedListing = factory('App\Listing')->create([
            'state' => 'MO',
        ]);

        $this->checkFavoriteSearch($matchedListing, $keyword, $user);
    }

    /**
    * @test
    */
    public function it_searches_favorite_listings_by_summary_business_description()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $user = $this->signInWithEvents();

        // Matched listing/favorite by listing business description.
        $keyword = 'Some summary';
        $matchedListing = factory('App\Listing')->create([
            'summary_business_description' => implode(' ', [
                $this->faker->words(5, true),
                $keyword,
                $this->faker->words(5, true),
            ]),
        ]);

        $this->checkFavoriteSearch($matchedListing, $keyword, $user);
    }

    /**
    * @test
    */
    public function it_searches_favorite_listings_by_business_description()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $user = $this->signInWithEvents();

        // Matched listing/favorite by listing summary business description.
        $keyword = 'Some Description';
        $matchedListing = factory('App\Listing')->create([
            'business_description' => implode(' ', [
                $this->faker->words(5, true),
                $keyword,
                $this->faker->words(5, true),
            ]),
        ]);

        $this->checkFavoriteSearch($matchedListing, $keyword, $user);
    }

    /**
    * @test
    */
    public function it_searches_favorite_listings_by_products_services()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $user = $this->signInWithEvents();

        // Matched listing/favorite by listing products and services.
        $keyword = 'Some product or services';
        $matchedListing = factory('App\Listing')->create([
            'products_services' => implode(' ', [
                $this->faker->words(5, true),
                $keyword,
                $this->faker->words(5, true),
            ]),
        ]);

        $this->checkFavoriteSearch($matchedListing, $keyword, $user);
    }

    /**
    * @test
    */
    public function it_searches_favorite_listings_by_business_name()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $user = $this->signInWithEvents();

        // Matched listing/favorite by listing business name.
        $keyword = 'Some Business Name';
        $matchedListing = factory('App\Listing')->create([
            'business_name' => $keyword,
            'name_visible' => true,
        ]);

        $this->checkFavoriteSearch($matchedListing, $keyword, $user);
    }

    protected function checkFavoriteSearch($matchedListing, $keyword, $user)
    {
        factory('App\Favorite')->create([
            'user_id' => $user->id,
            'listing_id' => $matchedListing->id,
        ]);

        // Un-matched listings
        factory('App\Listing', 10)->create([
            'title' => 'Some Other Title',
        ])->each(function ($listing) use ($user) {
            factory('App\Favorite')->create([
                'user_id' => $user->id,
                'listing_id' => $listing->id,
            ]);
        });

        // Search favorites.
        $result = $this->json('POST', route('favorites.index', [
            'keyword' => $keyword,
        ]));

        // Check the listings
        $listings = r_collect($result->json()['data']);
        $this->assertCount(1, $listings);
        $this->assertEquals(
            $matchedListing->id,
            $listings->first()->get('id')
        );
    }
}
