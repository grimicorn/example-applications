<?php

namespace Tests\Feature;

use App\Site;
use Tests\TestCase;
use App\Notifications\SiteNeedsReview;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignorefile
class SiteSnapshotsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->copySitemapStubs();
    }


    public function tearDown()
    {
        $this->clearSitemapStubs();

        parent::tearDown();
    }

    /**
    * @test
    */
    public function a_user_is_notified_when_the_difference_is_greater_than_the_page_difference_threshold()
    {
        $this->withoutExceptionHandling();
        $site = create(Site::class, [
            'sitemap_url' => url('stubs/sitemaps/sitemap3.xml'),
        ]);
        $site->syncPages();
        $this->signIn($site->user);

        $site->fresh()->pages->each(function ($page) {
            $page->difference_threshold = 1;
            $page->save();
        });

        Notification::fake();

        $this->patch(
            route('site.baselines.update', ['site' => $site])
        );
        $this->patch(
            route('site.snapshots.update', ['site' => $site])
        );

        $this->assertTrue($site->fresh()->needs_review);

        $site->pages->each(function ($page) {
            $page = $page->fresh();
            $this->assertTrue($page->needs_review);

            Notification::assertSentTo(
                $page->site->user,
                SiteNeedsReview::class,
                function ($notification, $channels) use ($page) {
                    return $notification->site->id === $page->site->id;
                }
            );
        });
    }

    /**
    * @test
    */
    public function a_user_is_not_notified_when_the_difference_is_less_than_the_page_difference_threshold()
    {
        $site = create(Site::class, [
            'sitemap_url' => url('stubs/sitemaps/sitemap3.xml'),
        ]);
        $site->syncPages();
        $this->signIn($site->user);

        $site->fresh()->pages->each(function ($page) {
            $page->difference_threshold = 0;
            $page->save();
        });

        Notification::fake();

        $this->patch(
            route('site.baselines.update', ['site' => $site])
        );
        $this->patch(
            route('site.snapshots.update', ['site' => $site])
        );

        $this->assertFalse($site->fresh()->needs_review);

        $site->pages->each(function ($page) {
            $page = $page->fresh();
            $this->assertFalse($page->needs_review);

            Notification::assertNotSentTo(
                $page->site->user,
                SiteNeedsReview::class,
                function ($notification, $channels) use ($page) {
                    return $notification->site->id === $page->site->id;
                }
            );
        });
    }
}
