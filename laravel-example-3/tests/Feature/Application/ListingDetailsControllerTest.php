<?php

namespace Tests\Feature\Application;

use App\Listing;
use Tests\TestCase;
use Tests\Support\HasTestFiles;
use App\Support\Listing\Documents;
use App\Support\ExchangeSpaceStatusType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

// @codingStandardsIgnoreStart
class ListingDetailsControllerTest extends TestCase
{
    use RefreshDatabase, HasTestFiles;

    /**
     * @test
     */
    public function it_only_allows_the_owner_of_a_listing_to_edit_a_listing()
    {
        // Sign in a user that will attempt to "edit" the listing.
        $user1 = $this->signInWithEvents();

        // Create a listing and set it to another user than the current user.
        $user2 = factory('App\User')->create();
        $listing = factory('App\Listing')->create(['user_id' => $user2->id]);

        // Test all restricted routes.
        $this->get(route('listing.details.edit', ['id' => $listing->id]))->assertStatus(403);
        $this->patch(route('listing.details.update', ['id' => $listing->id]))->assertStatus(403);
        $this->patch(route('listing.details.update', ['id' => $listing->id]))->assertStatus(403);
        $this->delete(route('listing.destroy', ['id' => $listing->id]))->assertStatus(403);

        // Sign the second user in and attempt to "edit" the listing.
        $this->signInWithEvents($user2);
        $this->get(route('listing.details.edit', ['id' => $listing->id]))->assertStatus(200);
        $this->patch(route('listing.details.update', ['id' => $listing->id]))->assertStatus(302);
        $this->patch(route('listing.details.update', ['id' => $listing->id]))->assertStatus(302);
        $this->delete(route('listing.destroy', ['id' => $listing->id]))->assertStatus(302);
    }

    /**
     * @test
     */
    public function it_creates_a_listing()
    {
        // Setup
        $user = $this->signInWithEvents();
        $listing = factory('App\Listing')->make([
            'display_listed_by' => false,
            'name_visible' => true,
        ]);
        $request = $this->getListingRequest($listing);

        // Execute
        $response = $this->post(route('listing.details.store'), $request);

        // Assert the response is ok.
        $response->assertRedirect(route('listing.details.edit', Listing::latest()->first()->id))
                        ->assertSessionHas('status')
                         ->assertSessionHas('success', true);

        // Assert the listing was created.
        $fieldKeys = $this->getPossibleListingKeys();

        $this->assertDatabaseHas('listings', $listing->get()->only($fieldKeys)->toArray());
    }

    /**
     * @test
     */
    public function it_updates_a_listing()
    {
        // Setup
        $user = $this->signInWithEvents();
        $listing = factory('App\Listing')->create(['user_id' => $user->id]);
        $newListingData = factory('App\Listing')->make([
            'user_id' => $user->id,
            'links' => null,
            'summary_business_description' => 'Summary Business Description',
            'summary_business_description' => 'Summary Business Description',
            'products_services' => 'Products Services',
            'market_overview' => 'Market Overview',
            'competitive_position' => 'Competitive Position',
            'business_performance_outlook' => 'Business Performance Outlook',
            'reason_for_selling' => 'Reason For Selling',
        ]);
        $request = $this->getListingRequest($newListingData);

        // Execute
        $response = $this->patch(route('listing.details.update', $listing->id), $request);

        // Assert the response is ok.
        $response->assertStatus(302)
                        ->assertSessionHas('status')
                         ->assertSessionHas('success', true);

        // Assert the listing was created.
        $this->assertDatabaseHas('listings', $this->setRequestAttributesForAssertion($request));
    }

