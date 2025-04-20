<?php

namespace Tests\Browser\Application;

use App\User;
use App\Listing;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ListingCreateEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_allows_the_user_to_fill_out_listing_information_and_create_the_listing()
    {
        $this->browse(function (Browser $browser) {
            // Make a listing for its attributes.
            $listing = factory(Listing::class)->make($attributes = ['title' => 'This is my new listing']);

            // Visit the page.
            $browser->loginAs(factory(User::class)->create())->visit(route('listing.create'));

            // Fill out the form.
            $this->fillOutForm($listing, $browser);

            // Submit the form and make sure it submits successfully
            $browser->click('button.btn-model-submit')
                           ->waitFor('.alert-success')
                           ->assertSeeIn('.alert-success', 'Your listing has been successfully created!');

            // Check if the listing exists. Since the feature test will cover the full submission just check that it exists.
            $this->assertDatabaseHas('listings', $attributes);
        });
    }

    /**
     * @test
     */
    public function it_allows_the_user_to_fill_out_listing_information_and_update_the_listing()
    {
        $this->browse(function (Browser $browser) {
            // Create a user that will "edit" the listing.
            $user = factory(User::class)->create();

            // Create a listing to "edit".
            $originalListing = factory(Listing::class)->create([
                'title' => 'This is my new listing',
                'user_id' => $user->id,
            ]);

            // Make a new set of listing attributes.
            $newListing = factory(Listing::class)->make($newAttributes = ['title' => 'This is my updated listing']);

            // Visit the page.
            $browser->loginAs($user)->visit(route('listing.details.edit', ['id' => $originalListing->id]));

            // Fill out the form.
            $this->fillOutForm($newListing, $browser);

            // Submit the form and make sure it submits successfully
            $browser->click('button.btn-model-submit')
                           ->waitFor('.alert-success')
                           ->assertSeeIn('.alert-success', 'Your listing has been successfully updated!');

            // Check if the listing exists. Since the feature test will cover the full submission just check that it exists.
            $this->assertDatabaseHas('listings', $newAttributes);
        });
    }

    /**
     * Fills out the listing form.
     *
     * @param  Listing $listing
     * @param  Browser $browser
     */
    protected function fillOutForm(Listing $listing, Browser $browser)
    {
        // Fill out the basic fields.
        $browser->type('title', $listing->title);
        $browser->type('business_name', $listing->business_name);
        $browser->type('asking_price', $listing->asking_price);
        // $browser->click('.fe-input-name_visible-wrap' . ($listing->name_visible ? ' .is-option-on' : ' .is-option-off'));
        $browser->select('business_category_id', $listing->business_category_id);
        $browser->select('business_sub_category_id', $listing->business_sub_category_id);
        $browser->type('year_established', $listing->year_established);
        $browser->type('number_of_employees', $listing->number_of_employees);
        $browser->type('revenue', $listing->revenue);
        $browser->type('discretionary_cash_flow', $listing->discretionary_cash_flow);
        $browser->type('address_1', $listing->address_1);
        $browser->type('address_2', $listing->address_2);
        $browser->type('city', $listing->city);
        $browser->select('state', $listing->state);
        $browser->type('zip_code', $listing->zip_code);
        // $browser->click('.fe-input-address_visible-wrap' . ($listing->address_visible ? ' .is-option-on' : ' .is-option-off'));
        $browser->type('pre_tax_earnings', $listing->pre_tax_earnings);
        $browser->type('ebitda', $listing->ebitda);
        $browser->select('real_estate_included', $listing->real_estate_included);
        $browser->type('real_estate_estimated', $listing->real_estate_estimated);
        $browser->select('fixtures_equipment_included', $listing->fixtures_equipment_included);
        $browser->type('fixtures_equipment_estimated', $listing->fixtures_equipment_estimated);
        $browser->select('inventory_included', $listing->inventory_included);
        $browser->type('inventory_estimated', $listing->inventory_estimated);
        $browser->click($listing->financing_available ? 'label[for="financing_available_1"]' : 'label[for="financing_available_2"]');
        $browser->click($listing->support_training ? 'label[for="support_training_1"]' : 'label[for="support_training_2"]');
        // desired_sale_date(date)
        $browser->click($listing->seller_non_compete ? 'label[for="seller_non_compete_1"]' : 'label[for="seller_non_compete_2"]');
        $browser->type('summary_business_description', $listing->summary_business_description);
        $browser->type('business_description', $listing->business_description);
        $browser->type('location_description', $listing->location_description);
        $browser->type('products_services', $listing->products_services);
        $browser->type('market_overview', $listing->market_overview);
        $browser->type('competitive_position', $listing->competitive_position);
        $browser->type('business_performance_outlook', $listing->business_performance_outlook);
        $browser->type('financing_available_description', $listing->financing_available_description);
        $browser->type('support_training_description', $listing->support_training_description);
        $browser->type('reason_for_selling', $listing->reason_for_selling);
    }
}
