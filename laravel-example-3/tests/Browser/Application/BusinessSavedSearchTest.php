<?php

namespace Tests\Browser\Application;

use App\User;
use App\Listing;
use App\SavedSearch;
use Tests\DuskTestCase;
use App\BusinessCategory;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BusinessSavedSearchTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testItSearchesForAllBusinesses()
    {
        $this->browse(function (Browser $browser) {
            $listings = $this->makeListings();
            $user = factory(User::class)->create();
            $listing1 = $listings->get('listing1');
            $listing2 = $listings->get('listing2');
            $listing3 = $listings->get('listing3');
            $watchlist = factory(SavedSearch::class)->states('empty')->create([
                'name' => 'All Businesses',
                'user_id' => $user->id,
            ]);

            $browser->loginAs($user);

            $browser->visit('/businesses/search');
            $browser->waitForText('Saved Searches:');
            $browser->select('saved_search_id_select', $watchlist->id);
            $browser->press('#listing-search-form button[type="submit"]');
            $browser->assertPathIs('/businesses/search/results');
            $browser->assertSee($listing1->title);
            $browser->assertSee($listing2->title);
            $browser->assertSee($listing3->title);
            $browser->logout();
        });
    }

    public function testItSearchesForBusinessesWithAskingPriceMinMax()
    {
        $this->browse(function (Browser $browser) {
            $listings = $this->makeListings();
            $user = factory(User::class)->create();
            $listing1 = $listings->get('listing1');
            $listing2 = $listings->get('listing2');
            $listing3 = $listings->get('listing3');
            $watchlist = factory(SavedSearch::class)->states('empty')->create([
                'name' => 'All Businesses',
                'user_id' => $user->id,
                'asking_price_min' => 50000,
                'asking_price_max' => 550000,
            ]);

            $browser->loginAs($user);

            $browser->visit('/businesses/search');
            $browser->waitForText('Saved Searches:');
            $browser->select('saved_search_id_select', $watchlist->id);
            $browser->press('#listing-search-form button[type="submit"]');
            $browser->assertPathIs('/businesses/search/results');
            $browser->assertSee($listing1->title);
            $browser->assertSee($listing2->title);
            $browser->assertDontSee($listing3->title);
            $browser->logout();
        });
    }

    protected function makeListings()
    {
        $listing1 = factory(Listing::class)->states('published')->create([
            'title' => 'Listing 1',
            'asking_price' => 100000,
            'business_category_id' => $this->getBusinesCategoryId(
                'Business & Personal Services'
            ),
            'business_sub_category_id' => $this->getBusinesCategoryId(
                'Car Wash'
            ),
        ]);

        $listing2 = factory(Listing::class)->states('published')->create([
            'title' => 'Listing 2',
            'asking_price' => 500000,
            'business_category_id' => $this->getBusinesCategoryId(
                'Finance & Insurance'
            ),
            'business_sub_category_id' => $this->getBusinesCategoryId(
                'Depository Institutions'
            ),
        ]);

        $listing3 = factory(Listing::class)->states('published')->create([
            'title' => 'Listing 3',
            'asking_price' => 1000000,
            'business_category_id' => $this->getBusinesCategoryId(
                'Wholesale & Distribution'
            ),
            'business_sub_category_id' => $this->getBusinesCategoryId(
                'Nondurable Goods'
            ),
        ]);

        return collect([
            'listing1' => $listing1,
            'listing2' => $listing2,
            'listing3' => $listing3,
        ]);
    }

    protected function getBusinesCategoryId($label)
    {
        return optional(BusinessCategory::where('label', $label)->first())->id;
    }
}
