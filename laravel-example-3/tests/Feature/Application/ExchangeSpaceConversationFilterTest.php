<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use App\Support\ConversationCategoryType;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class ExchangeSpaceConversationFilterTest extends TestCase
{
    use HasExchangeSpaceCreators, RefreshDatabase;

    /**
    * @test
    */
    public function it_filters_unresolved_conversation()
    {
        // Setup a user so they can filter
        $seller = $this->signInWithEvents();

        // Create a space with one resolved converstion the inquiry.
        $space = $this->createSpaceConversation([], $seller)->space->fresh();
        $this->assertCount(1, $space->fresh()->conversations()->get());

        // Add a few more conversations that are not resolved.
        $unresolved = [];
        $unresolved[] = $this->addConversationToSpace($space, ['resolved' => false]);
        $unresolved[] = $this->addConversationToSpace($space, ['resolved' => false]);
        $unresolved[] = $this->addConversationToSpace($space, ['resolved' => false]);

        // Add a few more conversations that are resolved.
        $this->addConversationToSpace($space, ['resolved' => true]);
        $this->addConversationToSpace($space, ['resolved' => true]);
        $this->addConversationToSpace($space, ['resolved' => true]);
        $this->addConversationToSpace($space, ['resolved' => true]);

        // Make the filter request.
        $response = $this->json('POST', $this->route($space), [
            'all' => 1,
            'resolved' => 0,
        ]);

        // Make sure we get the correct items back.
        $items = collect($response->json()['data']);
        $this->assertCount(count($unresolved), $items);
        $this->assertEquals(
            collect($unresolved)->pluck('id')->sort()->values()->toArray(),
            $items->pluck('id')->sort()->values()->toArray()
        );
    }

    /**
    * @test
    */
    public function it_filters_resolved_conversation()
    {
        // Setup a user so they can filter
        $seller = $this->signInWithEvents();

        // Create a space with one resolved converstion the inquiry.
        $space = $this->createSpaceConversation([], $seller)->space->fresh();
        $this->assertCount(1, $conversations = $space->fresh()->conversations()->get());

        // Add the resolved inquiry conversation to the resolved conversations.
        $resolved = [
            $conversations->first()
        ];

        // Add a few more conversations that are not resolved.
        $this->addConversationToSpace($space, ['resolved' => false]);
        $this->addConversationToSpace($space, ['resolved' => false]);
        $this->addConversationToSpace($space, ['resolved' => false]);

        // Add a few more conversations that are resolved.
        $resolved[] = $this->addConversationToSpace($space, ['resolved' => true]);
        $resolved[] = $this->addConversationToSpace($space, ['resolved' => true]);
        $resolved[] = $this->addConversationToSpace($space, ['resolved' => true]);

        // Make the filter request.
        $response = $this->json('POST', $this->route($space), [
            'all' => 1,
            'resolved' => 1,
        ]);

        // Make sure we get the correct items back.
        $items = collect($response->json()['data']);
        $this->assertCount(count($resolved), $items);
        $this->assertEquals(
            collect($resolved)->pluck('id')->sort()->values()->toArray(),
            $items->pluck('id')->sort()->values()->toArray()
        );
    }

    /**
    * @test
    */
    public function it_filters_conversation_by_category()
    {
        // Setup a user so they can filter
        $seller = $this->signInWithEvents();

        // Create a space with one resolved converstion the inquiry.
        $space = $this->createSpaceConversation([], $seller)->space->fresh();
        $this->assertCount(1, $space->fresh()->conversations()->get());

        // Add a few more conversations that are in the category.
        $category = $this->getRandomConversationCategory();
        $matched[] = $this->addConversationToSpace($space, ['category' => $category]);
        $matched[] = $this->addConversationToSpace($space, ['category' => $category]);
        $matched[] = $this->addConversationToSpace($space, ['category' => $category]);
        $matched[] = $this->addConversationToSpace($space, ['category' => $category]);

        // Add a few more conversations that are not in the category.
        $this->addConversationToSpace($space, ['category' => $this->getRandomConversationCategory([$category])]);
        $this->addConversationToSpace($space, ['category' => $this->getRandomConversationCategory([$category])]);
        $this->addConversationToSpace($space, ['category' => $this->getRandomConversationCategory([$category])]);

        // Make the filter request.
        $response = $this->json('POST', $this->route($space), [
            'all' => 1,
            'category' => $category,
        ]);

        // Make sure we get the correct items back.
        $items = collect($response->json()['data']);
        $this->assertCount(count($matched), $items);
        $this->assertEquals(
            collect($matched)->pluck('id')->sort()->values()->toArray(),
            $items->pluck('id')->sort()->values()->toArray()
        );
    }

    /**
    * @test
    * @group failing
    */
    public function it_searches_conversations()
    {
        $this->withoutExceptionHandling();

        // Setup a user so they can filter
        $seller = $this->signInWithEvents();

        // Create a space with one resolved converstion the inquiry.
        $space = $this->createSpaceConversation([], $seller)->space->fresh();
        $this->assertCount(1, $space->fresh()->conversations()->get());

        // Set search value
        $search = 'examplesearch';

        // Add conversations that would be found.
        $matched[] = $this->addConversationToSpace($space, [
            'title' => $search,
        ]);

        // conversation->latest_message->creator_member->name
        $matched[] = $convo2 = $this->addConversationToSpace($space, []);
        $matched[] = $convo3 = $this->addConversationToSpace($space, []);
        factory('App\ExchangeSpaceMember')->create([
            'exchange_space_id' => $space->id,
            'user_id' => $convo2User = factory('App\User')->create(['last_name' => "before{$search}"]),
        ])->id;
        $this->addMessageToConversation($convo2, [
            'user_id' => $convo2User->id,
        ]);
        factory('App\ExchangeSpaceMember')->create([
            'exchange_space_id' => $space->id,
            'user_id' => $convo3User = factory('App\User')->create(['last_name' => "before{$search}"]),
        ])->id;
        $this->addMessageToConversation($convo3, [
            'user_id' => $convo3User->id,
        ]);

        // conversation->latest_message->body
        $matched[] = $convo4 = $this->addConversationToSpace($space, []);
        $this->addMessageToConversation($convo4, [ 'body' => "before {$search} after among some other after." ]);

        // Add a few more conversations that are not going to be matched.
        $this->addConversationToSpace($space, []);
        $this->addConversationToSpace($space, []);
        $this->addConversationToSpace($space, []);

        // Make the filter request.
        $response = $this->json('POST', $this->route($space), [
            'all' => 1,
            'search' => $search,
        ]);

        // Make sure we get the correct items back.
        $items = collect($response->json()['data']);
        $this->assertCount(count($matched), $items);
        $this->assertEquals(
            collect($matched)->pluck('id')->sort()->values()->toArray(),
            $items->pluck('id')->sort()->values()->toArray()
        );
    }

    /**
     * Gets the index route.
     *
     * @param App\ExchangeSpace $space
     * @return void
     */
    public function route($space)
    {
        return route('exchange-spaces.conversations.index', [
            'id' => $space->id,
        ]);
    }
}
