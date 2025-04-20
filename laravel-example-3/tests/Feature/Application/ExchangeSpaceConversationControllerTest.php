<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use App\ConversationNotification;
use App\Support\ExchangeSpaceStatusType;
use App\Support\ConversationCategoryType;
use Tests\Support\HasExchangeSpaceCreators;
use App\Support\Notification\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreStart
class ExchangeSpaceConversationControllerTest extends TestCase
{
    use HasExchangeSpaceCreators, RefreshDatabase;

    /**
    * @test
    */
    public function it_allows_sellers_to_creates_new_exchange_space_conversation()
    {
        $this->withoutExceptionHandling();
        $seller = $this->signInWithEvents();
        $space = $this->createAcceptedExchangeSpace([], $seller);
        $this->checkCreation($space, $seller);
    }

    /**
    * @test
    */
    public function it_allows_buyers_to_creates_new_exchange_space_conversation()
    {
        $this->withoutExceptionHandling();
        $buyer = $this->signInWithEvents();
        $space = $this->createAcceptedExchangeSpace([], null, $buyer);
        $this->checkCreation($space, $buyer);
    }

    /**
    * @test
    */
    public function it_allows_sellers_to_update_an_existing_exchange_space_conversation()
    {
        $conversation = $this->createSpaceConversation();
        $seller = $conversation->seller->user;
        $this->signInWithEvents($seller);
        $this->checkUpdating($conversation, $seller);
    }

    /**
    * @test
    */
    public function it_allows_buyers_to_update_an_existing_exchange_space_conversation()
    {
        $conversation = $this->createSpaceConversation();
        $buyer = $conversation->space->buyer_user;
        $this->signInWithEvents($buyer);
        $this->checkUpdating($conversation, $buyer);
    }


    /**
    * @test
    */
    public function it_reads_a_users_new_conversation_notifications_when_visiting_the_conversation()
    {
        // Given a user has a new conversation notification for a conversation..
        $notification = factory('App\ConversationNotification')->states('unread', 'new')->create();
        $this->signInWithEvents($notification->user);
        $conversation = $notification->conversation;

        // Make sure the new conversation notification has not been read...
        $this->assertFalse($notification->read);

        // and the user visits that conversation...
        $this->get(route('exchange-spaces.conversations.show', [
            'id' => $conversation->space->id,
            'c_id' => $conversation->id,
        ]));

        // Make sure that the new conversation notification has been read.
        $this->assertTrue($notification->fresh()->read);
    }

    /**
    * @test
    */
    public function it_reads_a_users_new_message_notifications_when_visiting_the_conversation()
    {
        // Given a user has a new conversation notification for a conversation..
        $notification = factory('App\ConversationNotification')->states('unread', 'message')->create();
        $this->signInWithEvents($notification->user);
        $conversation = $notification->conversation;

        // make sure the exchange space is not an inquiry...
        $conversation->space->status = ExchangeSpaceStatusType::ACCEPTED;
        $conversation->space->save();

        // Make sure the new conversation notification has not been read...
        $this->assertFalse($notification->read);

        // and the user visits that conversation...
        $this->get(route('exchange-spaces.conversations.show', [
            'id' => $conversation->space->id,
            'c_id' => $conversation->id,
        ]));

        // Make sure that the new conversation notification has been read.
        $this->assertTrue($notification->fresh()->read);
    }

    /**
     * Checks creation of a conversation.
     *
     * @param App\ExchangeSpace $space
     * @param App\User $user
     */
    protected function checkCreation($space, $user)
    {
        // Make sure we only have the business inquiry so we can check that only one is added later.
        $this->assertCount(1, $space->conversations);

        // Make sure there are no files for this space,
        $this->assertCount(0, $space->getMedia());

        $response = $this->post(
            route('exchange-spaces.conversations.store', [
                'id' => $space->id,
            ]),
            [
                'title' => $title = $this->faker->words(3, true),
                'category' => $category = $this->getRandomConversationCategory(),
                'body' => $body = $this->faker->paragraphs(3, true),
                'files' => [
                    'new' => $files = $this->getTestFiles(2, 'jpg'),
                ],
            ]
        );

        // Make sure the request went ok
        $response->assertStatus(302);

        // The conversation should have one conversation with the created title/category.
        $space = $space->fresh();
        $conversations = $space->conversations->fresh();
        $this->assertCount(2, $conversations);
        $conversation = $conversations->whereNotIn('category', [
            ConversationCategoryType::BUYER_INQUIRY
        ])->first();
        $this->assertEquals($title, $conversation->title);
        $this->assertEquals($category, $conversation->category);

        // The conversation should have 1 message with posted body as the message body. Theuserr should be the message creator.
        $this->assertCount(1, $conversation->messages);
        $message = $conversation->messages->first();
        $this->assertEquals($body, $message->body);
        $this->assertEquals($user->id, $message->user_id);

        // Make sure the files where added to the exchange space.
        $media = $space->getMedia();
        $this->assertCount(count($files), $space->getMedia());
        $media->each(function ($item) use ($conversation, $message, $user) {
            $this->assertEquals($conversation->id, $item->getCustomProperty('conversation_id'));
            $this->assertEquals($message->id, $item->getCustomProperty('message_id'));
            $this->assertEquals($user->id, $item->getCustomProperty('user_id'));
        });
    }

    /**
     * Checks updating of a conversation.
     *
     * @param App\Conversation $conversation
     * @param App\User $user
     */
    protected function checkUpdating($conversation, $user)
    {
        $messageCount = $conversation->messages->count();

        // Make sure there are no files for this space,
        $this->assertCount(0, $conversation->space->getMedia());

        $response = $this->patch(
            route('exchange-spaces.conversations.update', [
                'id' => $conversation->space->id,
                'c_id' => $conversation->id,
            ]),
            [
                'body' => $body = $this->faker->paragraphs(3, true),
                'files' => [
                    'new' => $files = $this->getTestFiles(2, 'jpg'),
                ],
            ]
        );

        $response->assertStatus(302);

        // The conversation should have the start message count + 1 message with posted body as the message body. The user should be the message creator.
        $messages = $conversation->messages()->get();
        $this->assertCount($messageCount + 1, $messages);
        $message = $messages->last();
        $this->assertEquals($body, $message->body);
        $this->assertEquals($user->id, $message->user_id);

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
}
