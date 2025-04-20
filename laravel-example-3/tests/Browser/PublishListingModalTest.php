<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

// phpcs:ignoreFile
class PublishListingModalTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_publish_a_lifetime_listing_from_listing_create_page()
    {
        $this->browse(function (Browser $browser) {
            $user = factory(App\User::class)->create();

            $browser->visit('/dashboard/listing/create');
            $browser->type('title', 'Listing title');
            $browser->type('business_name', 'Business Name');
            $browser->type('asking_price', '100,000');
            $browser->type('summary_business_description_textarea', 'Summary tagline');
            $browser->type('business_description_textarea', 'Business Overview');
            $browser->select('', 'business_sub_category_id_select');
            $browser->select('2', 'business_category_id_select');
            $browser->select('44', 'business_sub_category_id_select');
            $browser->type('city', 'Saint Louis');
            $browser->select('MO', 'state_select');
            $browser->type('zip_code', '63103');
            $browser->press('Post Listing');
            $browser->assertPathIs('/dashboard/listing/101531911/details/edit');
            $browser->waitForText('Lifetime Listing');
            $browser->select('', 'month_select');
            $browser->type('name', $user->name);
            $browser->type('address', '111 Test Rd.');
            $browser->type('address_line_2', 'Suite 101');
            $browser->type('city', 'Saint Louis');
            $browser->select('MO', 'state_select');
            $browser->type('zip', '63103');
            $browser->type('number', '4242424242424242');
            $browser->select('01', 'month_select');
            $browser->select('2022', 'year_select');
            $browser->type('cvc', '111');
            $browser->press('Save');
            $browser->waitForText('Congraulations! Your listing has been posted.');
        });

    }
}
