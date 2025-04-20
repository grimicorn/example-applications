<?php

namespace Tests\Feature\Application\Notification;

use Tests\TestCase;
use App\Mail\NewNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Tests\Support\HasExchangeSpaceCreators;
use Tests\Support\HasNotificationTestHelpers;
use App\Support\Notification\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Support\Notification\DueDiligenceDigestNotification;

// @codingStandardsIgnoreFile
class ConversationNotificationTest extends TestCase
{
    use RefreshDatabase;
    use HasExchangeSpaceCreators;
    use HasNotificationTestHelpers;

    /**
    * @test
    */
    public function it_sends_a_conversation_notification_for_an_exchange_space_if_a_user_has_requested_it()
    {
        // Create the conversation
        $seller = $this->signInWithEvents();
        $buyer = factory('App\User')->create();
        $conversation = $this->createSpaceConversation([], $seller, $buyer);
        $space = $conversation->space;

        // Set all members to be active and approved.
        $conversation->space->allMembers->each(function ($member) {
            $member->approved = true;
            $member->active = true;
            $member->save();

            $member->user->emailNotificationSettings->enable_all = false;
            $member->user->emailNotificationSettings->enable_due_diligence = true;
            $member->user->emailNotificationSettings->due_diligence_digest = false;
            $member->user->emailNotificationSettings->save();
        });

        // Start the mail fake
        Mail::fake();

        // Add a message
        $this->addMessageToConversation($conversation, ['user_id' => $seller->id]);

        // Make sure the emails where sent correctly.
        $this->assertNotificationCount(1, NotificationType::MESSAGE);

        // Make sure the notification was stored.
        $this->assertDatabaseHas('conversation_notifications', [
            'user_id' => $buyer->id,
            'conversation_id' => $conversation->id,
            'type' => NotificationType::MESSAGE,
            'message_sender_name' => $seller->name,
        ]);

        // Allows us to really get the latest post.
        sleep(1);

        // Add a conversation
        $this->withoutExceptionHandling();
        $exampleConvo = factory('App\Conversation')->make();
        $this->post(route('exchange-spaces.conversations.store', [
            'id' => $space->id,
            'category' => $exampleConvo->category,
            'body' => 'Body',
            'title' => $exampleConvo->title,
        ]));
        $newConversation = $space->fresh()->conversations()->latest()->take(1)->first();

        // Make sure the email was not sent
        $this->assertNotificationCount(1, NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION);

        // Make sure the notification was stored.
        $this->assertDatabaseHas('conversation_notifications', [
            'user_id' => $buyer->id,
            'conversation_id' => $newConversation->id,
            'type' => NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION,
        ]);
    }

    /**
    * @test
    */
    public function it_does_not_send_a_conversation_notification_for_an_exchange_space_if_a_user_has_disabled_it()
    {
        // Create the conversation
        $seller = $this->signInWithEvents();
        $buyer = factory('App\User')->create();
        $conversation = $this->createSpaceConversation([], $seller, $buyer);
        $space = $conversation->space;

        // Set all members to be active and approved.
        $conversation->space->allMembers->each(function ($member) {
            $member->approved = true;
            $member->active = true;
            $member->save();

            // Disable the users notifications.
            $member->user->emailNotificationSettings->enable_all = false;
            $member->user->emailNotificationSettings->enable_due_diligence= false;
            $member->user->emailNotificationSettings->due_diligence_digest = false;
            $member->user->emailNotificationSettings->save();
        });

        // Start the mail fake
        Mail::fake();

        // Add a message
        $this->addMessageToConversation($conversation, [
            'user_id' => $seller->id,
        ]);

        // Make sure the emails was not sent.
        $this->assertNotificationCount(0, NotificationType::NEW_BUYER_INQUIRY);

        // Make sure the notification was still stored.
        $this->assertDatabaseHas('conversation_notifications', [
            'user_id' => $buyer->id,
            'conversation_id' => $conversation->id,
            'type' => NotificationType::MESSAGE,
            'message_sender_name' => $seller->name,
        ]);

        // Add a conversation
        $newConversation = $this->addConversationToSpace($space);

        // Make sure the email was not sent
        $this->assertNotificationCount(0, NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION);

        // Make sure the notification was still stored.
        $this->assertDatabaseHas('conversation_notifications', [
            'user_id' => $buyer->id,
            'conversation_id' => $newConversation->id,
            'type' => NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION,
        ]);
    }