    /**
     * @test
     */
    public function it_deletes_a_listing()
    {
        // Create a listing for the "signed in" user
        $listing = factory('App\Listing')->create(['user_id' => $this->signInWithEvents()->id]);

        // Add an inquiry so we can check removing it later.
        $inquiry = factory('App\ExchangeSpace')->states('inquiry')->create(['listing_id' => $listing->id]);

        // Add an exchange space so we can check removing it later.
        $space = factory('App\ExchangeSpace')->create(['listing_id' => $listing->id]);

        // Delete the listing
        $response = $this->delete(route('listing.destroy', ['id' => $listing->id]));

        // Make sure we redirected back to listing.index
        $response->assertRedirect(route('listing.index'))
                         ->assertSessionHas('status')
                         ->assertSessionHas('success', true);

        // Make sure the listing was truly deleted.
        $this->assertSoftDeleted('listings', ['id' => $listing->id]);

        // Make sure the space was cascade deleted
        $this->assertTrue($space->fresh()->trashed());

        // Make sure the business inquiry was rejected
        $this->assertTrue($inquiry->fresh()->trashed());
        $this->assertEquals(ExchangeSpaceStatusType::REJECTED, $inquiry->fresh()->status);
    }

    /**
     * @test
     */
    public function it_stores_the_exit_survey_when_deleting_a_listing()
    {
        // Create a listing for the "signed in" user
        $listing = factory('App\Listing')->create(['user_id' => $this->signInWithEvents()->id]);

        // Get the exit survey attributes
        $survey = factory('App\ListingExitSurvey')->make(['listing_id' => $listing->id]);

        // Make sure the exit survey does not currently exist
        $this->assertNull($listing->fresh()->exitSurvey);

        // Make a few random exchange spaces
        factory('App\ExchangeSpace', 5)->states('not-inquiry-or-completed')->create(['listing_id' => $listing->id]);

        // Make a completed exchange space for the listing
        factory('App\ExchangeSpace')->states('completed')->create(['listing_id' => $listing->id]);

        // Delete the listing
        $this->delete(route('listing.destroy', ['id' => $listing->id]), $survey->toArray());

        // Make sure the exit survey contains the data
        $savedSurvey = $listing->fresh()->exitSurvey;
        $this->assertNotNull($savedSurvey);
        $this->assertEquals($survey->listing_id, $savedSurvey->listing_id);
        $this->assertEquals($survey->final_sale_price, $savedSurvey->final_sale_price);
        $this->assertEquals($survey->overall_experience_rating, $savedSurvey->overall_experience_rating);
        $this->assertEquals($survey->overall_experience_feedback, $savedSurvey->overall_experience_feedback);
        $this->assertEquals($survey->products_services, $savedSurvey->products_services);
        $this->assertEquals($survey->participant_message, $savedSurvey->participant_message);
        $this->assertEquals($survey->participant_message, $savedSurvey->participant_message);
        $this->assertTrue($savedSurvey->sale_completed);
    }

    /**
     * @test
     */
    public function it_uploads_listing_photos()
    {
        $this->withoutExceptionHandling();
        // Sign in a user that will edit the listing.
        $user = $this->signInWithEvents();

        // Start the request with some dummy listing data.
        $request = $this->getListingRequest(factory('App\Listing')->states('required-only')->make());

        // Add the photos to the request
        $request['photos'] = [ 'new' => $this->getTestFiles($photoCount = 8) ];

        // Update the listing
        $response = $this->post(route('listing.details.store'), $request);

        // Assert the response is ok.
        $listing = Listing::latest()->first();

        $response->assertRedirect(route('listing.details.edit', $listing->id))
                        ->assertSessionHas('status')
                         ->assertSessionHas('success', true);

        // Verify that the listing has the correct photos associated with it.
        $this->assertCount($photoCount, $listing->photos()->toArray());
        $this->assertNotEmpty($listing->photos()->toArray());
    }

    /**
     * @test
     */
    public function it_validates_listing_photos_type()
    {
        // Sign in a user that will edit the listing.
        $user = $this->signInWithEvents();

        // Start the request with some dummy listing data.
        $request = $this->getListingRequest(factory('App\Listing')->make());

        // Add the photos to the request (PDF files are not allowed as photos)
        $request['photos'] = [ 'new' => $this->getTestFiles(1, 'pdf') ];

        // Update the listing
        $response = $this->post(route('listing.details.store'), $request);

        // Make sure the response has the first photo error.
        $response->assertSessionHasErrors('photos.new.0');
    }

