<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use App\Support\ConversationCategoryType;
use Tests\Support\HasExchangeSpaceCreators;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class BuyerInquiryConversationTest extends TestCase
{
    use RefreshDatabase, HasExchangeSpaceCreators;

    /**
    * @test
    */
    public function it_requires_the_message_body()
    {
        // Create the conversation.
        $conversation = $this->createInquiryConversation();

        // Sign in the buyer.
        $this->signInWithEvents($conversation->space->buyer_user);

        // Make sure are messages start as empty.
        $this->assertCount(0, $conversation->messages);

        // Add a message.
        $response = $this->post($this->getStoreRoute($conversation));

        // Check the response
        $response->assertStatus(302)
                 ->assertSessionHasErrors(['body']);

        // Check the message.
        $this->assertCount(0, $conversation->messages);
    }

    /**
    * @test
    */
    public function it_allows_the_buyer_to_post_buyer_inquiry_a_message()
    {
        // Create the conversation.
        $conversation = $this->createInquiryConversation();

        // Sign in the buyer.
        $user = $this->signInWithEvents($conversation->space->buyer_user);

        // Make sure are messages start as empty.
        $this->assertCount(0, $conversation->messages);

        // Add a message.
        $body = $this->faker->paragraphs(3, true);
        $response = $this->post($this->getStoreRoute($conversation), [
            'body' => $body,
            'files' => [
                'new' => $files = $this->getTestFiles(2, 'jpg'),
            ],
        ]);

        // Check the response
        $response->assertStatus(302);

        // Check the message.
        $messages = $conversation->fresh()->messages;
        $message = $messages->first();
        $this->assertCount(1, $messages);
        $this->assertEquals($body, $message->body);
        $this->assertEquals(
            $conversation->space->buyer_user->id,
            $message->user_id
        );
        $this->assertEquals($conversation->id, $message->conversation_id);

        // Make sure the files where added to the exchange space.
        $space = $conversation->space->fresh();
        $media = $space->getMedia();
        $this->assertCount(count($files), $space->getMedia());
        $media->each(function ($item) use ($conversation, $message, $user) {
            $this->assertEquals($conversation->id, $item->getCustomProperty('conversation_id'));
            $this->assertEquals($message->id, $item->getCustomProperty('message_id'));
            $this->assertEquals($user->id, $item->getCustomProperty('user_id'));
        });
    }

    /**
    * @test
    */
    public function it_allows_the_seller_to_post_buyer_inquiry_a_message()
    {
        // Create the conversation.
        $conversation = $this->createInquiryConversation();

        // Sign in the seller.
        $user = $this->signInWithEvents($conversation->seller->user);

        // Make sure are messages start as empty.
        $this->assertCount(0, $conversation->messages);

        // Add a message.
        $body = $this->faker->paragraphs(3, true);
        $response = $this->post($this->getStoreRoute($conversation), [
            'body' => $body,
            'files' => [
                'new' => $files = $this->getTestFiles(2, 'jpg'),
            ],
        ]);

        // Check the response
        $response->assertStatus(302);

        // Check the message.
        $messages = $conversation->fresh()->messages;
        $message = $messages->first();
        $this->assertCount(1, $messages);
        $this->assertEquals($body, $message->body);
        $this->assertEquals(
            $conversation->seller->user->id,
            $message->user_id
        );
        $this->assertEquals($conversation->id, $message->conversation_id);

        // Make sure the files where added to the exchange space.
        $space = $conversation->space->fresh();
        $media = $space->getMedia();
        $this->assertCount(count($files), $space->getMedia());
        $media->each(function ($item) use ($conversation, $message, $user) {
            $this->assertEquals($conversation->id, $item->getCustomProperty('conversation_id'));
            $this->assertEquals($message->id, $item->getCustomProperty('message_id'));
            $this->assertEquals($user->id, $item->getCustomProperty('user_id'));
        });
    }

    /**
     * Gets the store route.
     *
     * @param App\Conversation $conversation
     * @return void
     */
    protected function getStoreRoute($conversation)
    {
        return route('business-inquiry.conversation.store', [
            'id' => $conversation->space->id,
            'c_id' => $conversation->id,
        ]);
    }
}