    /**
    * @test
    */
    public function it_sends_notifications_for_new_exchange_space_conversations()
    {
        // Create the conversation
        $seller = $this->signInWithEvents();
        $buyer = factory('App\User')->create();

        // Create the base conversation
        $conversation = $this->createSpaceConversation([], $seller, $buyer);

        // Set all members to be active and approved.
        $conversation->space->allMembers->each(function ($member) {
            $member->approved = true;
            $member->active = true;
            $member->save();

            $member->user->emailNotificationSettings->enable_all = true;
            $member->user->emailNotificationSettings->enable_due_diligence = true;
            $member->user->emailNotificationSettings->due_diligence_digest = false;
            $member->user->emailNotificationSettings->save();
        });

        // Start the mail fake
        Mail::fake();

        // Add a new conversation
        $conversation2 = $this->addConversationToSpace($conversation->space, []);

        // Make sure the emails where sent correctly.
        $this->assertNotificationCount(1, NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION);
        $this->assertNotificationCount(0, NotificationType::MESSAGE);

        // Make sure the notification was stored.
        $this->assertDatabaseHas('conversation_notifications', [
            'user_id' => $buyer->id,
            'conversation_id' => $conversation2->id,
            'type' => NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION,
            'message_sender_name' => $seller->name,
        ]);
    }

    /**
    * @test
    * @group failing
    */
    public function it_does_not_send_notifications_for_new_buyer_inquiry_conversations()
    {
        // Create the conversation
        $seller = $this->signInWithEvents();
        $buyer = factory('App\User')->create();

        // Create the base conversation
        $conversation = $this->createInquiryConversation([], $seller, $buyer);

        // Set all members to be active and approved.
        $conversation->space->allMembers->each(function ($member) {
            $member->approved = true;
            $member->active = true;
            $member->save();

            $member->user->emailNotificationSettings->enable_all = true;
            $member->user->emailNotificationSettings->enable_due_diligence = true;
            $member->user->emailNotificationSettings->due_diligence_digest = false;

            $member->user->emailNotificationSettings->save();
        });

        // Start the mail fake
        Mail::fake();

        // Add a new conversation
        $this->addConversationToSpace($conversation->space, []);

        // Make sure the emails where sent correctly.
        Mail::assertNotSent(NewNotification::class, 1);

        // Make sure the notification was stored.
        $this->assertDatabaseMissing('conversation_notifications', [
            'user_id' => $buyer->id,
            'conversation_id' => $conversation->id,
            'type' => NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION,
            'message_sender_name' => $seller->name,
        ]);
    }

    /**
    * @test
    */
    public function it_does_not_send_the_email_on_each_update_if_due_diligence_digest_is_enabled()
    {
        // Create the conversation
        $seller = $this->signInWithEvents();
        $buyer = factory('App\User')->create();
        $conversation = $this->createSpaceConversation([], $seller, $buyer);
        $space = $conversation->space;

        // Set all members to be active and approved.
        $conversation->space->allMembers->each(function ($member) {
            $member->activate();

            $member->user->emailNotificationSettings->enable_all = false;
            $member->user->emailNotificationSettings->enable_due_diligence = true;
            $member->user->emailNotificationSettings->due_diligence_digest = true;
            $member->user->emailNotificationSettings->save();
        });

        // Start the mail fake
        Mail::fake();

        // Add a message
        $this->addMessageToConversation($conversation, ['user_id' => $seller->id]);

        // Make sure the emails where sent correctly.
        $this->assertNotificationCount(0, NotificationType::MESSAGE);

        // Make sure the notification was stored.
        $this->assertDatabaseHas('conversation_notifications', [
            'user_id' => $buyer->id,
            'conversation_id' => $conversation->id,
            'type' => NotificationType::MESSAGE,
            'message_sender_name' => $seller->name,
        ]);

        // Add a conversation
        $newConversation = $this->addConversationToSpace($space);

        // Make sure the email was not sent
        $this->assertNotificationCount(0, NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION);

        // Make sure the notification was stored.
        $this->assertDatabaseHas('conversation_notifications', [
            'user_id' => $buyer->id,
            'conversation_id' => $newConversation->id,
            'type' => NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION,
        ]);
    }

