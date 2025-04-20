<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use App\Conversation;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class ExchangeSpaceConversationResolveTest extends TestCase
{
    use RefreshDatabase;
    use HasExchangeSpaceCreators;

    /**
    * @test
    * @group failing
    */
    public function it_resolves_a_conversation()
    {
        $this->withoutExceptionHandling();
        $buyer = $this->signInWithEvents();
        $conversation = $this->createSpaceConversation([
            'resolved' => false,
        ], null, $buyer);

        $this->assertFalse($conversation->resolved);
        $response = $this->post($this->getRoute('store', $conversation));
        $response->assertStatus(302);

        $this->assertTrue($conversation->fresh()->resolved);
    }

    /**
    * @test
    * @group failing
    */
    public function it_unresolves_a_conversation()
    {
        $seller = $this->signInWithEvents();
        $this->withoutExceptionHandling();

        $conversation = $this->createSpaceConversation([
            'resolved' => true,
        ], $seller);

        $this->assertTrue($conversation->resolved);
        $response = $this->delete($this->getRoute('destroy', $conversation));
        $response->assertStatus(302);

        $this->assertFalse($conversation->fresh()->resolved);
    }

    /**
    * @test
    */
    public function it_does_not_unresolve_inquiry_conversations()
    {
        $seller = $this->signInWithEvents();
        $this->withoutExceptionHandling();

        $conversation = $this->createSpaceConversation([
            'resolved' => true,
            'is_inquiry' => true,
        ], $seller);

        $this->assertTrue($conversation->resolved);
        $response = $this->delete($this->getRoute('destroy', $conversation));
        $response->assertStatus(302);

        $this->assertTrue($conversation->fresh()->resolved);
    }

    /**
     * Gets the resolve route.
     *
     * @param string $action
     * @param App\Conversation $conversation
     * @return void
     */
    protected function getRoute($action, Conversation $conversation)
    {
        return route(
            "exchange-spaces.conversations.resolve.{$action}",
            [
                'id' => $conversation->space->id,
                'c_id' => $conversation->id,
            ]
        );
    }
}