    /**
     * @test
     * @group failing
     */
    public function it_validates_listing_photos_limit()
    {
        // Sign in a user that will edit the listing.
        $user = $this->signInWithEvents();

        // Start the request with some dummy listing data.
        $request = $this->getListingRequest(factory('App\Listing')->make());

        // Add the photos to the request (Since the limit is 8 we can use 9 to make sure we are good)
        $request['photos'] = [ 'new' => $this->getTestFiles(9, 'pdf') ];

        // Update the listing
        $response = $this->post(route('listing.details.store'), $request);

        // Make sure the response has the first photo error.
        $response->assertSessionHasErrors('photos');
    }

    /**
     * @test
     */
    public function it_orders_all_current_listing_photos()
    {
        // Sign in a user that will edit the listing.
        $user = $this->signInWithEvents();

        // Create a new listing we can edit
        $listing = factory('App\Listing')->create([
            'user_id' => $user->id,
            'name_visible' => true,
        ]);

        // Start the request with some dummy listing data.
        $request = $this->getListingRequest($listing);

        // Have the user add some files to their listing.
        $request['photos'] = [ 'new' => $this->getTestFiles($photoCount = 8) ];
        $this->patch(route('listing.details.update', ['id' => $listing->id]), $request);
        unset($request['photos']['new']);
        $listing = $listing->fresh();
        $originalOrder = $listing->photos()->pluck('id')->toArray();

        // Update the listing to delete the previously added photos.
        $sortOrder = $originalOrder;
        shuffle($sortOrder);
        $deletedId = $listing->photos()->first()->id;
        $request['photos']['order'] = $sortOrder;
        $response = $this->patch(route('listing.details.update', ['id' => $listing->id]), $request);

        // Assert the response is ok.
        $response->assertSessionHas('success', true);

        // Make sure we have the latest data.
        $listing = $listing->fresh();

        // Verify that the listing has the correct photos associated with it.
        $this->assertEquals($sortOrder, $listing->photos()->pluck('id')->toArray());
        $this->assertNotEquals($originalOrder, $listing->photos()->pluck('id')->toArray());
    }

    /**
     * @test
     */
    public function it_orders_all_current_listing_and_new_photos()
    {
        $this->withoutExceptionHandling();
        // Sign in a user that will edit the listing.
        $user = $this->signInWithEvents();

        // Create a new listing we can edit
        $listing = factory('App\Listing')->create([
            'user_id' => $user->id,
            'name_visible' => true,
        ]);

        // Start the request with some dummy listing data.
        $request = $this->getListingRequest($listing);

        // Have the user add some files to their listing.
        $request['photos'] = ['new' => $new = $this->getTestFiles($photoCount = 8)];
        $request['photos']['order-file-names'] = collect($new)
        ->values()->map(function ($item, $key) {
            return "new-{$key}";
        })->toArray();
        $request['photos']['order-file-names'] = collect($new)
        ->values()->keyBy(function ($item, $key) {
            return "new-{$key}";
        })->map->getClientOriginalName()->toArray();
        $this->patch(route('listing.details.update', ['id' => $listing->id]), $request);
        unset($request['photos']['new']);

        // Update the listing to delete the previously added photos.
        $listing = $listing->fresh();
        $originalOrder = $listing->photos()->pluck('id')->toArray();
        $deletedIds = $listing->photos()->take(3)->pluck('id')->toArray();
        $sortOrder = collect($originalOrder)->filter(function ($id) use ($deletedIds) {
            return !in_array($id, $deletedIds);
        })->shuffle()->toArray();
        $request['photos']['order'] = $sortOrder;
        $request['photos']['deleted'] = $deletedIds;
        $response = $this->patch(route('listing.details.update', ['id' => $listing->id]), $request);

        // Verify that the listing has the correct photos associated with it.
        $listing = $listing->fresh();
        $this->assertEquals($sortOrder, $listing->photos()->pluck('id')->toArray());
        $this->assertNotEquals($originalOrder, $listing->photos()->pluck('id')->toArray());

        // Add some new photos
        $listing = $listing->fresh();
        $originalOrder = $listing->photos()->pluck('id')->toArray();
        $request['photos'] = ['new' => $new = $this->getTestFiles($photoCount = 3)];
        $sortOrder = collect($new)->values()->map(function ($item, $key) {
            return "new-{$key}";
        })->merge($originalOrder)->shuffle()->values()->toArray();
        $request['photos']['order-file-names'] = collect($new)
        ->values()->keyBy(function ($item, $key) {
            return "new-{$key}";
        })->map->getClientOriginalName()->toArray();
        $request['photos']['order'] = $sortOrder;
        $response = $this->patch(route('listing.details.update', ['id' => $listing->id]), $request);

        // Verify that the listing has the correct photos associated with it.
        $listing = $listing->fresh();
        $sortOrder = $this->replaceNewSortIds($sortOrder, $request, $listing, 'photos');
        $this->assertEquals($sortOrder, $listing->photos()->pluck('id')->toArray());
        $this->assertNotEquals($originalOrder, $listing->photos()->pluck('id')->toArray());
    }