    /**
    * @test
    */
    public function it_sends_the_due_diligence_digest_email()
    {
        // Create the conversation
        $seller = $this->signInWithEvents();
        $buyer = factory('App\User')->create();
        $conversation = $this->createSpaceConversation([], $seller, $buyer);
        $space = $conversation->space;

        // Set all members to be active and approved.
        $conversation->space->allMembers->each(function ($member) {
            $member->activate();

            $member->user->emailNotificationSettings->enable_all = true;
            $member->user->emailNotificationSettings->enable_due_diligence = true;
            $member->user->emailNotificationSettings->due_diligence_digest = true;
            $member->user->emailNotificationSettings->save();
        });

        // Now disable the 1st users dilligence center conversations to make sure we are not just sending them to everyone.
        $conversation->space->allMembers->first()->user->emailNotificationSettings->due_diligence_digest = false;
        $conversation->space->allMembers->first()->user->emailNotificationSettings->save();

        // Start the mail fake
        Mail::fake();

        // Add a message to conversation
        $oldConversation = $this->addMessageToConversation($conversation, ['user_id' => $seller->id]);
        $this->assertNotificationCount(0, NotificationType::MESSAGE);

        // Add a conversation
        $newConversation = $this->addConversationToSpace($space);
        $this->assertNotificationCount(0, NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION);

        // Add an "old" conversation
        factory('App\Conversation')->create([
            'updated_at' => now()->subDays(2),
            'created_at' => now()->subDays(10),
            'exchange_space_id' => $space->id,
        ]);

        // Send the digest.
        Artisan::call('fe:send-due-diligence-digest');
        $this->assertNotificationCount($conversation->space->allMembers->count() - 1, NotificationType::DUE_DILIGENCE_DIGEST);
    }

    /**
    * @test
    */
    public function it_does_not_send_when_there_are_no_updates()
    {
        // Create the conversation
        $seller = $this->signInWithEvents();
        $buyer = factory('App\User')->create();
        $conversation1 = $this->createSpaceConversation([], $seller, $buyer);
        $space = $conversation1->space;

        // Set all members to be active and approved.
        $conversation1->space->allMembers->each(function ($member) {
            $member->activate();

            $member->user->emailNotificationSettings->enable_all = true;
            $member->user->emailNotificationSettings->enable_due_diligence = true;
            $member->user->emailNotificationSettings->due_diligence_digest = true;
            $member->user->emailNotificationSettings->save();
        });

        // Now disable the 1st users dilligence center conversation1s to make sure we are not just sending them to everyone.
        $conversation1->space->allMembers->first()->user->emailNotificationSettings->due_diligence_digest = false;
        $conversation1->space->allMembers->first()->user->emailNotificationSettings->save();

        // Start the mail fake
        Mail::fake();

        // Add a message from the seller and buyer to conversation 1
        $this->addMessageToConversation($conversation1, ['user_id' => $buyer->id]);
        $this->addMessageToConversation($conversation1, ['user_id' => $seller->id]);
        $this->assertNotificationCount(0, NotificationType::MESSAGE);

        // Add another conversation with a message from the seller and buyer
        $conversation2 = $this->addConversationToSpace($space);
        $this->addMessageToConversation($conversation2, ['user_id' => $buyer->id]);
        $this->addMessageToConversation($conversation2, ['user_id' => $seller->id]);
        $this->assertNotificationCount(0, NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION);

        // Add an "old" conversation
        factory('App\Conversation')->create([
            'updated_at' => now()->subDays(2),
            'created_at' => now()->subDays(10),
            'exchange_space_id' => $space->id
        ]);

        // Set all the conversations to be more than 24 hrs in the past
        $space->conversations()->get()->each(function ($conversation) {
            $conversation->updated_at = now()->subDays(2);
            $conversation->created_at = now()->subDays(10);
            $conversation->save();
        });

        // Send the digest.
        Artisan::call('fe:send-due-diligence-digest');
        $this->assertNotificationCount(0, NotificationType::DUE_DILIGENCE_DIGEST);
    }

