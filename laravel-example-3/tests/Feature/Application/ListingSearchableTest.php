<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use App\Support\Listing\Search;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class ListingSearchableTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_does_not_index_unpublished_listings()
    {
        // Make sure the restriction is enabled
        config([
            'app.disable_listing_search_publish_restriction' => false,
        ]);

        // Setup the world
        $user = factory('App\User')->create();
        $unpublished = factory('App\Listing', 2)->create([
            'user_id' => $user->id,
            'published' => false,
        ]);
        $published = factory('App\Listing', 2)->create([
            'user_id' => $user->id,
            'published' => true,
        ]);

        // Make sure the unpublished listings are not searchbale
        $unpublished->each(function ($listing) {
            $this->assertEquals([], $listing->toSearchableArray());
        });

        // Make sure the published listings are searchable
        $published->each(function ($listing) {
            $searchable = $listing->toSearchableArray();
            $this->assertNotNull($searchable);
            $this->assertTrue(count($searchable) > 0);
            $this->assertNotEquals([], $searchable);
        });
    }

    /**
    * @test
    */
    public function it_allows_disabling_of_not_index_unpublished_listings()
    {
        // Make sure the restriction is disabled
        config([
            'app.disable_listing_search_publish_restriction' => true,
        ]);

        // Setup the world
        $user = factory('App\User')->create();
        $unpublished = factory('App\Listing', 2)->create([
            'user_id' => $user->id,
            'published' => false,
        ]);
        $published = factory('App\Listing', 2)->create([
            'user_id' => $user->id,
            'published' => true,
        ]);

        // Make sure the published listings are searchable
        $published->concat($unpublished)->each(function ($listing) {
            $searchable = $listing->toSearchableArray();
            $this->assertNotNull($searchable);
            $this->assertTrue(count($searchable) > 0);
            $this->assertNotEquals([], $searchable);
        });
    }

    /**
    * @test
    */
    public function it_disables_listing_search_from_returning_unpublished_listings()
    {
        $this->enableScout();

        // Make sure the restriction is enabled
        config([
            'app.disable_listing_search_publish_restriction' => false,
        ]);

        // Setup the world
        $user = factory('App\User')->create();
        $unpublished = factory('App\Listing', 2)->create([
            'user_id' => $user->id,
            'published' => false,
            'title' => $keyword = 'Some Title',
        ]);
        $published = factory('App\Listing', 2)->create([
            'user_id' => $user->id,
            'published' => true,
            'title' => $keyword = 'Some Title',
        ]);

        // Check simple search
        $simpleResults = (new Search())->execute();
        $this->assertEquals(
            $published->pluck('id')->sort()->values(),
            collect($simpleResults->items())->pluck('id')->sort()->values()
        );

        $this->disableScout();

        // Check complex search
        // $complexResults = (new Search(['keyword' => $keyword]))->execute();
        // $this->assertEquals(
        //     $published->pluck('id')->sort()->values(),
        //     collect($complexResults->items())->pluck('id')->sort()->values()
        // );

        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;
    }

    /**
    * @test
    */
    public function it_allows_disabling_of_disables_listing_search_from_returning_unpublished_listings()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $this->enableScout();

        // Make sure the restriction is enabled
        config([
            'app.disable_listing_search_publish_restriction' => true,
        ]);

        // Setup the world
        $user = factory('App\User')->create();
        $unpublished = factory('App\Listing', 2)->create([
            'user_id' => $user->id,
            'published' => false,
            'title' => $keyword = 'Some Title',
        ]);
        $published = factory('App\Listing', 2)->create([
            'user_id' => $user->id,
            'published' => true,
            'title' => $keyword = 'Some Title',
        ]);

        // Check simple search
        $simpleResults = (new Search())->execute();
        $this->assertEquals(
            $published->concat($unpublished)->pluck('id')->sort()->values(),
            collect($simpleResults->items())->pluck('id')->sort()->values()
        );

        // Check complex search
        $complexResults = (new Search(['keyword' => $keyword]))->execute();
        $this->assertEquals(
            $published->concat($unpublished)->pluck('id')->sort()->values(),
            collect($complexResults->items())->pluck('id')->sort()->values()
        );

        $this->disableScout();
    }
}
