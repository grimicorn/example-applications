<?php

namespace Tests\Feature;

use App\SitePage;
use App\Snapshot;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\SnapshotConfiguration;

// phpcs:ignorefile
class SitePageTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_converts_the_page_url_to_path_attribute()
    {
        $page = create(SitePage::class, [
            'url' => 'http://example.com/path/to/somewhere/',
        ]);

        $this->assertEquals($path = '/path/to/somewhere/', $page->path);
        $this->assertEquals($path, $page->toArray()['path']);
    }

    /**
     * @test
     */
    public function it_deletes_all_snapshots_when_deleting_a_site_page()
    {
        $page = create(SitePage::class);
        $configuration = $page->snapshotConfigurations()->first();
        $configurations = $page->snapshotConfigurations()->get();
        $this->assertNotEmpty($configurations);

        $page->delete();

        $configurations->each(function ($item) {
            $this->assertNull($item->fresh());
        });
    }

    /**
    * @test
    */
    public function it_creates_a_snapshot_configuration_with_default_values_when_a_site_page_is_created()
    {
        $page = create(SitePage::class);
        $configuration = $page->snapshotConfigurations()->first();
        $this->assertNotNull($configuration);
        $this->assertEquals($configuration->width, 1400);
    }
}