    /**
     * @test
     */
    public function it_deletes_listing_photos()
    {
        // Sign in a user that will edit the listing.
        $user = $this->signInWithEvents();

        // Create a new listing we can edit
        $listing = factory('App\Listing')->create(['user_id' => $user->id]);

        // Start the request with some dummy listing data.
        $request = $this->getListingRequest($listing);

        // Have the user add some files to their listing.
        $request['photos'] = [ 'new' => $this->getTestFiles($photoCount = 8) ];
        $this->patch(route('listing.details.update', ['id' => $listing->id]), $request);
        unset($request['photos']['new']);
        $listing = $listing->fresh();

        // Update the listing to delete the previously added photos.
        $deletedPhoto = $listing->photos()->first();
        $deletedId = $deletedPhoto->id;
        $deletedPath = $deletedPhoto->getPath();
        $request['photos']['deleted'] = [$deletedId];

        // Make sure the "deleted" file actually exists.
        $this->assertFileExists($deletedPath);

        // Delete the file
        $response = $this->patch(route('listing.details.update', ['id' => $listing->id]), $request);

        // Assert the response is ok.
        $response->assertSessionHas('success', true);

        // Make sure we have the latest data.
        $listing = $listing->fresh();

        // Verify that the listing has the correct photos associated with it.
        $this->assertCount($photoCount - 1, $listing->photos()->toArray());
        $this->assertNotContains($deletedId, $listing->photos()->pluck('id')->toArray());
        // $this->assertFileNotExists($deletedPath);
    }

    /**
     * @test
     */
    public function it_uploads_listing_files()
    {
        // Sign in a user that will edit the listing.
        $user = $this->signInWithEvents();

        // Start the request with some dummy listing data.
        $request = $this->getListingRequest(factory('App\Listing')->states('required-only')->make());

        // Add the files to the request
        $request['files'] = [ 'new' => $this->getTestFiles($fileCount = 8) ];

        // Update the listing
        $response = $this->post(route('listing.details.store'), $request);

        // Assert the response is ok.
        $listing = Listing::latest()->first();

        // Verify that the listing has the correct files associated with it.
        $this->assertCount($fileCount, $listing->files()->toArray());
        $this->assertNotEmpty($listing->files()->toArray());
    }

    /**
     * @test
     */
    public function it_validates_listing_files_type()
    {
        // Sign in a user that will edit the listing.
        $user = $this->signInWithEvents();

        // Start the request with some dummy listing data.
        $request = $this->getListingRequest(factory('App\Listing')->make());

        // Add the files to the request (PDF files are not allowed as files)
        $request['files'] = [ 'new' => $this->getTestFiles(1, 'zip') ];

        // Update the listing
        $response = $this->post(route('listing.details.store'), $request);

        // Make sure the response has the first file error.
        $response->assertSessionHasErrors('files.new.0');
    }

