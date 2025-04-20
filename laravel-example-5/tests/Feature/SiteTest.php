<?php

namespace Tests\Feature;

use App\Site;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ProcessSiteSnapshotUpdates;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignorefile
class SiteTest extends TestCase
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
    public function it_syncs_site_pages_when_a_sites_sitemap_url_is_created()
    {
        $site = create(Site::class, [
            'sitemap_url' => url('stubs/sitemaps/sitemap1.xml'),
        ]);

        $this->assertNotEmpty($site->pages);
        $this->assertEquals(
            $site->pages->pluck('url')->sort(),
            $this->sitemap1PageUrls()->sort()
        );
        $site->pages->pluck('site_id')
        ->each(function ($site_id) use ($site) {
            $this->assertEquals($site->id, $site_id);
        });
    }

    /**
    * @test
    */
    public function it_syncs_site_pages_when_a_sites_sitemap_url_is_updated()
    {
        $site = create(Site::class, [
            'sitemap_url' => url('stubs/sitemaps/sitemap1.xml'),
        ]);

        $site = $site->fresh();
        $this->assertNotEmpty($site->pages);
        $this->assertEquals(
            $this->sitemap1PageUrls()->sort(),
            $site->pages->pluck('url')->sort()
        );
        $site->pages->pluck('site_id')
        ->each(function ($site_id) use ($site) {
            $this->assertEquals($site->id, $site_id);
        });

        $site->sitemap_url = url('stubs/sitemaps/sitemap2.xml');
        $site->save();

        $site = $site->fresh();
        $this->assertNotEmpty($site->pages);
        $this->assertEquals(
            $this->sitemap2PageUrls()->sort(),
            $site->pages->pluck('url')->sort()
        );
        $site->pages->pluck('site_id')
        ->each(function ($site_id) use ($site) {
            $this->assertEquals($site->id, $site_id);
        });
    }

    /**
    * @test
    */
    public function it_removes_pages_when_a_sitemap_url_is_removed()
    {
        $site = create(Site::class, [
            'sitemap_url' => url('stubs/sitemaps/sitemap1.xml'),
        ]);

        $this->assertNotEmpty($site->pages);
        $this->assertEquals(
            $this->sitemap1PageUrls()->sort(),
            $site->pages->pluck('url')->sort()
        );
        $site->pages->pluck('site_id')
        ->each(function ($site_id) use ($site) {
            $this->assertEquals($site->id, $site_id);
        });

        $site->sitemap_url = null;
        $site->save();

        $this->assertEmpty($site->fresh()->pages);
    }

    /**
    * @test
    */
    public function it_updates_all_of_a_site_pages_snapshots()
    {
        $site = create(Site::class, [
            'sitemap_url' => url('stubs/sitemaps/sitemap1.xml'),
        ]);

        $configurations = $site->pages->map->snapshotConfigurations->flatten();
        $this->assertEmpty(
            $configurations->map->snapshots->flatten()
        );

        $site->updateSnapshots();

        $configurations = $configurations->map->fresh();

        $this->assertCount(
            $configurations->count(),
            $configurations->map->snapshots->flatten()
        );
    }

    /**
    * @test
    */
    public function it_resets_all_site_page_baselines()
    {
        $site = create(Site::class, [
            'sitemap_url' => url('stubs/sitemaps/sitemap3.xml'),
        ]);

        // Run it twice so we have a baseline set and a regular snapshot set
        $site->updateSnapshots();
        $site->updateSnapshots();

        $pages = $site->fresh()->pages;
        $originalBaseline = $site->baselineSnapshots;
        $originalNotBaseline = $site->notBaselineSnapshots;

        $site->resetBaselineSnapshots();

        $this->assertEmpty($originalBaseline->map->fresh()->filter());
        $this->assertEmpty($originalNotBaseline->map->fresh()->filter());

        $this->assertCount(
            $pages->count(),
            $site->fresh()->baselineSnapshots
        );
    }

    /**
    * @test
    */
    public function it_queues_each_site_page_snapshot()
    {
        $site = create(Site::class, [
            'sitemap_url' => url('stubs/sitemaps/sitemap1.xml'),
        ]);

        Queue::fake();

        $site->updateSnapshots();

        Queue::assertPushed(ProcessSiteSnapshotUpdates::class, 1);
    }

    /**
    * @test
    */
    public function it_syncs_site_pages_before_updating_screenshots()
    {
        $site = create(Site::class, [
            'sitemap_url' => url('stubs/sitemaps/sitemap1.xml'),
        ]);
        $firstUrl = $site->pages()->first()->url;
        $site->pages()->first()->delete(); // Simulate new url.
        $this->assertEmpty(
            $site->pages()->where('url', $firstUrl)->get()
        );

        $site->updateSnapshots();

        $this->assertNotEmpty(
            $site->pages()->where('url', $firstUrl)->get()
        );

        $this->assertNotEmpty(
            $site->pages()->where('url', $firstUrl)
                ->get()->map->screenshots->flatten()
        );
    }

    /**
    * @test
    */
    public function it_skips_updating_snapshots_for_ignored_pages()
    {
        $site = create(Site::class, [
            'sitemap_url' => url('stubs/sitemaps/sitemap1.xml'),
        ]);
        $site->pages()->first()->ignore();

        $site->updateSnapshots();

        $pages = $site->pages()->get();
        $page1 = $pages->shift();
        $this->assertEmpty(
            $page1->snapshotConfigurations
                ->first()
                ->snapshots()->get()
        );
        $pages->each(function ($page) {
            $snapshots = $page->snapshotConfigurations
                ->first()
                ->snapshots()->get();
            $this->assertNotEmpty($snapshots);
        });
    }

    /**
     * @test
     */
    public function it_deletes_all_of_a_sites_pages_when_deleting_a_site()
    {
        $site = create(Site::class);
        $pages = $site->pages;

        $this->assertNotEmpty($pages);

        $site->delete();

        $pages->each(function ($page) {
            $this->assertNull($page->fresh());
        });
    }
}
