<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use App\Support\ExchangeSpaceDealType;
use App\Support\ExchangeSpaceStatusType;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class ExchangeSpaceAdvanceStageTest extends TestCase
{
    use HasExchangeSpaceCreators, RefreshDatabase;

    /**
     * @test
     */
    public function it_advances_an_exchange_spaces_status_to_signed_nda()
    {
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller, null)->space;

        // Set the space to have an Pre-NDA deal stage.
        $space->deal = ExchangeSpaceDealType::PRE_NDA;
        $space->save();

        // Advance the status to Signed NDA.
        $response = $this->patch(route('exchange-spaces.advance-stage.update', ['id' => $space->id]));

        // Make sure everything went ok.
        $response->assertStatus(302);
        $this->assertEquals(ExchangeSpaceDealType::SIGNED_NDA, $space->fresh()->deal);
    }

    /**
     * @test
     */
    public function it_advances_an_exchange_spaces_status_to_loi_signed()
    {
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller, null)->space;

        // Set the space to have an Signed NDA deal stage.
        $space->deal = ExchangeSpaceDealType::SIGNED_NDA;
        $space->save();

        // Advance the status to LOI Signed.
        $response = $this->patch(route('exchange-spaces.advance-stage.update', ['id' => $space->id]));

        // Make sure everything went ok.
        $response->assertStatus(302);
        $this->assertEquals(ExchangeSpaceDealType::LOI_SIGNED, $space->fresh()->deal);
    }

    /**
    * @test
    */
    public function it_advances_an_exchange_spaces_status_to_complete()
    {
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller, null)->space;
        $listing = $space->listing;

        // Set listing to be published
        $listing->published = true;
        $listing->save();
        $this->assertTrue($listing->fresh()->published);

        // Set the space to have an LOI Signed deal stage.
        $space->deal = ExchangeSpaceDealType::LOI_SIGNED;
        $space->save();

        // Add an inquiry so we can check removing it later.
        $inquiry = factory('App\ExchangeSpace')->states('inquiry')->create(['listing_id' => $space->listing->id]);

        // Add an exchange space so we can check removing it later.
        $space2 = factory('App\ExchangeSpace')->create(['listing_id' => $space->listing->id]);

        // Advance the status to Complete.
        $response = $this->patch(route('exchange-spaces.advance-stage.update', ['id' => $space->id]));

        // Make sure everything went ok.
        $space = $space->fresh();
        $response->assertStatus(302);
        $this->assertEquals(ExchangeSpaceDealType::COMPLETE, $space->deal);
        $this->assertEquals(ExchangeSpaceStatusType::COMPLETED, $space->status);

        // Make sure the listing was NOT unpublished
        $this->assertTrue($listing->fresh()->published);

        // Make sure the exchange space was NOT deleted
        $this->assertFalse($space->trashed());

        // Make sure the remaining exchange spaces are NOT deleted.
        $this->assertFalse($space2->fresh()->trashed());

        // Make sure the remaining buyer inquires are NOT deleted
        $this->assertFalse($inquiry->fresh()->trashed());
        $this->assertEquals(ExchangeSpaceStatusType::INQUIRY, $inquiry->fresh()->status);
    }

    /**
    * @test
    * @group failing
    */
    public function it_only_allows_sellers_to_advance_the_stage()
    {
        $buyer = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], null, $buyer)->space;

        // Set the space to have an Pre-NDA deal stage.
        $space->deal = ExchangeSpaceDealType::PRE_NDA;
        $space->save();

        // Advance the status to Signed NDA.
        $response = $this->patch(route('exchange-spaces.advance-stage.update', ['id' => $space->id]));

        // Make sure everything went ok.
        $response->assertStatus(403);
    }
}
