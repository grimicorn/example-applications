<?php


namespace Tests\Browser\Application;

use App\User;
use App\Listing;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\BusinessCategory;

class WatchlistCreateRefreshTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testItSearchesForAllListings()
    {
        $this->browse(function (Browser $browser) {
            $listings = $this->makeListings();
            $listing1 = $listings->get('listing1');
            $listing2 = $listings->get('listing2');
            $listing3 = $listings->get('listing3');

            $browser->loginAs($user = factory(User::class)->create());
            $browser->visit('/dashboard/watchlists');
            $browser->type('name', $watchlistTitle = 'All Businesses');
            $browser->press('CREATE SEARCH');
            $browser->assertPathIs('/dashboard/watchlists');
            $browser->waitForText('VIEW RESULTS');
            $browser->waitForText($watchlistTitle);
            $browser->clickLink($watchlistTitle);
            $browser->assertSee($listing1->title);
            $browser->assertSee($listing2->title);
            $browser->assertSee($listing3->title);
            $browser->logout();
        });
    }

    public function testItSearchesForListingsWithAskingPriceMinMax()
    {
        $this->browse(function (Browser $browser) {
            $listings = $this->makeListings();
            $listing1 = $listings->get('listing1');
            $listing2 = $listings->get('listing2');
            $listing3 = $listings->get('listing3');

            $browser->loginAs($user = factory(User::class)->create());
            $browser->visit('/dashboard/watchlists');
            $browser->type('asking_price_min', '50,000');
            $browser->type('asking_price_max', '550,000');
            $browser->type('name', 'Listings between 50,000 and 550,000');
            $browser->press('CREATE SEARCH');
            $browser->assertPathIs('/dashboard/watchlists');
            $browser->waitForText('Listings between 50,000 and 550,000');
            $browser->clickLink('Listings between 50,000 and 550,000');
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
