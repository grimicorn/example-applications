<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use App\ExchangeSpace;
use Tests\Support\AssertsGuards;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class CheckIsExchangeSpaceMemberTest extends TestCase
{
    use HasExchangeSpaceCreators,
        AssertsGuards,
        RefreshDatabase;

    /**
    * @test
    */
    public function it_does_not_allow_access_to_non_members()
    {
        $space = $this->createSpaceConversation([])->space;
        $this->signInWithEvents();

        $this->assertAllGuarded($space);
    }

    /**
    * @test
    */
    public function it_does_not_allow_access_to_unapproved_members()
    {
        $space = $this->createSpaceConversation([])->space;
        $member = factory('App\ExchangeSpaceMember')->create([
            'exchange_space_id' => $space->id,
            'active' => true,
            'approved' => false,
        ]);
        $this->signInWithEvents($member->user);

        $this->assertAllGuarded($space);
    }

    /**
    * @test
    */
    public function it_does_not_allow_access_to_unactive_members()
    {
        $space = $this->createSpaceConversation([])->space;
        $member = factory('App\ExchangeSpaceMember')->create([
            'exchange_space_id' => $space->id,
            'active' => false,
            'approved' => true,
        ]);
        $this->signInWithEvents($member->user);

        $this->assertAllGuarded($space);
    }

    /**
    * @test
    */
    public function it_allows_access_to_approved_active_members()
    {
        $seller = $this->signInWithEvents();
        $buyer = factory('App\User')->create();
        $space = $this->createSpaceConversation([], $seller, $buyer)->space;
        $this->assertAllNotGuarded($space);
    }

    /**
     * Asserts all of the possible routes are not guarded.
     *
     * @param \App\ExchangeSpace $space
     * @return void
     */
    protected function assertAllNotGuarded(ExchangeSpace $space)
    {
        $this->assertNotGuarded(
            'get',
            route('exchange-spaces.show', ['id' => $space->id])
        );
        $this->assertNotGuarded(
            'put',
            route('exchange-spaces.member-title.update', ['id' => $space->id])
        );
        $this->assertNotGuarded(
            'patch',
            route('exchange-spaces.member-title.update', ['id' => $space->id])
        );
        $this->assertNotGuarded(
            'post',
            route('exchange-spaces.member.index', ['id' => $space->id])
        );
        $this->assertNotGuarded(
            'post',
            route('exchange-spaces.dashboard.store', ['id' => $space->id])
        );
        $this->assertNotGuarded(
            'delete',
            route('exchange-spaces.dashboard.destroy', ['id' => $space->id])
        );
        $this->assertNotGuarded(
            'get',
            route('exchange-spaces.conversations.index', ['id' => $space->id])
        );
        $this->assertNotGuarded(
            'post',
            route('exchange-spaces.conversations.index', ['id' => $space->id])
        );
        $this->assertNotGuarded(
            'get',
            route('exchange-spaces.conversations.create', ['id' => $space->id])
        );
        $this->assertNotGuarded(
            'post',
            route('exchange-spaces.conversations.store', ['id' => $space->id])
        );
        $this->assertNotGuarded(
            'post',
            route('exchange-spaces.file.destroy', ['id' => $space->id])
        );
        $this->assertNotGuarded(
            'get',
            route('exchange-spaces.notifications.index', ['id' => $space->id])
        );
        $this->assertNotGuarded(
            'post',
            route('exchange-spaces.notifications.update', ['id' => $space->id])
        );
    }

    /**
     * Asserts all of the possible routes are not guarded.
     *
     * @param \App\ExchangeSpace $space
     * @return void
     */
    protected function assertAllGuarded(ExchangeSpace $space)
    {
        $this->assertGuarded(
            'get',
            route('exchange-spaces.show', ['id' => $space->id])
        );
        $this->assertGuarded(
            'put',
            route('exchange-spaces.member-title.update', ['id' => $space->id])
        );
        $this->assertGuarded(
            'patch',
            route('exchange-spaces.member-title.update', ['id' => $space->id])
        );
        $this->assertGuarded(
            'post',
            route('exchange-spaces.member.index', ['id' => $space->id])
        );
        $this->assertGuarded(
            'post',
            route('exchange-spaces.dashboard.store', ['id' => $space->id])
        );
        $this->assertGuarded(
            'delete',
            route('exchange-spaces.dashboard.destroy', ['id' => $space->id])
        );
        $this->assertGuarded(
            'get',
            route('exchange-spaces.conversations.index', ['id' => $space->id])
        );
        $this->assertGuarded(
            'post',
            route('exchange-spaces.conversations.index', ['id' => $space->id])
        );
        $this->assertGuarded(
            'get',
            route('exchange-spaces.conversations.create', ['id' => $space->id])
        );
        $this->assertGuarded(
            'post',
            route('exchange-spaces.conversations.store', ['id' => $space->id])
        );
        $this->assertGuarded(
            'post',
            route('exchange-spaces.file.destroy', ['id' => $space->id])
        );
        $this->assertGuarded(
            'get',
            route('exchange-spaces.notifications.index', ['id' => $space->id])
        );
        $this->assertGuarded(
            'post',
            route('exchange-spaces.notifications.update', ['id' => $space->id])
        );
    }
}
