<?php

namespace Tests\Feature;

use App\Site;
use Tests\TestCase;
use App\Notifications\SiteNeedsReview;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignoreFile
class NotificationsControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_deletes_a_notification()
    {
        $site = create(Site::class, ['needs_review' => true]);
        $this->signIn($site->user);
        $site->user->notify(new SiteNeedsReview($site));
        $notification = $site->user->notifications()->first();
        $this->assertNotNull($notification);

        $this->delete(route('notifications.destroy', ['notification' => $notification]));

        $this->assertNull($notification->fresh());
    }

    /**
    * @test
    */
    public function it_only_allows_notification_owners_to_delete_a_notification()
    {
        $site = create(Site::class, ['needs_review' => true]);
        $this->signIn();
        $site->user->notify(new SiteNeedsReview($site));
        $notification = $site->user->notifications()->first();
        $this->assertNotNull($notification);

        $this->delete(route('notifications.destroy', ['notification' => $notification]))
            ->assertStatus(403);

        $this->assertNotNull($notification->fresh());
    }
}
