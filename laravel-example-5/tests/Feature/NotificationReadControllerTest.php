<?php

namespace Tests\Feature;

use App\Site;
use Tests\TestCase;
use App\Notifications\SiteNeedsReview;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignoreFile
class NotificationReadControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_marks_a_notification_as_read()
    {
        $site = create(Site::class, ['needs_review' => true]);
        $this->signIn($site->user);
        $site->user->notify(new SiteNeedsReview($site));
        $notification = $site->user->notifications()->first();
        $this->assertNull($notification->read_at);

        $this->patch(route('notifications.read.update', [
            'notification' => $notification->id,
        ]))
        ->assertLocation($notification->link);

        $this->assertNotNull($notification->fresh()->read_at);
    }

    /**
    * @test
    */
    public function it_only_allows_notification_owners_to_mark_as_read()
    {
        $site = create(Site::class, ['needs_review' => true]);
        $this->signIn();
        $site->user->notify(new SiteNeedsReview($site));
        $notification = $site->user->notifications()->first();
        $this->assertNull($notification->read_at);

        $this->patch(route('notifications.read.update', [
            'notification' => $notification->id,
        ]))
        ->assertStatus(403);

        $this->assertNull($notification->fresh()->read_at);
    }

    /**
    * @test
    */
    public function it_marks_a_notification_as_unread()
    {
        $site = create(Site::class, ['needs_review' => true]);
        $this->signIn($site->user);
        $site->user->notify(new SiteNeedsReview($site));
        $notification = $site->user->notifications()->first();
        $notification->markAsRead();
        $this->assertNotNull($notification->read_at);

        $this->delete(route('notifications.read.destroy', [
            'notification' => $notification->id,
        ]))
        ->assertLocation($notification->link);

        $this->assertNull($notification->fresh()->read_at);
    }

    /**
    * @test
    */
    public function it_only_allows_notification_owners_to_mark_as_unread()
    {
        $site = create(Site::class, ['needs_review' => true]);
        $this->signIn();
        $site->user->notify(new SiteNeedsReview($site));
        $notification = $site->user->notifications()->first();
        $notification->markAsRead();
        $this->assertNotNull($original = $notification->read_at);

        $this->delete(route('notifications.read.destroy', [
            'notification' => $notification->id,
        ]))
        ->assertStatus(403);

        $this->assertEquals($original, $notification->fresh()->read_at);
    }
}
