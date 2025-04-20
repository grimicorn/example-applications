<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\HasExchangeSpaceCreators;

// @codingStandardsIgnoreFile
class ExchangeSpacesDashboardTest extends TestCase
{
    use RefreshDatabase;
    use HasExchangeSpaceCreators;

    /**
    * @test
    * @group failing
    */
    public function it_adds_an_exchange_space_to_the_dashboard()
    {
        $buyer = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], null, $buyer)->space;
        $member = $space->buyerMember();

        $this->post(route('exchange-spaces.dashboard.store', ['id' => $space->id]));

        $this->assertEquals(
            $space->id,
            $member->fresh()->onDashboard()->first()->space->id
        );
    }

    /**
    * @test
    * @group failing
    */
    public function it_removes_an_exchange_space_from_the_dashboard()
    {
        $buyer = $this->signInWithEvents();
        $space = $this->createSpaceConversation([], null, $buyer)->space;
        $member = $space->buyerMember();

        $this->delete(route('exchange-spaces.dashboard.destroy', ['id' => $space->id]));
        $this->assertFalse($member->fresh()->dashboard);
    }
}