    /**
     * @test
     */
    public function it_deletes_listing_files()
    {
        // Sign in a user that will edit the listing.
        $user = $this->signInWithEvents();

        // Create a new listing we can edit
        $listing = factory('App\Listing')->create(['user_id' => $user->id]);

        // Start the request with some dummy listing data.
        $request = $this->getListingRequest($listing);

        // Have the user add some files to their listing.
        $request['files'] = [ 'new' => $this->getTestFiles($fileCount = 8) ];
        $this->patch(route('listing.details.update', ['id' => $listing->id]), $request);
        unset($request['files']['new']);
        $listing = $listing->fresh();

        // Update the listing to delete the previously added files.
        $deletedId = $listing->files()->first()->id;
        $request['files']['deleted'] = [$deletedId];
        $response = $this->patch(route('listing.details.update', ['id' => $listing->id]), $request);

        // Assert the response is ok.
        $response->assertSessionHas('success', true);

        // Make sure we have the latest data.
        $listing = $listing->fresh();

        // Verify that the listing has the correct files associated with it.
        $this->assertCount($fileCount - 1, $listing->files()->toArray());
        $this->assertNotContains($deletedId, $listing->files()->pluck('id')->toArray());
    }

    /**
     * Cleans up the request attributes to match stored values.
     *
     * @param array $request
     */
    protected function setRequestAttributesForAssertion($request)
    {
        return collect($request)->filter(function ($attr) {
            return !is_null($attr);
        })->map(function ($attr, $key) {
            $attr = ($attr === 'on') ? true : $attr;
            $attr = ($attr === 'off') ? false : $attr;
            $attr = ($attr === true) ? '1' : $attr;
            $attr = ($attr === false) ? '0' : $attr;

            return $attr;
        })->toArray();
    }

    /**
     * Gets the request for a listing.
     *
     * @param  App\Listing $listing
     *
     * @return array
     */
    protected function getListingRequest(Listing $listing)
    {
        $request = $this->removeNonFillableToArray($listing);

        // Set a few toggles to be on/off
        $request['real_estate_included'] = (bool) $request['real_estate_included'] ? 'on' : 'off';
        $request['fixtures_equipment_included'] = (bool) $request['fixtures_equipment_included'] ? 'on' : 'off';
        $request['inventory_included'] = (bool) $request['inventory_included'] ? 'on' : 'off';
        $request['name_visible'] = (bool) $request['name_visible']  ? 'on' : 'off';
        $request['address_visible'] = (bool) $request['address_visible']  ? 'on' : 'off';
        $request['financing_available'] = (bool) $request['financing_available']  ? 'on' : 'off';
        $request['support_training'] = (bool) $request['support_training']  ? 'on' : 'off';
        $request['seller_non_compete'] = (bool) $request['seller_non_compete']  ? 'on' : 'off';

        // Remove user id if it exists.
        if (isset($request['user_id'])) {
            unset($request['user_id']);
        }

        // Remove id if it exists.
        if (isset($request['id'])) {
            unset($request['id']);
        }

        // Remove updated_at if it exists.
        if (isset($request['updated_at'])) {
            unset($request['updated_at']);
        }

        // Remove created_at if it exists.
        if (isset($request['created_at'])) {
            unset($request['created_at']);
        }

        return $request;
    }

    protected function replaceNewSortIds($sortOrder, $request, $listing, $collection)
    {
        $documents = (new Documents($listing));
        $orderIds = $documents->getOrderFileIds(
            collect($request['photos']['order-file-names']),
            $collection
        );

        return $documents->mergeOrderFileIds(collect($sortOrder), $orderIds);
    }

    /**
     * Get all of the possible keys.
     *
     * @return array
     */
    protected function getPossibleListingKeys()
    {
        $keys = (new Listing)->getFillable();
        $keys[] = 'user_id';

        return $keys;
    }
}
