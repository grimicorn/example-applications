<?php

namespace Tests\Feature\Application\Notification;

use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use App\Support\Notification\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_marks_exchange_space_notifications_as_read()
    {
        Event::fake();

        // Create a notification and sign in its user.
        $notification = factory('App\ExchangeSpaceNotification')->create([
            'read' => false,
        ]);
        $this->assertFalse($notification->read);
        $this->signInWithEvents($notification->user);

        // Set the notification to read.
        $response = $this->json('POST', route('notifications.update', ['id' => $notification->id]), [
            'read' => 1,
            'type' => $notification->type,
        ]);

        // Make sure
        $response->assertStatus(200);
        $this->assertTrue($notification->fresh()->read);
    }

    /**
    * @test
    */
    public function it_marks_conversation_notifications_as_read()
    {
        Event::fake();

        // Create a notification and sign in its user.
        $notification = factory('App\ConversationNotification')->create([
            'read' => false,
        ]);
        $this->assertFalse($notification->read);
        $this->signInWithEvents($notification->user);

        // Set the notification to read.
        $response = $this->json('POST', route('notifications.update', ['id' => $notification->id]), [
            'read' => 1,
            'type' => $notification->type,
        ]);

        // Make sure
        $response->assertStatus(200);
        $this->assertTrue($notification->fresh()->read);
    }

    /**
    * @test
    */
    public function it_marks_saved_search_notifications_as_read()
    {
        Event::fake();

        // Create a notification and sign in its user.
        $notification = factory('App\SavedSearchNotification')->create([
            'read' => false,
        ]);
        $this->assertFalse($notification->read);
        $this->signInWithEvents($notification->user);

        // Set the notification to read.
        $response = $this->json('POST', route('notifications.update', ['id' => $notification->id]), [
            'read' => 1,
            'type' => $notification->type,
        ]);

        // Make sure
        $response->assertStatus(200);
        $this->assertTrue($notification->fresh()->read);
    }

    /**
    * @test
    */
    public function it_deletes_exchange_space_notifications()
    {
        // Create a notification and sign in its user.
        $notification = factory('App\ExchangeSpaceNotification')->create();
        $this->signInWithEvents($notification->user);
        $this->assertDatabaseHas('exchange_space_notifications', ['id' => $notification->id]);

        // Delete the notification
        $response = $this->json('DELETE', route('notifications.destroy', ['id' => $notification->id]), [
            'type' => $notification->type,
        ]);

        // Make sure the notifications was deleted
        $response->assertStatus(200);
        $this->assertDatabaseMissing('exchange_space_notifications', ['id' => $notification->id]);
    }

    /**
    * @test
    */
    public function it_deletes_conversation_notifications()
    {
        // Create a notification and sign in its user.
        $notification = factory('App\ConversationNotification')->create();
        $this->signInWithEvents($notification->user);
        $this->assertDatabaseHas('conversation_notifications', ['id' => $notification->id]);

        // Delete the notification
        $response = $this->json('DELETE', route('notifications.destroy', ['id' => $notification->id]), [
            'type' => $notification->type,
        ]);

        // Make sure the notifications was deleted
        $response->assertStatus(200);
        $this->assertDatabaseMissing('conversation_notifications', ['id' => $notification->id]);
    }

    /**
    * @test
    */
    public function it_deletes_saved_search_notifications()
    {
        // Create a notification and sign in its user.
        $notification = factory('App\SavedSearchNotification')->create();
        $this->signInWithEvents($notification->user);
        $this->assertDatabaseHas('saved_search_notifications', ['id' => $notification->id]);

        // Delete the notification
        $response = $this->json('DELETE', route('notifications.destroy', ['id' => $notification->id]), [
            'type' => $notification->type,
        ]);

        // Make sure the notifications was deleted
        $response->assertStatus(200);
        $this->assertDatabaseMissing('saved_search_notifications', ['id' => $notification->id]);
    }
}
