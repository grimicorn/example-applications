<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class ExchangeSpaceControllerTest extends TestCase
{
    use HasExchangeSpaceCreators;
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_deletes_an_exchange_space()
    {
        // Make a space.
        $seller = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], $seller)->space;

        // Delete the space.
        $response = $this->delete(
            route('exchange-spaces.destroy', ['id' => $space->id]),
            ['close_message' => $close_message = $this->faker->paragraphs(3, true)]
        );

        // Check that everything went ok.
        $space = $space->fresh();
        $response->assertStatus(302);
        $this->assertTrue($space->trashed());
        $this->assertEquals($close_message, $space->close_message);
    }

    /**
    * @test
    */
    public function it_only_allows_sellers_to_delete_an_exchange_space()
    {
        // Make a space.
        $buyer = $this->signInWithEvents();
        $seller = factory('App\User')->create();
        $space = $this->createSpaceConversation([], $seller, $buyer)->space;

        // Delete the space.
        $response = $this->delete(route('exchange-spaces.destroy', ['id' => $space->id]));

        // Check that the space was not deleted.
        $response->assertStatus(403);
        $this->assertFalse($space->fresh()->trashed());
    }
}