    /**
    * @test
    */
    public function it_only_sends_conversations_with_new_messages()
    {
        // Create the conversation
        $seller = $this->signInWithEvents();
        $buyer = factory('App\User')->create();
        $conversation1 = $this->createSpaceConversation([], $seller, $buyer); //  (Conversation 1)
        $space = $conversation1->space;

        // Set all members to be active and approved.
        $conversation1->space->allMembers->each(function ($member) {
            $member->activate();

            $member->user->emailNotificationSettings->enable_all = true;
            $member->user->emailNotificationSettings->enable_due_diligence = true;
            $member->user->emailNotificationSettings->due_diligence_digest = true;
            $member->user->emailNotificationSettings->save();
        });

        // Now disable the 1st users dilligence center conversations to make sure we are not just sending them to everyone.
        $conversation1->space->allMembers->first()->user->emailNotificationSettings->due_diligence_digest = false;
        $conversation1->space->allMembers->first()->user->emailNotificationSettings->save();

        // Start the mail fake
        Mail::fake();

        // Add a message to conversation
        $oldConversation = $this->addMessageToConversation($conversation1, ['user_id' => $seller->id]);
        $this->assertNotificationCount(0, NotificationType::MESSAGE);

        // Add a conversation (Conversation 2)
        $this->addConversationToSpace($space);
        $this->assertNotificationCount(0, NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION);

        // Add an "old" conversation (Conversation 3)
        factory('App\Conversation')->create([
            'updated_at' => now()->subDays(2),
            'created_at' => now()->subDays(10),
            'exchange_space_id' => $space->id,
        ]);

        // 3 total conversations with one being "old"
        $this->assertCount(2, (new DueDiligenceDigestNotification($seller))->getConversations());
    }

    /**
     * @test
     */
    public function it_only_sends_conversations_with_new_messages_where_the_user_was_not_the_creator()
    {
        // Create the conversation
        $seller = $this->signInWithEvents();
        $buyer = factory('App\User')->create();
        $conversation1 = $this->createSpaceConversation([], $seller, $buyer); //  (Conversation 1)
        $space = $conversation1->space;

        // Set all members to be active and approved.
        $conversation1->space->allMembers->each(function ($member) {
            $member->activate();

            $member->user->emailNotificationSettings->enable_all = true;
            $member->user->emailNotificationSettings->enable_due_diligence = true;
            $member->user->emailNotificationSettings->due_diligence_digest = true;
            $member->user->emailNotificationSettings->save();
        });

        // Now disable the 1st users dilligence center conversations to make sure we are not just sending them to everyone.
        $conversation1->space->allMembers->first()->user->emailNotificationSettings->due_diligence_digest = false;
        $conversation1->space->allMembers->first()->user->emailNotificationSettings->save();

        // Start the mail fake
        Mail::fake();

        // Add a message to conversation
        $this->addMessageToConversation($conversation1, ['user_id' => $seller->id]);
        $this->assertNotificationCount(0, NotificationType::MESSAGE);

        // Add a conversation (Conversation 2)
        $conversation2 = $this->addConversationToSpace($space);
        $this->assertNotificationCount(0, NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION);
        $this->addMessageToConversation($conversation2, ['user_id' => $seller->id]);
        $this->assertNotificationCount(0, NotificationType::MESSAGE);

        // Add a message from the "buyer"
        $this->addMessageToConversation($conversation2, ['user_id' => $buyer->id]);

        // 2 total conversations now exist.
        // We should only get the one back that the buyer posted a message to
        $this->assertCount(1, (new DueDiligenceDigestNotification($seller))->getConversations());
    }
}
