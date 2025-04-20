<?php

namespace Tests\Unit\Support\Listing;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Support\Listing\Search;
use App\ListingCompletionScoreTotal;
use Tests\Support\TestApplicationData;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class SearchTest extends TestCase
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
    * @group failing
    */
    public function it_searches_for_listings_from_a_specific_user()
    {
        // Setup some non-matching listings
        factory('App\Listing', 5)->create();

        $user = factory('App\User')->create();

        // Setup some matching listings
        $matching = factory('App\Listing', 5)->create([
            'user_id' => $user->id,
        ]);

        // Perform the search
        $results = (new Search([
            'user' => $user->id,
        ]))->execute();

        // Check the results.
        $this->assertEquals(
            $matching->pluck('id')->sort()->values()->toArray(),
            $results->pluck('id')->sort()->values()->toArray()
        );
    }

    /**
    * @test
    * @group failing
    */
    public function it_searches_for_listings_from_a_specific_user_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;
        // Setup some non-matching listings
        factory('App\Listing', 5)->create(['title' => $keyword = 'Title']);

        $user = factory('App\User')->create();

        // Setup some matching listings
        $matching = factory('App\Listing', 5)->create([
            'user_id' => $user->id,
            'title' => $keyword,
        ]);

        // Perform the search
        $results = (new Search([
            'user' => $user->id,
            'keyword' => $keyword,
        ]))->execute();

        // Check the results.
        $this->assertEquals(
            $matching->pluck('id')->sort()->values()->toArray(),
            $results->pluck('id')->sort()->values()->toArray()
        );
    }

    /**
    * @test
    */
    public function it_searches_for_listings_by_keyword()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        // Setup some non-matching listings
        factory('App\Listing', 5)->create();

        // Setup some matching listings
        $search = 'searchterm';
        $matched = [];
        $matched[] = factory('App\Listing')->create(['title' => $search]);

        // Setup some more non-matching listings
        factory('App\Listing', 5)->create();

        // Perform the search
        $results = (new Search([
            'keyword' => $search,
            'sort_order' => 'title_a_to_z',
        ]))->execute();

        // Make sure we have what we need.
        $matched = collect($matched);
        $this->assertCount($matched->count(), $results);
        $this->assertEquals(
            $matched->pluck('id')->values()->sort(),
            $results->pluck('id')->values()->sort()
        );
    }

    /**
    * @test
    */
    public function it_searches_for_listings_by_state()
    {
        $testData = new TestApplicationData();

        // Setup some non-matching listings
        factory('App\Listing', 5)->create(['state' => null]);

        // Setup some matching listings
        $state = $testData->getTestStateAbbreviation();
        $matched = factory('App\Listing', 4)->create(['state' => $state]);

        // Setup some more non-matching listings
        factory('App\Listing', 5)->create(['state' => null]);

        // Perform the search
        $results = (new Search([
            'state' => $state,
        ]))->execute();

        // Make sure we have what we need.
        $matched = collect($matched);
        $this->assertCount($matched->count(), $results);
        $this->assertEquals(
            $matched->pluck('id')->values()->sort(),
            $results->pluck('id')->values()->sort()
        );
    }

    /**
     * @test
     */
    public function it_searches_for_listings_by_state_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $testData = new TestApplicationData();

        // Setup some non-matching listings
        factory('App\Listing', 5)->create(['state' => null, 'title' => $keyword = 'Test']);

        // Setup some matching listings
        $state = $testData->getTestStateAbbreviation();
        $matched = factory('App\Listing', 4)->create(['state' => $state, 'title' => $keyword]);

        // Setup some more non-matching listings
        factory('App\Listing', 5)->create(['state' => null, 'title' => $keyword]);

        // Perform the search
        $results = (new Search([
            'state' => $state,
            'keyword' => $keyword,
        ]))->execute();

        // Make sure we have what we need.
        $matched = collect($matched);
        $this->assertCount($matched->count(), $results);
        $this->assertEquals(
            $matched->pluck('id')->values()->sort(),
            $results->pluck('id')->values()->sort()
        );
    }

    /**
    * @test
    */
    public function it_searches_for_listings_by_updated_at()
    {
        // Setup some non-matching listings
        factory('App\Listing', 2)->create(['updated_at' => Carbon::now()->addMonth()]);

        // Create example listings for each type of date.
        $dates = [
            'last_month' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'last_three_months' => $this->faker->dateTimeBetween('-3 months', '-1 month'),
            'last_year'  => $this->faker->dateTimeBetween('-1 year', '-3 months'),
        ];

        $matched = [];
        foreach ($dates as $key => $date) {
            $matched[ $key ] = factory('App\Listing')->create(['updated_at' => $date]);
        }
        $matched = collect($matched);

        // Setup some non-matching listings
        factory('App\Listing', 2)->create(['updated_at' => Carbon::now()->addMonth()]);

        // Perform the search for last month.
        $last_month_results = (new Search([
            'listing_updated' => ['last_month']
        ]))->execute();
        $this->assertCount(1, $last_month_results);
        $this->assertEquals(
            collect([
                $matched->get('last_month')->id,
            ])->sort()->values()->toArray(),
            $last_month_results->pluck('id')->sort()->values()->toArray()
        );

        // Perform the search for last three months.
        $last_three_months_results = (new Search([
            'listing_updated' => ['last_three_months']
        ]))->execute();
        $this->assertCount(2, $last_three_months_results);
        $this->assertEquals(
            collect([
                $matched->get('last_month')->id,
                $matched->get('last_three_months')->id,
            ])->sort()->values()->toArray(),
            $last_three_months_results->pluck('id')->sort()->values()->toArray()
        );

        // Perform the search for last year.
        $last_year_results = (new Search([
            'listing_updated' => ['last_year']
        ]))->execute();
        $this->assertCount(3, $last_year_results);
        $this->assertEquals(
            collect([
                $matched->get('last_month')->id,
                $matched->get('last_three_months')->id,
                $matched->get('last_year')->id,
            ])->sort()->values()->toArray(),
            $last_year_results->pluck('id')->sort()->values()->toArray()
        );
    }

    /**
    * @test
    */
    public function it_searches_for_listings_by_updated_at_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        // Setup some non-matching listings
        factory('App\Listing', 2)->create(['updated_at' => Carbon::now()->addMonth(), 'title' => $keyword = 'Title']);

        // Create example listings for each type of date.
        $dates = [
            'last_month' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'last_three_months' => $this->faker->dateTimeBetween('-3 months', '-1 month'),
            'last_year'  => $this->faker->dateTimeBetween('-1 year', '-3 months'),
        ];

        $matched = [];
        foreach ($dates as $key => $date) {
            $matched[ $key ] = factory('App\Listing')->create(['updated_at' => $date, 'title' => $keyword]);
        }
        $matched = collect($matched);

        // Setup some non-matching listings
        factory('App\Listing', 2)->create(['updated_at' => Carbon::now()->addMonth(), 'title' => $keyword]);

        // Perform the search for last month.
        $last_month_results = (new Search([
            'listing_updated' => ['last_month'],
            'keyword' => $keyword,
        ]))->execute();
        $this->assertCount(1, $last_month_results);
        $this->assertEquals(
            collect([
                $matched->get('last_month')->id,
            ])->sort()->values()->toArray(),
            $last_month_results->pluck('id')->sort()->values()->toArray()
        );

        // Perform the search for last three months.
        $last_three_months_results = (new Search([
            'listing_updated' => ['last_three_months'],
            'keyword' => $keyword,
        ]))->execute();
        $this->assertCount(2, $last_three_months_results);
        $this->assertEquals(
            collect([
                $matched->get('last_month')->id,
                $matched->get('last_three_months')->id,
            ])->sort()->values()->toArray(),
            $last_three_months_results->pluck('id')->sort()->values()->toArray()
        );

        // Perform the search for last year.
        $last_year_results = (new Search([
            'listing_updated' => ['last_year'],
            'keyword' => $keyword,
        ]))->execute();
        $this->assertCount(3, $last_year_results);
        $this->assertEquals(
            collect([
                $matched->get('last_month')->id,
                $matched->get('last_three_months')->id,
                $matched->get('last_year')->id,
            ])->sort()->values()->toArray(),
            $last_year_results->pluck('id')->sort()->values()->toArray()
        );
    }

    /**
    * @test
    */
    public function it_searches_for_listings_by_business_categories()
    {
        $testData = new TestApplicationData();

        // Get business categories to test
        $business_categories = $this->faker->randomElements(
            $testData->getTestBusinesCategories(),
            4
        );

        // Setup some non-matching listings
        factory('App\Listing', 2)->create([
            'business_category_id' => null,
            'business_sub_category_id' => null,
        ]);

        // Create the matching listings.
        $matched = [];
        $matched[] = factory('App\Listing')->create([
            'business_category_id' => $business_categories[0],
            'business_sub_category_id' => null,
        ]);
        $matched[] = factory('App\Listing')->create([
            'business_category_id' => null,
            'business_sub_category_id' => $business_categories[1],
        ]);
        $matched[] = factory('App\Listing')->create([
            'business_category_id' => $business_categories[2],
            'business_sub_category_id' => null,
        ]);
        $matched[] = factory('App\Listing')->create([
            'business_category_id' => null,
            'business_sub_category_id' => $business_categories[3],
        ]);
        $matched = collect($matched);

        // Setup some more non-matching listings
        factory('App\Listing', 2)->create([
            'business_category_id' => null,
            'business_sub_category_id' => null,
        ]);

        // Perform the search
        $results = (new Search([
            'business_categories' => $business_categories,
        ]))->execute();

        // Make sure we found what we needed.
        $this->assertCount(count($matched), $results);
        $this->assertEquals($matched->pluck('id'), $results->pluck('id'));
    }

    /**
    * @test
    */
    public function it_searches_for_listings_by_business_categories_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;


        $testData = new TestApplicationData();

        // Get business categories to test
        $business_categories = $this->faker->randomElements(
            $testData->getTestBusinesCategories(),
            4
        );

        // Setup some non-matching listings
        factory('App\Listing', 2)->create([
            'business_category_id' => null,
            'business_sub_category_id' => null,
            'title' => $keyword = 'Title',
        ]);

        // Create listings with the matching categories but not the keyword.
        $matched[] = factory('App\Listing')->create([
            'business_category_id' => $business_categories[0],
            'business_sub_category_id' => null,
            'title' => $this->faker->sentence,
        ]);
        $matched[] = factory('App\Listing')->create([
            'business_category_id' => null,
            'business_sub_category_id' => $business_categories[1],
            'title' => $this->faker->sentence,
        ]);
        $matched[] = factory('App\Listing')->create([
            'business_category_id' => $business_categories[2],
            'business_sub_category_id' => null,
            'title' => $this->faker->sentence,
        ]);
        $matched[] = factory('App\Listing')->create([
            'business_category_id' => null,
            'business_sub_category_id' => $business_categories[3],
            'title' => $this->faker->sentence,
        ]);


        // Create the matching listings.
        $matched = [];
        $matched[] = factory('App\Listing')->create([
            'business_category_id' => $business_categories[0],
            'business_sub_category_id' => null,
            'title' => $keyword,
        ]);
        $matched[] = factory('App\Listing')->create([
            'business_category_id' => null,
            'business_sub_category_id' => $business_categories[1],
            'title' => $keyword,
        ]);
        $matched[] = factory('App\Listing')->create([
            'business_category_id' => $business_categories[2],
            'business_sub_category_id' => null,
            'title' => $keyword,
        ]);
        $matched[] = factory('App\Listing')->create([
            'business_category_id' => null,
            'business_sub_category_id' => $business_categories[3],
            'title' => $keyword,
        ]);

        $matched = collect($matched);

        // Setup some more non-matching listings
        factory('App\Listing', 2)->create([
            'business_category_id' => null,
            'business_sub_category_id' => null,
            'title' => $keyword,
        ]);

        // Perform the search
        $results = (new Search([
            'business_categories' => $business_categories,
            'keyword' => $keyword,
        ]))->execute();

        // Make sure we found what we needed.
        $this->assertCount(count($matched), $results);
        $this->assertEquals($matched->pluck('id'), $results->pluck('id'));
    }

    /**
    * @test
    */
    public function it_searches_for_listings_higher_than_minimum_cash_flow()
    {
        $this->assertMinPriceUnlimitedMax('cash_flow', 'discretionary_cash_flow');

    }

    /**
    * @test
    */
    public function it_searches_for_listings_higher_than_minimum_cash_flow_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $this->assertMinPriceUnlimitedMaxUsingScout('cash_flow', 'discretionary_cash_flow');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_lower_than_maximum_cash_flow()
    {
        $this->assertMaxPriceNoMin('cash_flow', 'discretionary_cash_flow');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_lower_than_maximum_cash_flow_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $this->assertMaxPriceNoMinUsingScout('cash_flow', 'discretionary_cash_flow');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_between_minimum_and_maximum_cash_flow()
    {
        $this->assertMinMaxPrice('cash_flow', 'discretionary_cash_flow');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_between_minimum_and_maximum_cash_flow_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $this->assertMinMaxPriceUsingScout('cash_flow', 'discretionary_cash_flow');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_higher_than_minimum_pre_tax_income()
    {
        $this->assertMinPriceUnlimitedMax('pre_tax_income', 'pre_tax_earnings');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_higher_than_minimum_pre_tax_income_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $this->assertMinPriceUnlimitedMaxUsingScout('pre_tax_income', 'pre_tax_earnings');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_lower_than_maximum_pre_tax_income()
    {
        $this->assertMaxPriceNoMin('pre_tax_income', 'pre_tax_earnings');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_lower_than_maximum_pre_tax_income_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $this->assertMaxPriceNoMinUsingScout('pre_tax_income', 'pre_tax_earnings');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_between_minimum_and_maximum_pre_tax_income()
    {
        $this->assertMinMaxPrice('pre_tax_income', 'pre_tax_earnings');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_between_minimum_and_maximum_pre_tax_income_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $this->assertMinMaxPriceUsingScout('pre_tax_income', 'pre_tax_earnings');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_higher_than_minimum_ebitda()
    {
        $this->assertMinPriceUnlimitedMax('ebitda', 'ebitda');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_higher_than_minimum_ebitda_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $this->assertMinPriceUnlimitedMaxUsingScout('ebitda', 'ebitda');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_lower_than_maximum_ebitda()
    {
        $this->assertMaxPriceNoMin('ebitda', 'ebitda');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_lower_than_maximum_ebitda_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $this->assertMaxPriceNoMinUsingScout('ebitda', 'ebitda');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_between_minimum_and_maximum_ebitda()
    {
        $this->assertMinMaxPrice('ebitda', 'ebitda');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_between_minimum_and_maximum_ebitda_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $this->assertMinMaxPriceUsingScout('ebitda', 'ebitda');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_higher_than_minimum_revenue()
    {
        $this->assertMinPriceUnlimitedMax('revenue', 'revenue');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_higher_than_minimum_revenue_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $this->assertMinPriceUnlimitedMaxUsingScout('revenue', 'revenue');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_lower_than_maximum_revenue()
    {
        $this->assertMaxPriceNoMin('revenue', 'revenue');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_lower_than_maximum_revenue_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $this->assertMaxPriceNoMinUsingScout('revenue', 'revenue');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_between_minimum_and_maximum_revenue()
    {
        $this->assertMinMaxPrice('revenue', 'revenue');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_between_minimum_and_maximum_revenue_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $this->assertMinMaxPriceUsingScout('revenue', 'revenue');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_higher_than_minimum_asking_price()
    {
        $this->assertMinPriceUnlimitedMax('asking_price', 'asking_price');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_higher_than_minimum_asking_price_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $this->assertMinPriceUnlimitedMaxUsingScout('asking_price', 'asking_price');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_lower_than_maximum_asking_price()
    {
        $this->assertMaxPriceNoMin('asking_price', 'asking_price');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_lower_than_maximum_asking_price_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $this->assertMaxPriceNoMinUsingScout('asking_price', 'asking_price');
    }

    /**
    * @test
    */
    public function it_searches_for_listings_between_minimum_and_maximum_asking_price()
    {
        $this->assertMinMaxPrice('asking_price', 'asking_price');
    }
/**
    * @test
    */
    public function it_searches_for_listings_between_minimum_and_maximum_asking_price_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $this->assertMinMaxPriceUsingScout('asking_price', 'asking_price');
    }

    /**
    * @test
    * @group failing
    */
    public function it_sorts_searched_listings_by_title_a_to_z()
    {
        $titles = collect([
            'A Title',
            'B Title',
            'C Title',
            'D Title',
            'E Title',
            'G Title',
        ]);
        $titles->shuffle()->each(function ($title) {
            $listings = factory('App\Listing')->create([
                'title' => $title,
            ]);
        });

        $results = (new Search([
            'sort_order' => 'title_a_to_z',
        ]))->execute();

        $this->assertEquals(
            $titles->values()->toArray(),
            collect($results->items())->pluck('title')->toArray()
        );
    }

    /**
    * @test
    * @group failing
    */
    public function it_sorts_searched_listings_by_title_a_to_z_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;

        $titles = collect([
            'A Title',
            'B Title',
            'C Title',
            'D Title',
            'E Title',
            'G Title',
        ]);
        $keyword = 'Keyword';
        $titles->shuffle()->each(function ($title) use ($keyword) {
            $listings = factory('App\Listing')->create([
                'title' => $title,
                'business_description' => $keyword,
            ]);
        });

        $results = (new Search([
            'sort_order' => 'title_a_to_z',
            'keyword' => $keyword,
        ]))->execute();

        $this->assertEquals(
            $titles->values()->toArray(),
            collect($results->items())->pluck('title')->toArray()
        );
    }


    /**
    * @test
    * @group failing
    */
    public function it_sorts_searched_listings_by_title_z_to_a()
    {
        $titles = collect([
            'G Title',
            'F Title',
            'E Title',
            'D Title',
            'C Title',
            'B Title',
            'A Title',
        ]);
        $titles->shuffle()->each(function ($title) {
            $listings = factory('App\Listing')->create([
                'title' => $title,
            ]);
        });

        $results = (new Search([
            'sort_order' => 'title_z_to_a',
        ]))->execute();

        $this->assertEquals(
            $titles->values()->toArray(),
            collect($results->items())->pluck('title')->toArray()
        );
    }

    /**
    * @test
    * @group failing
    */
    public function it_sorts_searched_listings_by_title_z_to_a_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;


        $titles = collect([
            'G Title',
            'F Title',
            'E Title',
            'D Title',
            'C Title',
            'B Title',
            'A Title',
        ]);
        $keyword = 'Keyword';
        $titles->shuffle()->each(function ($title) use ($keyword) {
            $listings = factory('App\Listing')->create([
                'title' => $title,
                'business_description' => $keyword,
            ]);
        });

        $results = (new Search([
            'sort_order' => 'title_z_to_a',
            'keyword' => $keyword,
        ]))->execute();

        $this->assertEquals(
            $titles->values()->toArray(),
            collect($results->items())->pluck('title')->values()->toArray()
        );
    }

    /**
    * @test
    * @group failing
    */
    public function it_sorts_searched_listings_by_listing_completion_score_high_to_low()
    {
        $totals = collect([72, 66, 55, 42, 12]);
        $totals->shuffle()->each(function ($total) {
            factory('App\Listing')->create([
                'current_score_total' => $total,
            ]);
        });

        $results = (new Search([
            'sort_order' => 'lcs_high_to_low',
        ]))->execute();

        $this->assertEquals(
            $totals->values(),
            collect($results->items())->map(function ($item) {
                return intval($item->current_score_total);
            })
        );
    }

    /**
    * @test
    * @group failing
    */
    public function it_sorts_searched_listings_by_listing_completion_score_high_to_low_using_scout()
    {
        $this->markTestIncomplete('boxed-code/laravel-scout-database has not been updated for scout ^4.0');

        return;


        $totals = collect([72, 66, 55, 42, 12]);
        $keyword = 'Title';
        $totals->shuffle()->each(function ($total) use ($keyword) {
            factory('App\Listing')->create([
                'current_score_total' => $total,
                'title' => $keyword,
            ]);
        });

        $results = (new Search([
            'sort_order' => 'lcs_high_to_low',
            'keyword' => $keyword,
        ]))->execute();

        $this->assertEquals(
            $totals->values(),
            collect($results->items())->map(function ($item) {
                return intval($item->current_score_total);
            })
        );
    }

    /**
    * @test
    * @group failing
    */
    public function it_sorts_searched_listings_by_listing_completion_score_high_to_low_by_default()
    {
        $totals = collect([
            12,
            42,
            55,
            66,
            72,
        ]);
        $listings = factory('App\Listing', $totals->count())->create();
        $shuffledTotals = $totals->shuffle();
        $listings = $listings->map(function ($listing) {
            $listing->listingCompletionScore->save();
            return $listing->fresh();
        });

        $results = (new Search())->execute();
        $this->assertEquals(
            collect($listings)->map(function ($listing) {
                return $listing->listingCompletionScoreTotal->total;
            })->sort()->reverse()->values(),
            collect($results->items())->map(function ($listing) {
                return $listing->listingCompletionScoreTotal->total;
            })
        );
    }

    /**
    * @test
    */
    public function it_filters_results_by_listing()
    {
        factory('App\Listing', 5)->create();
        $listing = factory('App\Listing')->create();
        $results = (new Search([], $listing->id))->execute();

        $this->assertCount(1, $results->items());
        $this->assertEquals($listing->id, collect($results->items())->first()->id);
    }

    /**
     * Gets the min/max listings for a test
     *
     * @param string $listingKey
     * @param integer $min
     * @param integer $max
     * @param  mixed $nonMatchedValue
     * @return array
     */
    protected function getMinMaxListings($listingKey, $min = 1000, $max = 100000, $nonMatchedValue = null, $title = null)
    {
        $title = $title ?: $this->faker->words(3, true);

        // Setup some non-matching listings
        factory('App\Listing', 2)->create([$listingKey => $nonMatchedValue, 'title' => $title]);

        // Setup some matching listings
        $matched = [];
        $count = 4;
        for ($i = 1; $i <= $count; $i++) {
            $matched[] = factory('App\Listing')->create([
                $listingKey => $this->faker->numberBetween($min, $max),
                'title' => $title
            ]);
        }

        // Setup some more non-matching listings
        factory('App\Listing', 2)->create([$listingKey => $nonMatchedValue, 'title' => $title]);

        return $matched;
    }

    /**
     * Asserts against minimum prices without a maximum.
     *
     * @param string $searchKey
     * @param string $listingKey
     * @param boolean $useScout
     * @return void
     */
    protected function assertMinPriceUnlimitedMax($searchKey, $listingKey, $useScout = false)
    {
        $keyword = $useScout ? 'Title' : null;
        $matched = $this->getMinMaxListings($listingKey, $min = 1000, $max = 100000, $nonMatchedValue = null, $title = $keyword);

        // Perform the search
        $results = (new Search(array_filter([
            "{$searchKey}_min" => $min,
            'keyword' => $useScout ? $keyword : '',
        ])))->execute();

        // Make sure we have what we need.
        $matched = collect($matched);
        $this->assertCount($matched->count(), $results);
        $this->assertEquals(
            $matched->pluck('id')->values()->sort(),
            $results->pluck('id')->values()->sort()
        );
    }

    /**
     * Asserts against minimum prices without a maximum using Laravel Scout.
     *
     * @param string $searchKey
     * @param string $listingKey
     * @return void
     */
    protected function assertMinPriceUnlimitedMaxUsingScout($searchKey, $listingKey)
    {
        return $this->assertMinPriceUnlimitedMax($searchKey, $listingKey, $useScout = true);
    }

    /**
     * Asserts against maximium prices without a minimum.
     *
     * @param string $searchKey
     * @param string $listingKey
     * @param boolean $useScout
     * @return void
     */
    protected function assertMaxPriceNoMin($searchKey, $listingKey, $useScout = false)
    {
        $keyword = $useScout ? 'Title' : null;
        $matched = $this->getMinMaxListings($listingKey, 1000, $max = 10000, $nonMatchedValue = 15000, $title = $keyword);

        // Perform the search
        $results = (new Search(array_filter([
            "{$searchKey}_max" => $max,
            'keyword' => $useScout ? $keyword : '',
        ])))->execute();

        // Make sure we have what we need.
        $matched = collect($matched);
        $this->assertCount($matched->count(), $results);
        $this->assertEquals(
            $matched->pluck('id')->values()->sort(),
            $results->pluck('id')->values()->sort()
        );
    }

     /**
     * Asserts against maximium prices without a minimum using Laravel Scout.
     *
     * @param string $searchKey
     * @param string $listingKey
     * @return void
     */
    protected function assertMaxPriceNoMinUsingScout($searchKey, $listingKey)
    {
        return $this->assertMaxPriceNoMin($searchKey, $listingKey, $useScout = true);
    }

    /**
     * Asserts against maximium prices without a minimum.
     *
     * @param string $searchKey
     * @param string $listingKey
     * @param boolean $useScout
     * @return void
     */
    protected function assertMinMaxPrice($searchKey, $listingKey, $useScout = false)
    {
        $keyword = $useScout ? 'Title' : null;
        $matched = $this->getMinMaxListings($listingKey, $min = 1000, $max = 10000, $nonMatchedValue = 15000, $title = $keyword);

        // Perform the search
        $results = (new Search(array_filter([
            "{$searchKey}_min" => $min,
            "{$searchKey}_max" => $max,
            'keyword' => $keyword,
        ])))->execute();

        // Make sure we have what we need.
        $matched = collect($matched);
        $this->assertCount($matched->count(), $results);
        $this->assertEquals(
            $matched->pluck('id')->values()->sort(),
            $results->pluck('id')->values()->sort()
        );
    }

    /**
     * Asserts against maximium prices without a minimum for Laravel Scout.
     *
     * @param string $searchKey
     * @param string $listingKey
     * @return void
     */
    protected function assertMinMaxPriceUsingScout($searchKey, $listingKey)
    {
        return $this->assertMinMaxPrice($searchKey, $listingKey, $useScout = true);
    }
}
