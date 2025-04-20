<?php

namespace Tests\Unit\Application;

use App\Listing;
use Tests\TestCase;
use App\SavedSearch;
use App\SavedSearchListing;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignorefile
class SavedSearchTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_retrieves_new_saved_search_listings()
    {
        $now = now();

        // Saved search will match any listing and is refreshed in the "present".
        $savedSearch = factory(SavedSearch::class)->states('empty')->create([
            'refreshed_at' => $now,
        ]);

        // Listings are created in the "past".
        factory(Listing::class, 5)->states('published')->create([
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(5),
        ]);
        $listing1 = factory(Listing::class)->states('published')->create([
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(5),
        ]);
        $listing2 = factory(Listing::class)->states('published')->create([
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(5),
        ]);

        // Saved search is created in the "past".
        $oldSavedSearchListing = factory(SavedSearchListing::class)->create([
            'saved_search_id' => $savedSearch->id,
            'listing_id' => $listing1->id,
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        // Saved search is created in the "present".
        $newSavedSearchListing = factory(SavedSearchListing::class)->create([
            'saved_search_id' => $savedSearch->id,
            'listing_id' => $listing2->id,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->assertEquals(
            [$listing1->id, $listing2->id],
            $savedSearch->fresh()->listings->pluck('id')->sort()->values()->toArray()
        );

        $this->assertEquals(
            [$listing2->id],
            $savedSearch->fresh()->newListings->pluck('id')->values()->toArray()
        );
    }

    /**
    * @test
    */
    public function it_only_retrieves_published_listings()
    {
        $search = factory('App\SavedSearch')->create();

        $published = factory(SavedSearchListing::class)->create([
            'listing_id' => factory('App\Listing')->states('published')->create()->id,
            'saved_search_id' => $search->id,
        ]);

        $unpublished = factory(SavedSearchListing::class)->create([
            'listing_id' => factory('App\Listing')->states('unpublished')->create()->id,
            'saved_search_id' => $search->id,
        ]);

        $this->assertCount(1, $listings = $search->fresh()->listings);
        $this->assertEquals([$published->id], $listings->pluck('id')->toArray());
    }

    /**
     * @test
     */
    public function it_only_retrieves_published_new_listings()
    {
        $savedSearch = factory(SavedSearch::class)->states('empty')->create([
            'refreshed_at' => now()->subMinute(),
        ]);

        // Listings are created in the "past".
        factory(Listing::class, 5)->states('published')->create([
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(5),
        ]);
        $published = factory(Listing::class)->states('published')->create([
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(5),
        ]);
        $unpublished = factory(Listing::class)->states('unpublished')->create([
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(5),
        ]);

        // Saved search is created in the "past".
        $oldSavedSearchListing = factory(SavedSearchListing::class)->create([
            'saved_search_id' => $savedSearch->id,
            'listing_id' => $published->id,
        ]);
        $newSavedSearchListing = factory(SavedSearchListing::class)->create([
            'saved_search_id' => $savedSearch->id,
            'listing_id' => $unpublished->id,
        ]);

        $this->assertEquals(
            [$published->id],
            $savedSearch->fresh()->newListings->pluck('id')->values()->toArray()
        );
    }
}
