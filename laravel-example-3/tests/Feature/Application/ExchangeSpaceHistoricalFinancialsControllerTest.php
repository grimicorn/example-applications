<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class ExchangeSpaceHistoricalFinancialsControllerTest extends TestCase
{
    use HasExchangeSpaceCreators, RefreshDatabase;

    /**
    * @test
    */
    public function it_updates_historical_financals_public_status()
    {
        // Setup the space.
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller)->space;
        $space->historical_financials_public = false;
        $space->save();
        $space = $space->fresh();

        // Make sure the current status is not public so we can toggle it and check it later.
        $this->assertFalse($space->historical_financials_public);

        // Attempt to set public to true.
        $response = $this->post(
            route(
                'exchange-spaces.historical-financials.update',
                ['id' => $space->id]
            ),
            ['public' => true]
        );

        // Check the response
        $response->assertStatus(302);

        // Check it the value was toggled properly
        $this->assertTrue($space->fresh()->historical_financials_public);
    }

    /**
    * @test
    * @group failing
    */
    public function it_only_allows_sellers_to_update_historical_financals_public_status()
    {
        // Setup the space.
        $buyer = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], null, $buyer)->space;

        // Attempt to set public to true being a buyer.
        $response = $this->post(
            route(
                'exchange-spaces.historical-financials.update',
                ['id' => $space->id]
            ),
            ['public' => true]
        );

        // Check the response
        $response->assertStatus(403);
    }
}
