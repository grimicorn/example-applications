<?php

namespace Tests\Feature;

use App\Site;
use App\SitePage;
use App\Snapshot;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\SnapshotConfiguration;

// phpcs:ignorefile
class SitePageBaselineControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_updates_a_site_pages_baseline_snapshot()
    {
        $site = create(Site::class, [
            'needs_review' => true,
        ]);
        $page = create(SitePage::class, [
            'needs_review' => true,
            'site_id' => $site->id,
        ]);
        $configuration = $page->snapshotConfigurations()->first();
        $currentBaseline = factory(Snapshot::class)->states('baseline')->create([
            'snapshot_configuration_id' => $configuration->id,
        ]);
        $newBaseline = create(Snapshot::class, [
            'snapshot_configuration_id' => $configuration->id,
        ]);
        $this->signIn($site->user);

        $this->assertTrue($currentBaseline->is_baseline);
        $this->assertFalse($newBaseline->is_baseline);

        $this->patch(route('site.pages.baseline.update', [
            'snapshot' => $newBaseline,
        ]));

        $this->assertNull($currentBaseline->fresh());
        $this->assertTrue($newBaseline->fresh()->is_baseline);
        $this->assertFalse($site->fresh()->needs_review);
        $this->assertFalse($page->fresh()->needs_review);
        $this->assertFalse($configuration->fresh()->needs_review);
    }

    /**
    * @test
    */
    public function it_does_not_allow_non_site_page_owners_to_update_the_baseline()
    {
        $page = create(SitePage::class);
        $configuration = $page->snapshotConfigurations()->first();
        $currentBaseline = factory(Snapshot::class)->states('baseline')->create([
            'snapshot_configuration_id' => $configuration->id,
        ]);
        $newBaseline = create(Snapshot::class, [
            'snapshot_configuration_id' => $configuration->id,
        ]);

        $this->assertTrue($currentBaseline->is_baseline);
        $this->assertFalse($newBaseline->is_baseline);

        $this->signIn(); // Sign in another user

        $this->patch(route('site.pages.baseline.update', [
            'snapshot' => $newBaseline,
        ]));

        $this->assertTrue($currentBaseline->fresh()->is_baseline);
        $this->assertFalse($newBaseline->fresh()->is_baseline);
    }
}
