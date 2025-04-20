<?php

namespace Tests\Feature\Application\Notification;

use Tests\TestCase;
use App\Support\Notification\Notification;
use App\Support\Notification\TestNotification;
use App\Support\Notification\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    * @group failing
    */
    public function it_selects_correct_view_for_type()
    {
        $this->signInWithEvents();

        NotificationType::getConstants()->flip()->map(function ($value) {
            return str_slug($value);
        })->each(function ($slug, $type) {
            $viewSlug = (new TestNotification($type))->getViewSlugFromType();

            // The deleted Exchange Space  slug is handled at the notification level.
            if ($viewSlug === 'deleted-exchange-space') {
                return;
            }
            $path = "views/app/sections/notifications/email/{$viewSlug}.blade.php";
            $this->assertEquals($slug, $viewSlug);
            $this->assertFileExists(resource_path($path));
        });
    }
}
