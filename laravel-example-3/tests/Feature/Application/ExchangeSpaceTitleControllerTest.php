<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class ExchangeSpaceTitleControllerTest extends TestCase
{
    use HasExchangeSpaceCreators, RefreshDatabase;

    /**
    * @test
    */
    public function it_allows_sellers_to_update_the_exchange_space_title()
    {
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller, null)->space;
        $data = [ 'subtitle' => $subtitle = 'My New Title' ];
        $response = $this->patch(route('exchange-spaces.member-title.update', ['id' => $space->id]), $data);

        // Make sure everything went correctly.
        $response->assertStatus(302);
        $this->assertEquals($space->fresh()->title, $subtitle);
    }

    /**
    * @test
    */
    public function it_allows_buyers_to_update_the_exchange_space_title()
    {
        $buyer = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], null, $buyer)->space;
        $space->allMembers->each->activate();
        $data = [ 'subtitle' => $subtitle = 'My New Title' ];
        $response = $this->patch(route('exchange-spaces.member-title.update', ['id' => $space->id]), $data);

        // Make sure everything went correctly.
        $response->assertStatus(302);
        $this->assertEquals($space->fresh()->title, $subtitle);
    }
}
