<?php

namespace Tests\Feature\Application;

use App\Listing;
use Carbon\Carbon;
use Tests\TestCase;
use App\ListingCompletionScoreTotal;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class ListingTableFilterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_only_filters_the_current_users_listings()
    {
        // This sign in a user to view their listings
        $user = $this->signInWithEvents();

        // Create one listings that will not be the users listing.
        $notUsersListing = factory(Listing::class)->create();

        // Create one listings that will be the users listing.
        $usersListing = factory(Listing::class)->create(['user_id' => $user->id]);

        // Retrieve the listings.
        $response = $this->json('GET', route('listing.index'), ['page' => '1']);

        // Check the listings
        $this->assertCount(1, $response->json()['data']);
    }

    /**
     * @test
     */
    public function it_paginates_listings()
    {
        // This sign in a user to view their listings
        $user = $this->signInWithEvents();

        // Create 20 listings. We will only want to retrieve 10.
        $listings = factory(Listing::class, 20)->create(['user_id' => $user->id]);

        // Retrieve the listings.
        $response = $this->json('GET', route('listing.index'), ['page' => '1']);

        // Assert we only received 10 listings.
        $this->assertEquals(10, count(collect($response->json())->get('data')));
    }

    /**
     * @test
     */
    public function it_orders_listings_by_business_name_ascending()
    {
        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Set some test names to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 names to be safe.
        $business_names = [
            'Atest',
            'Btest',
            'Ctest',
            'Dtest',
            'Etest',
            'Ftest',
            'Gtest',
            'Htest',
            'Itest',
        ];
        shuffle($business_names);

        // Create some listings to sort.
        foreach ($business_names as $business_name) {
            factory(Listing::class)->create(['business_name' => $business_name, 'user_id' => $user->id]);
        }

        // Get the listings sorted by business name ascending.
        $response = $this->json('GET', route('listing.index'), [
            'sortKey' => 'business_name',
            'sortOrder' => 'asc',
            'page' => '1',
        ]);

        // Sort the listing names to assert correct order.
        sort($business_names);

        // Assert the names where sorted correctly.
        $listing_names = collect(collect($response->json())->get('data'))->pluck('business_name')->toArray();
        $this->assertEquals(array_values($business_names), array_values($listing_names));
    }

    /**
     * @test
     */
    public function it_orders_listings_by_business_name_descending()
    {
        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Set some test names to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 names to be safe.
        $business_names = [
            'Atest',
            'Btest',
            'Ctest',
            'Dtest',
            'Etest',
            'Ftest',
            'Gtest',
            'Htest',
            'Itest',
        ];
        shuffle($business_names);

        // Create some listings to sort.
        foreach ($business_names as $business_name) {
            factory(Listing::class)->create(['business_name' => $business_name, 'user_id' => $user->id]);
        }

        // Get the listings sorted by business name descending.
        $response = $this->json('GET', route('listing.index'), [
            'sortKey' => 'business_name',
            'sortOrder' => 'desc',
            'page' => '1',
        ]);

        // Sort the listing names to assert correct order.
        sort($business_names);
        $business_names = array_reverse($business_names);

        // Assert the names where sorted correctly.
        $listing_names = collect(collect($response->json())->get('data'))->pluck('business_name')->toArray();
        $this->assertEquals(array_values($business_names), array_values($listing_names));
    }

    /**
     * @test
     */
    public function it_orders_listings_by_created_at_ascending()
    {
        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Set some test created ats to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 created ats to be safe.
        $created_ats = [
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
        ];
        shuffle($created_ats);

        // Create some listings to sort.
        foreach ($created_ats as $created_at) {
            factory(Listing::class)->create(['created_at' => $created_at, 'user_id' => $user->id]);
        }

        // Get the listings sorted by created at ascending.
        $response = $this->json('GET', route('listing.index'), [
            'sortKey' => 'created_at',
            'sortOrder' => 'asc',
            'page' => '1',
        ]);

        // Sort the listing names to assert correct order.
        sort($created_ats);

        // Assert the names where sorted correctly.
        $listing_created_ats = collect(collect($response->json())->get('data'))->pluck('created_at')->toArray();
        $this->assertEquals(array_values($created_ats), array_values($listing_created_ats));
    }

    /**
     * @test
     */
    public function it_orders_listings_by_created_at_descending()
    {
        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Set some test created ats to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 created ats to be safe.
        $created_ats = [
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
            new Carbon($this->faker->dateTimeThisYear()->format('c')),
        ];
        shuffle($created_ats);

        // Create some listings to sort.
        foreach ($created_ats as $created_at) {
            factory(Listing::class)->create(['created_at' => $created_at, 'user_id' => $user->id]);
        }

        // Get the listings sorted by created at ascending.
        $response = $this->json('GET', route('listing.index'), [
            'sortKey' => 'created_at',
            'sortOrder' => 'desc',
            'page' => '1',
        ]);

        // Sort the listing names to assert correct order.
        sort($created_ats);
        $created_ats = array_reverse($created_ats);

        // Assert the names where sorted correctly.
        $listing_created_ats = collect(collect($response->json())->get('data'))->pluck('created_at')->toArray();
        $this->assertEquals(array_values($created_ats), array_values($listing_created_ats));
    }

    /**
     * @test
     */
    public function it_orders_listings_by_published_status_ascending()
    {
        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Set some test publish to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 publish to be safe.
        $statuses = [
            $this->faker->boolean(),
            $this->faker->boolean(),
            $this->faker->boolean(),
            $this->faker->boolean(),
            $this->faker->boolean(),
            $this->faker->boolean(),
            $this->faker->boolean(),
            $this->faker->boolean(),
            $this->faker->boolean(),
            $this->faker->boolean(),
        ];
        shuffle($statuses);

        // Create some listings to sort.
        foreach ($statuses as $status) {
            factory(Listing::class)->create(['published' => $status, 'user_id' => $user->id]);
        }

        // Get the listings sorted by created at ascending.
        $response = $this->json('GET', route('listing.index'), [
            'sortKey' => 'published',
            'sortOrder' => 'asc',
            'page' => '1',
        ]);

        // Sort the listing names to assert correct order.
        sort($statuses);

        // Assert the names where sorted correctly.
        $listing_statuses = collect(collect($response->json())->get('data'))->pluck('published')->toArray();
        $this->assertEquals(array_values($statuses), array_values($listing_statuses));
    }

    /**
     * @test
     */
    public function it_orders_listings_by_published_status_descending()
    {
        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Set some test publish to sort and shuffle them so it is not just a coincidence that the order is correct.
        // The pagination limit is set to 10 so there are 10 publish to be safe.
        $statuses = [
            $this->faker->boolean(),
            $this->faker->boolean(),
            $this->faker->boolean(),
            $this->faker->boolean(),
            $this->faker->boolean(),
            $this->faker->boolean(),
            $this->faker->boolean(),
            $this->faker->boolean(),
            $this->faker->boolean(),
            $this->faker->boolean(),
        ];
        shuffle($statuses);

        // Create some listings to sort.
        foreach ($statuses as $status) {
            factory(Listing::class)->create(['published' => $status, 'user_id' => $user->id]);
        }

        // Get the listings sorted by created at ascending.
        $response = $this->json('GET', route('listing.index'), [
            'sortKey' => 'published',
            'sortOrder' => 'desc',
            'page' => '1',
        ]);

        // Sort the listing names to assert correct order.
        sort($statuses);
        $statuses = array_reverse($statuses);

        // Assert the names where sorted correctly.
        $listing_statuses = collect(collect($response->json())->get('data'))->pluck('published')->toArray();
        $this->assertEquals(array_values($statuses), array_values($listing_statuses));
    }

    /**
     * @test
     * @group failing
     */
    public function it_searches_for_listings()
    {
        // Setup
        $search_query = 'thisshouldbefoundwhensearching';
        $expected_listing_ids = [];

        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Create listings with names column that could match.
        $expected_listing_ids[] = factory(Listing::class)->create(['business_name' => $search_query, 'user_id' => $user->id])->id;

        // Create a few listings that will not be matched.
        factory(Listing::class, 5)->create(['business_name' => 'thiswillnotmatch', 'user_id' => $user->id]);

        // Get the listings that match the search query.
        $response = $this->json('GET', route('listing.index'), [
            'search' => $search_query,
        ]);

        // Assert the the correct listings where found. We do not care about the order.
        $listing_ids = collect(collect($response->json())->get('data'))->pluck('id')->toArray();
        sort($listing_ids);
        sort($expected_listing_ids);
        $this->assertEquals($expected_listing_ids, $listing_ids);
    }

    /**
     * @test
     */
    public function it_searches_for_published_listings()
    {
        // Setup
        $search_query = 'PuBlish';
        $expected_listing_ids = [];

        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Create listings with names column that could match.
        $expected_listing_ids[] = factory(Listing::class)->create(['published' => true, 'user_id' => $user->id])->id;

        // Create a few listings that will not be matched.
        factory(Listing::class, 5)->create(['published' => false, 'user_id' => $user->id]);

        // Get the listings that match the search query.
        $response = $this->json('GET', route('listing.index'), [
            'search' => $search_query,
        ]);

        // Assert the the correct listings where found. We do not care about the order.
        $listing_ids = collect(collect($response->json())->get('data'))->pluck('id')->toArray();
        sort($listing_ids);
        sort($expected_listing_ids);
        $this->assertEquals($expected_listing_ids, $listing_ids);
    }

    /**
     * @test
     */
    public function it_searches_for_draft_listings()
    {
        // Setup
        $search_query = 'DraFt';
        $expected_listing_ids = [];

        // Sign in a "user".
        $user = $this->signInWithEvents();

        // Create listings with names column that could match.
        $expected_listing_ids[] = factory(Listing::class)->create(['published' => false, 'user_id' => $user->id])->id;

        // Create a few listings that will not be matched.
        factory(Listing::class, 5)->create(['published' => true, 'user_id' => $user->id]);

        // Get the listings that match the search query.
        $response = $this->json('GET', route('listing.index'), [
            'search' => $search_query,
        ]);

        // Assert the the correct listings where found. We do not care about the order.
        $listing_ids = collect(collect($response->json())->get('data'))->pluck('id')->toArray();
        sort($listing_ids);
        sort($expected_listing_ids);
        $this->assertEquals($expected_listing_ids, $listing_ids);
    }

    /**
    * @test
    */
    public function it_sorts_listings_by_score_total_ascending()
    {
        $user = $this->signInWithEvents();

        $totals = collect([
            12,
            42,
            55,
            66,
            72,
        ]);
        $listings = factory('App\Listing', $totals->count())->create([
            'user_id' => $user->id,
        ]);
        $shuffledTotals = $totals->shuffle();
        $listings = $listings->each(function ($listing, $key) use ($shuffledTotals) {
            $total = new ListingCompletionScoreTotal;
            $total->listing_id = $listing->id;
            $total->fill([
                'total' => intval($shuffledTotals->get($key)),
            ]);
            $total->save();
        })->map->fresh();

        $response = $this->json('GET', route('listing.index'), [
            'sortKey' => 'score_total',
            'sortOrder' => 'asc',
        ]);

        $this->assertEquals(
            $listings->fresh()
            ->pluck('score_total_percentage_for_display')
            ->sort()->values(),
            collect($response->json()['data'])->map(function ($listing) {
                return $listing['score_total_percentage_for_display'];
            })
        );
    }

    /**
    * @test
    */
    public function it_sorts_listings_by_score_total_descending()
    {
        $user = $this->signInWithEvents();

        $totals = collect([
            12,
            42,
            55,
            66,
            72,
        ]);
        $listings = factory('App\Listing', $totals->count())->create([
            'user_id' => $user->id,
        ]);
        $shuffledTotals = $totals->shuffle();
        $listings = $listings->each(function ($listing, $key) use ($shuffledTotals) {
            $total = new ListingCompletionScoreTotal;
            $total->listing_id = $listing->id;
            $total->fill([
                'total' => intval($shuffledTotals->get($key)),
            ]);
            $total->save();
        })->map->fresh();

        $response = $this->json('GET', route('listing.index'), [
            'sortKey' => 'score_total',
            'sortOrder' => 'desc',
        ]);

        $this->assertEquals(
            $listings->fresh()
            ->pluck('score_total_percentage_for_display')
            ->sort()->reverse()->values(),
            collect($response->json()['data'])->map(function ($listing) {
                return $listing['score_total_percentage_for_display'];
            })
        );
    }
}
