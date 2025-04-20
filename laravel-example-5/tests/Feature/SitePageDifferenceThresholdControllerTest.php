<?php

namespace Tests\Feature;

use App\Site;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\SitePage;

// phpcs:ignorefile
class SitePageDifferenceThresholdControllerTest extends TestCase
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
    public function it_allows_site_page_owners_to_set_a_site_pages_difference_threshold()
    {
        $site = create(Site::class, ['difference_threshold' => .1]);
        $page = $site->pages()->first();
        $this->signIn($site->user);
        $this->assertEquals(
            $site->difference_threshold,
            $page->difference_threshold
        );

        $this->patch(
            route('site.pages.threshold.update', ['id' => $page->id]),
            ['difference_threshold' => $newThreshold = .07]
        )->assertSessionHas('status');

        $this->assertEquals($newThreshold, $page->fresh()->difference_threshold);
    }


    /**
    * @test
    */
    public function it_validates_threshold_when_updating_difference_threshold()
    {
        $site = create(Site::class, ['difference_threshold' => .1, 'needs_review' => true]);
        $page = $site->pages()->first();
        $page->needs_review = true;
        $page->save();
        $this->signIn($site->user);
        $this->assertTrue($site->needs_review);
        $this->assertTrue($page->needs_review);

        $this->patch(
            route('site.pages.threshold.update', ['id' => $page->id]),
            ['difference_threshold' => $newThreshold = .0]
        )->assertSessionHas('status');

        $this->assertFalse($site->fresh()->needs_review);
        $this->assertFalse($page->fresh()->needs_review);
    }

    /**
    * @test
    */
    public function it_does_not_allow_non_site_page_owners_to_set_a_site_pages_difference_threshold()
    {
        $this->signIn();
        $site = create(Site::class, ['difference_threshold' => .1]);
        $page = $original = $site->pages()->first();
        $this->assertEquals(
            $site->difference_threshold,
            $page->difference_threshold
        );

        $this->patch(
            route('site.pages.threshold.update', ['id' => $page->id]),
            ['difference_threshold' => $newThreshold = .07]
        )->assertStatus(403);

        $this->assertEquals(
            $original->difference_threshold,
            $page->fresh()->difference_threshold
        );
    }

    /**
    * @test
    */
    public function it_defaults_a_site_page_difference_threshold_to_its_parent_if_null()
    {
        $site = create(Site::class, ['difference_threshold' => .1]);
        $page = create(SitePage::class, [
            'difference_threshold' => null,
            'site_id' => $site->id,
        ]);
        $this->assertEquals(
            $site->difference_threshold,
            $page->difference_threshold
        );
    }
}
