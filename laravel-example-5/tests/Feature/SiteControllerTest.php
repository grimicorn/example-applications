<?php

namespace Tests\Feature;

use App\Site;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignorefile
class SiteControllerTest extends TestCase
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
    public function it_allows_an_authenticated_user_to_create_a_site()
    {
        $user = $this->signIn();

        $this->assertEmpty(Site::where($attributes = [
            'name' => 'Test Site',
            'sitemap_url' => url('stubs/sitemaps/sitemap-index.xml'),
            'difference_threshold' => .09,
        ])->get());

        $this->post(route('sites.store'), $attributes)
             ->assertSessionHas('status')
             ->assertRedirect(route('sites.show', ['site' => Site::latest()->first()]));

        $attributes['user_id'] = $user->id;
        $attributes['difference_threshold'] = .09;
        $this->assertNotEmpty(Site::where($attributes)->get());
    }

    /**
    * @test
    */
    public function it_converts_the_difference_threshold_to_be_in_between_0_and_1()
    {
        $user = $this->signIn();

        $this->assertEmpty(Site::where($attributes = [
            'name' => 'Test Site',
            'sitemap_url' => url('stubs/sitemaps/sitemap-index.xml'),
            'difference_threshold' => 9,
        ])->get());

        $this->post(route('sites.store'), $attributes)
             ->assertSessionHas('status');

        $attributes['user_id'] = $user->id;
        $attributes['difference_threshold'] = .09;
        $this->assertNotEmpty(Site::where($attributes)->get());
    }

    /**
    * @test
    */
    public function it_does_not_allow_a_difference_threshold_greater_than_1()
    {
        $user = $this->signIn();

        $this->assertEmpty(Site::where($attributes = [
            'name' => 'Test Site',
            'sitemap_url' => url('stubs/sitemaps/sitemap-index.xml'),
            'difference_threshold' => 900, // When converted $t/100 it would become 9.
        ])->get());

        $this->post(route('sites.store'), $attributes)
             ->assertSessionHas('status');

        $attributes['user_id'] = $user->id;
        $attributes['difference_threshold'] = 1;
        $this->assertNotEmpty(Site::where($attributes)->get());
    }

    /**
    * @test
    */
    public function it_sets_a_difference_threshold_default()
    {
        $user = $this->signIn();

        $this->assertEmpty(Site::where($attributes = [
            'name' => 'Test Site',
            'sitemap_url' => url('stubs/sitemaps/sitemap-index.xml'),
            'difference_threshold' => .95,
        ])->get());

        $this->post(route('sites.store'), $attributes)
             ->assertSessionHas('status');

        $attributes['user_id'] = $user->id;
        $this->assertNotEmpty(Site::where($attributes)->get());
    }

    /**
    * @test
    */
    public function it_allows_a_user_to_update_their_site()
    {
        $user = $this->signIn();
        $site = create(Site::class, $attributes = [
            'name' => 'Test Site',
            'sitemap_url' => url('stubs/sitemaps/sitemap-index.xml'),
            'user_id' => $user->id,
            'difference_threshold' => .1,
        ]);

        $newUrl = url('stubs/sitemaps/sitemap-index2.xml');
        $newName = 'Renamed Test Site';
        $this->patch(route('sites.update', ['site' => $site]), [
            'name' => $newName,
            'sitemap_url' => $newUrl,
            'difference_threshold' => 7,
        ])
        ->assertSessionHas('status');

        $site = $site->fresh();
        $this->assertEquals($site->name, $newName);
        $this->assertEquals($site->sitemap_url, $newUrl);
        $this->assertEquals(.07, $site->difference_threshold);
    }

    /**
    * @test
    */
    public function it_guards_non_site_owners_from_updating_a_site()
    {
        $user = $this->signIn();

        // Does not belong to authenticated user.
        $site = $original = create(Site::class);

        $this->patch(route('sites.update', ['site' => $site]), [
            'name' => 'New Name',
            'sitemap_url' => 'broken-url',
        ])
        ->assertStatus(403);

        $site = $site->fresh();
        $this->assertEquals($site->name, $original->name);
        $this->assertEquals($site->sitemap_url, $original->sitemap_url);
    }

    /**
    * @test
    */
    public function it_allows_a_user_to_delete_their_site()
    {
        $user = $this->signIn();
        $site = create(Site::class, $attributes = [
            'user_id' => $user->id,
        ]);

        $this->delete(route('sites.destroy', ['site' => $site]))
             ->assertSessionHas('status');

        $this->assertNull($site->fresh());
    }

    /**
    * @test
    */
    public function it_guards_non_site_owners_from_deleting_a_site()
    {
        $user = $this->signIn();
        $site = create(Site::class);

        $this->delete(route('sites.destroy', ['site' => $site]))
             ->assertStatus(403);

        $this->assertNotNull($site->fresh());
    }

    /**
    * @test
    */
    public function it_allows_site_owners_to_view_a_site()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();
        $site = create(Site::class, [
            'user_id' => $user->id,
        ]);

        $this->get(route('sites.show', ['site' => $site]))
             ->assertStatus(200);
    }

    /**
    * @test
    */
    public function it_guards_non_site_owners_from_viewing_a_site()
    {
        $user = $this->signIn();
        $site = create(Site::class);

        $this->get(route('sites.show', ['site' => $site]))
             ->assertStatus(403);
    }

    /**
    * @test
    */
    public function it_allows_site_owners_to_view_a_sites_edit_page()
    {
        $user = $this->signIn();
        $site = create(Site::class, [
            'user_id' => $user->id,
        ]);

        $this->get(route('sites.edit', ['site' => $site]))
             ->assertStatus(200);
    }

    /**
    * @test
    */
    public function it_guards_non_site_owners_from_viewing_a_sites_edit_page()
    {
        $user = $this->signIn();
        $site = create(Site::class);

        $this->get(route('sites.edit', ['site' => $site]))
             ->assertStatus(403);
    }
}
