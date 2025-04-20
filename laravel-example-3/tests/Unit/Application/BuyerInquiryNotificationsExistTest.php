<?php

namespace Tests\Unit\Application;

use App\User;
use Tests\TestCase;
use App\Conversation;
use App\ExchangeSpace;
use App\ExchangeSpaceNotification;
use App\Support\ExchangeSpaceStatusType;
use Illuminate\Foundation\Testing\WithFaker;
use App\Support\Notification\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\ConversationNotification;

// @phpcs:ignoreFile
// @codingStandardsIgnoreFile
class BuyerInquiryNotificationsExistTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_checks_if_the_conversation_has_unread_inquiry_message_notifications()
    {
        // Create a notification this will setup the space and conversation as well...
        $notification = factory('App\ConversationNotification')
        ->states('message', 'unread')->create();

        // lets store the conversation so we can use it later...
        $conversation = $notification->conversation->fresh();

        // lets also make sure the exchange space is an inquiry...
        $conversation->space->status = ExchangeSpaceStatusType::INQUIRY;
        $conversation->space->save();

        // and finally lets log in the user since all checks are based off the the authenticated user.
        $user = $this->signInWithEvents($conversation->user);

        // Now we can check the notification.
        // If an unread message notification exists $conversation->has_notifications === true
        $this->assertTrue($conversation->fresh()->has_notifications);
    }

    /**
     * @test
     */
    public function it_checks_if_the_conversation_has_unread_inquiry_new_inquiry_notifications()
    {
        // Create a notification this will setup the space as well...
        $notification = factory('App\ExchangeSpaceNotification')
        ->states('new-inquiry', 'unread')->create();

        // lets also make sure the exchange space is an inquiry...
        $notification->exchangeSpace->status = ExchangeSpaceStatusType::INQUIRY;
        $notification->exchangeSpace->save();

        // lets create the conversation so we can use it later...
        $conversation = factory('App\Conversation')->create([
            'exchange_space_id' => $notification->exchangeSpace->id,
        ]);

        // and finally lets log in the user since all checks are based off the the authenticated user.
        $user = $this->signInWithEvents($conversation->user);

        // Now we can check the notification.
        // If an unread new conversation notification exists $conversation->has_notifications === true
        $this->assertTrue($conversation->fresh()->has_notifications);
    }

    /**
     * @test
     */
    public function it_checks_if_the_conversation_has_unread_inquiry_new_inquiry_and_mesage_notifications()
    {
        // Create a conversation notification this will setup the space and conversation as well...
        $notification1 = factory('App\ConversationNotification')
        ->states('message', 'unread')->create();

        // lets store the conversation so we can use it later...
        $conversation = $notification1->conversation->fresh();

        // lets also make sure the exchange space is an inquiry...
        $conversation->space->status = ExchangeSpaceStatusType::INQUIRY;
        $conversation->space->save();

        // lets log in the user since all checks are based off the
        // the authenticated user.
        $user = $user = $this->signInWithEvents($conversation->user);

        // and finally lets create an exchange space notification.
        $notification2 = factory('App\ExchangeSpaceNotification')->states('new-inquiry', 'unread')->create([
            'user_id' => $user->id,
            'exchange_space_id' => $conversation->space->id,
        ]);

        // Now we can check the notifications.
        // If an unread new conversation notification and an unread message notification exists $conversation->has_notifications === true
        $this->assertTrue($conversation->fresh()->has_notifications);
    }

    /**
     * @test
     */
    public function it_checks_if_the_conversation_has_no_unread_inquiry_notifications()
    {
        // Create a conversation notification this will setup the space and conversation as well...
        $notification1 = factory('App\ConversationNotification')
        ->states('message', 'unread')->create();

        // lets store the conversation so we can use it later...
        $conversation = $notification1->conversation->fresh();

        // lets also make sure the exchange space is an inquiry...
        $conversation->space->status = ExchangeSpaceStatusType::INQUIRY;
        $conversation->space->save();

        // lets log in the user since all checks are based off the
        // the authenticated user.
        $user = $user = $this->signInWithEvents($conversation->user);

        // and finally lets create an exchange space new inquiry notification.
        $notification2 = factory('App\ExchangeSpaceNotification')->states('new-inquiry', 'unread')->create([
            'user_id' => $user->id,
            'exchange_space_id' => $conversation->space->id,
        ]);

        // Set both notifications to read.
        $notification1->read = true;
        $notification1->save();
        $notification2->read = true;
        $notification2->save();

        // If an unread new conversation notification and an unread message notification DOES NOT exist $conversation->has_notifications === false
        $this->assertFalse($conversation->fresh()->has_notifications);
    }

    /**
    * @test
    */
    public function it_checks_if_an_user_has_unread_inquiry_message_notifications()
    {
        // Create a notification this will setup the space and conversation as well...
        $notification = factory('App\ConversationNotification')
        ->states('message', 'unread')->create();

        // lets store the conversation so we can use it later...
        $conversation = $notification->conversation->fresh();

        // lets also make sure the exchange space is an inquiry...
        $conversation->space->status = ExchangeSpaceStatusType::INQUIRY;
        $conversation->space->save();

        // and finally lets log in the user since all checks are based off the the authenticated user.
        $user = $this->signInWithEvents($conversation->user);

        // Now we can check the notification.
        // If an unread message notification exists $user->has_buyer_inquiry_notifications === true
        $this->assertTrue($user->fresh()->has_buyer_inquiry_notifications);
    }

    /**
     * @test
     */
    public function it_checks_if_an_user_has_unread_inquiry_new_inquiry_notifications()
    {
        // Create a notification this will setup the space as well...
        $notification = factory('App\ExchangeSpaceNotification')
            ->states('new-inquiry', 'unread')->create();

        // lets also make sure the exchange space is an inquiry...
        $notification->exchangeSpace->status = ExchangeSpaceStatusType::INQUIRY;
        $notification->exchangeSpace->save();

        // lets create the conversation so we can use it later...
        $conversation = factory('App\Conversation')->create([
            'exchange_space_id' => $notification->exchangeSpace->id,
        ]);

        // and finally lets log in the user since all checks are based off the the authenticated user.
        $user = $this->signInWithEvents($conversation->user);

        // Now we can check the notification.
        // If an unread new conversation notification exists $user->has_buyer_inquiry_notifications === true
        $this->assertTrue($user->fresh()->has_buyer_inquiry_notifications);
    }

    /**
     * @test
     */
    public function it_checks_if_an_user_has_unread_inquiry_message_and_new_inquiry_notifications()
    {
        // Create a conversation notification this will setup the space and conversation as well...
        $notification1 = factory('App\ConversationNotification')
            ->states('message', 'unread')->create();

        // lets store the conversation so we can use it later...
        $conversation = $notification1->conversation->fresh();

        // lets also make sure the exchange space is an inquiry...
        $conversation->space->status = ExchangeSpaceStatusType::INQUIRY;
        $conversation->space->save();

        // lets log in the user since all checks are based off the
        // the authenticated user.
        $user = $user = $this->signInWithEvents($conversation->user);

        // and finally lets create an exchange space notification.
        $notification2 = factory('App\ExchangeSpaceNotification')->states('new-inquiry', 'unread')->create([
            'user_id' => $user->id,
            'exchange_space_id' => $conversation->space->id,
        ]);

        // Now we can check the notifications.
        // If an unread new conversation notification and an unread message notification exists $user->has_buyer_inquiry_notifications === true
        $this->assertTrue($user->fresh()->has_buyer_inquiry_notifications);
    }

    /**
     * @test
     */
    public function it_checks_if_a_user_has_no_unread_inquiry_notifications()
    {
        // Create a conversation notification this will setup the space and conversation as well...
        $notification1 = factory('App\ConversationNotification')
            ->states('message', 'unread')->create();

        // lets store the conversation so we can use it later...
        $conversation = $notification1->conversation->fresh();

        // lets also make sure the exchange space is an inquiry...
        $conversation->space->status = ExchangeSpaceStatusType::INQUIRY;
        $conversation->space->save();

        // lets log in the user since all checks are based off the
        // the authenticated user.
        $user = $user = $this->signInWithEvents($conversation->user);

        // and finally lets create an exchange space new inquiry notification.
        $notification2 = factory('App\ExchangeSpaceNotification')->states('new-inquiry', 'unread')->create([
            'user_id' => $user->id,
            'exchange_space_id' => $conversation->space->id,
        ]);

        // Set both notifications to read.
        $notification1->read = true;
        $notification1->save();
        $notification2->read = true;
        $notification2->save();

        // If an unread new conversation notification and an unread message notification DOES NOT exist $user->has_buyer_inquiry_notifications === false
        $this->assertFalse($user->fresh()->has_buyer_inquiry_notifications);
    }

    /**
    * @test
    */
    public function it_clears_notifications_when_visiting_the_conversation_page()
    {
        $this->withoutExceptionHandling();
        // Create a conversation notification this will setup the space and conversation as well...
        $notification1 = factory('App\ConversationNotification')
            ->states('message', 'unread')->create();

        // lets store the conversation so we can use it later...
        $conversation = $notification1->conversation->fresh();

        // lets also make sure the exchange space is an inquiry...
        $conversation->space->status = ExchangeSpaceStatusType::INQUIRY;
        $conversation->space->save();

        // lets log in the user since all checks are based off the
        // the authenticated user.
        $user = $user = $this->signInWithEvents($conversation->user);

        // and finally lets create an exchange space new inquiry notification.
        $notification2 = factory('App\ExchangeSpaceNotification')->states('new-inquiry', 'unread')->create([
            'user_id' => $user->id,
            'exchange_space_id' => $conversation->space->id,
        ]);

        // Make sure the conversation has notifications
        $this->assertTrue($conversation->fresh()->has_notifications);

        // On /dashboard/buyer-inquiry/{id}
        $this->get(route('business-inquiry.show', ['id' => $conversation->space->id]));

        // Make sure the conversation has NO notifications
        $this->assertFalse($conversation->fresh()->has_notifications);
    }
}
