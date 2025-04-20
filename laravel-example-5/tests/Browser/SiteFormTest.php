<?php

namespace Tests\Browser;

use App\Site;
use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

// phpcs:ignorefile
class SiteFormTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function it_creates_a_site()
    {
        $this->browse(function (Browser $browser) {
            $site = make(Site::class);

            $user = $this->manualSignIn($browser);

            $browser->visit(route('sites.create'));

            $browser->type('@site_create_name', $site->name);
            $browser->type('@site_create_sitemap_url', $site->sitemap_url);
            $browser->type('@site_create_difference_threshold', $site->difference_threshold);
            $browser->press('@site_create_submit');
            $browser->waitForReload();
            $browser->assertRouteIs('sites.show', [
                'site' => Site::latest()->first(),
            ]);

            $this->assertDatabaseHas('sites', [
                'name' => $site->name,
                'sitemap_url' => $site->sitemap_url,
                'difference_threshold' => $site->difference_threshold,
                'user_id' => $user->id,
            ]);
        });
    }

    /**
    * @test
    */
    public function it_displays_create_site_validation_errors()
    {
        $this->browse(function (Browser $browser) {
            $site = make(Site::class);

            $this->manualSignIn($browser);

            $browser->visit(route('sites.create'));
            $browser->press('@site_create_submit');
            $browser->assertRouteIs('sites.create');
            $browser->waitFor('@danger_alert');
            $browser->assertSee('The name field is required.');
            $browser->assertSee('The sitemap url field is required.');
        });
    }

    /**
     * @test
     */
    public function it_edits_a_site()
    {
        $this->browse(function (Browser $browser) {
            $user = $this->manualSignIn($browser);

            $site = create(Site::class, [
                'user_id' => $user->id,
            ]);
            $newSite = make(Site::class);


            $browser->visit(route('sites.edit', ['site' => $site]));

            $browser->type('@site_edit_name', $newSite->name);
            $browser->type('@site_edit_sitemap_url', $newSite->sitemap_url);
            $browser->type('@site_edit_difference_threshold', $newSite->difference_threshold);
            $browser->press('@site_edit_submit');
            $browser->waitFor('@success_alert');
            $browser->assertRouteIs('sites.edit', ['site' => $site]);

            $site = $site->fresh();
            $this->assertEquals($user->id, $site->user_id);
            $this->assertEquals($newSite->name, $site->name);
            $this->assertEquals($newSite->sitemap_url, $site->sitemap_url);
            $this->assertEquals($newSite->difference_threshold, $site->difference_threshold);
        });
    }

    /**
     * @test
     */
    public function it_displays_edit_site_validation_errors()
    {
        $this->browse(function (Browser $browser) {
            $user = $this->manualSignIn($browser);

            $site = create(Site::class, [
                'user_id' => $user->id,
            ]);

            $browser->visit(route('sites.edit', ['site' => $site]));
            $browser->type('@site_edit_name', '');
            $browser->type('@site_edit_sitemap_url', '');
            $browser->press('@site_edit_submit');
            $browser->assertRouteIs('sites.edit', ['site' => $site]);
            $browser->waitFor('@danger_alert');
            $browser->assertSee('The name field is required.');
            $browser->assertSee('The sitemap url field is required.');
        });
    }
}
