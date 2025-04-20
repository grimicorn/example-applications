<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use App\Support\ExchangeSpaceDealType;
use App\Support\ExchangeSpaceStatusType;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignoreFile
class ListingExitSurveyControllerTest extends TestCase
{
    use HasExchangeSpaceCreators,
        RefreshDatabase;

    /**
    * @test
    */
    public function it_allows_disabling_of_exchange_auto_closing()
    {
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller, null)->space;
        $listing = $space->listing;

        // Set listing to be published
        $listing->user_id = $seller->id;
        $listing->published = true;
        $listing->save();
        $this->assertTrue($listing->fresh()->published);

        // Set the space to have an LOI Signed deal stage.
        $space->deal = ExchangeSpaceDealType::LOI_SIGNED;
        $space->save();

        // Add an inquiry so we can check removing it later.
        $inquiry = factory('App\ExchangeSpace')->states('inquiry')->create(['listing_id' => $space->listing->id]);

        // Add an exchange space so we can check removing it later.
        $space2 = factory('App\ExchangeSpace')->create([
            'listing_id' => $space->listing->id,
            'deal' => ExchangeSpaceDealType::PRE_NDA,
            'status' => ExchangeSpaceStatusType::ACCEPTED,
        ]);

        // Advance the status to Complete.
        $response = $this->patch(route('exchange-spaces.advance-stage.update', ['id' => $space->id]));

        // Make sure everything went ok.
        $space = $space->fresh();
        $response->assertStatus(302);
        $this->assertEquals(ExchangeSpaceDealType::COMPLETE, $space->deal);
        $this->assertEquals(ExchangeSpaceStatusType::COMPLETED, $space->status);

        // Make sure the exchange space was NOT deleted
        $this->assertFalse($space->trashed());

        // Make sure the remaining exchange spaces are NOT deleted.
        $this->assertFalse($space2->fresh()->trashed());

        // Make sure the remaining buyer inquires are NOT deleted
        $this->assertFalse($inquiry->fresh()->trashed());
        $this->assertEquals(ExchangeSpaceStatusType::INQUIRY, $inquiry->fresh()->status);

        // Fill out the exit survey
        $this->post(route('listing.exit-survey.store', [
            'id' => $listing->id,
            'disable_space_close' => 1,
        ]));

        // Make sure the exchange space was NOT deleted
        $this->assertFalse($space->trashed());

        // Make sure the remaining exchange spaces are NOT deleted.
        $this->assertFalse($space2->fresh()->trashed());

        // Make sure the remaining buyer inquires are NOT deleted
        $this->assertFalse($inquiry->fresh()->trashed());
        $this->assertNotEquals(ExchangeSpaceStatusType::REJECTED, $inquiry->fresh()->status);
    }

    /**
    * @test
    */
    public function it_closes_other_exchange_spaces_when_saving_exit_survey()
    {
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller, null)->space;
        $listing = $space->listing;

        // Set listing to be published
        $listing->user_id = $seller->id;
        $listing->published = true;
        $listing->save();
        $this->assertTrue($listing->fresh()->published);

        // Set the space to have an LOI Signed deal stage.
        $space->deal = ExchangeSpaceDealType::LOI_SIGNED;
        $space->save();

        // Add an inquiry so we can check removing it later.
        $inquiry = factory('App\ExchangeSpace')->states('inquiry')->create(['listing_id' => $space->listing->id]);

        // Add an exchange space so we can check removing it later.
        $space2 = factory('App\ExchangeSpace')->create([
            'listing_id' => $space->listing->id,
            'deal' => ExchangeSpaceDealType::PRE_NDA,
            'status' => ExchangeSpaceStatusType::ACCEPTED,
        ]);

        // Advance the status to Complete.
        $response = $this->patch(route('exchange-spaces.advance-stage.update', ['id' => $space->id]));

        // Make sure everything went ok.
        $space = $space->fresh();
        $response->assertStatus(302);
        $this->assertEquals(ExchangeSpaceDealType::COMPLETE, $space->deal);
        $this->assertEquals(ExchangeSpaceStatusType::COMPLETED, $space->status);

        // Make sure the exchange space was NOT deleted
        $this->assertFalse($space->trashed());

        // Make sure the remaining exchange spaces are NOT deleted.
        $this->assertFalse($space2->fresh()->trashed());

        // Make sure the remaining buyer inquires are NOT deleted
        $this->assertFalse($inquiry->fresh()->trashed());
        $this->assertEquals(ExchangeSpaceStatusType::INQUIRY, $inquiry->fresh()->status);

        // Fill out the exit survey
        $this->post(route('listing.exit-survey.store', ['id' => $listing->id]));

        // Make sure the exchange space was deleted
        $this->assertFalse($space->trashed());

        // Make sure the remaining exchange spaces are deleted.
        $this->assertTrue($space2->fresh()->trashed());

        // Make sure the remaining buyer inquires are deleted
        $this->assertTrue($inquiry->fresh()->trashed());
        $this->assertEquals(ExchangeSpaceStatusType::REJECTED, $inquiry->fresh()->status);
    }
}
