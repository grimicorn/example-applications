<?php

namespace Tests\Feature;

use App\Site;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignorefile
class SitePageIgnoreControllerTest extends TestCase
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
    public function it_allows_site_page_owners_to_ignore_pages()
    {
        $site = create(Site::class);
        $this->signIn($site->user);
        $page = $site->pages()->first();
        $this->assertFalse($page->ignored);

        $this->patch(
            route('site.pages.ignore.update', ['page' => $page])
        )->assertSessionHas('status');

        $this->assertTrue($page->fresh()->ignored);
    }

    /**
    * @test
    */
    public function it_does_not_allow_non_site_page_owners_to_ignore_pages()
    {
        $this->signIn();
        $site = create(Site::class);
        $page = $site->pages()->first();
        $this->assertFalse($page->ignored);

        $this->patch(
            route('site.pages.ignore.update', ['page' => $page])
        )->assertStatus(403);
    }

    /**
    * @test
    */
    public function it_allows_site_page_owners_to_remove_a_pages_ignore()
    {
        $site = create(Site::class);
        $this->signIn($site->user);
        $page = $site->pages()->first();
        $page->ignored = true;
        $page->save();
        $this->assertTrue($page->fresh()->ignored);

        $this->delete(
            route('site.pages.ignore.destroy', ['page' => $page])
        )->with('status');

        $this->assertFalse($page->fresh()->ignored);
    }

    /**
    * @test
    */
    public function it_does_not_allow_non_site_page_owners_to_remove_a_pages_ignore()
    {
        $this->signIn();
        $site = create(Site::class);
        $page = $site->pages()->first();
        $page->ignored = true;
        $page->save();
        $this->assertTrue($page->fresh()->ignored);

        $this->delete(
            route('site.pages.ignore.destroy', ['page' => $page])
        )->assertStatus(403);
    }
}
