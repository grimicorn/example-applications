<?php

namespace Tests\Feature;

use App\Site;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignorefile
class SiteBaselinesControllerTest extends TestCase
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
    public function it_resets_all_site_page_baselines()
    {
        $site = create(Site::class, [
            'sitemap_url' => url('stubs/sitemaps/sitemap1.xml'),
        ]);
        $this->signIn($site->user);

        $site->updateSnapshots();
        $baselines = $site->baselineSnapshots()->get();

        $baselines->each(function ($baseline) {
            $this->assertTrue($baseline->is_baseline);
        });

        $this->patch(route('site.baselines.update', [
            'site' => $site,
        ]));

        $baselines->each(function ($baseline) {
            $this->assertNull($baseline->fresh());
        });

        $this->assertNotEmpty($site->baselineSnapshots()->get());
    }

    /**
    * @test
    */
    public function it_does_not_allow_non_site_page_owners_to_reset_baselines()
    {
        $site = create(Site::class, [
            'sitemap_url' => url('stubs/sitemaps/sitemap1.xml'),
        ]);
        $this->signIn(); // Sign in another user

        $site->updateSnapshots();
        $baselines = $site->baselineSnapshots()->get();

        $baselines->each(function ($baseline) {
            $this->assertTrue($baseline->is_baseline);
        });

        $this->patch(route('site.baselines.update', [
            'site' => $site,
        ]));

        $baselines->each(function ($baseline) {
            $baseline = $baseline->fresh();
            $this->assertNotNull($baseline);
            $this->assertTrue($baseline->is_baseline);
        });
    }
}
