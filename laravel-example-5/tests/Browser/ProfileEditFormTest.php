<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

// phpcs:ignorefile
class ProfileEditFormTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function it_updates_a_users_profile()
    {
        $this->browse(function (Browser $browser) {
            $user = $old = $this->manualSignIn($browser);
            $new = make(User::class);
            $file_name = 'avatar.png';

            $browser->visit(route('profile.edit', ['user' => $user]));
            $browser->type('@profile_edit_first_name', $new->first_name);
            $browser->type('@profile_edit_last_name', $new->last_name);
            $browser->type('@profile_edit_email', $new->email);
            $browser->attach('@profile_edit_avatar', base_path("tests/stubs/{$file_name}"));
            $browser->type('@profile_edit_password', $password = uniqid());
            $browser->type('@profile_edit_password_confirmation', $password);
            $browser->press('@profile_edit_submit');
            $browser->assertRouteIs('profile.edit', ['user' => $user]);
            $browser->waitFor('@success_alert');

            $user = $user->fresh();
            $this->assertEquals($new->first_name, $user->first_name);
            $this->assertEquals($new->last_name, $user->last_name);
            $this->assertEquals($new->email, $user->email);
            $this->assertNotEquals($old->password, $user->password);
            $this->assertNotNull($media = $user->getFirstMedia('avatar'));
            $this->assertEquals(
                $file_name,
                pathinfo($user->avatar, PATHINFO_BASENAME)
            );
        });
    }

    /**
    * @test
    */
    public function it_only_updates_a_users_password_if_supplied()
    {
        $this->browse(function (Browser $browser) {
            $user = $old = $this->manualSignIn($browser);
            $new = make(User::class);

            $browser->visit(route('profile.edit', ['user' => $user]));
            $browser->type('@profile_edit_first_name', $new->first_name);
            $browser->type('@profile_edit_last_name', $new->last_name);
            $browser->type('@profile_edit_email', $new->email);
            $browser->type('@profile_edit_password', $password = '');
            $browser->type('@profile_edit_password_confirmation', $password);
            $browser->press('@profile_edit_submit');
            $browser->assertRouteIs('profile.edit', ['user' => $user]);
            $browser->waitFor('@success_alert');

            $user = $user->fresh();
            $this->assertEquals($new->first_name, $user->first_name);
            $this->assertEquals($new->last_name, $user->last_name);
            $this->assertEquals($new->email, $user->email);
            $this->assertEquals($old->password, $user->password);
        });
    }

    /**
    * @test
    */
    public function it_displays_profile_edit_validation_errors()
    {
        $this->browse(function (Browser $browser) {
            $user = $old = $this->manualSignIn($browser);
            $browser->visit(route('profile.edit', ['user' => $user]));
            $browser->waitFor('@profile_edit_first_name');
            $browser->type('@profile_edit_first_name', '');
            $browser->type('@profile_edit_last_name', '');
            $browser->type('@profile_edit_email', '');
            $browser->press('@profile_edit_submit');
            $browser->assertRouteIs('profile.edit', ['user' => $user]);
            $browser->waitFor('@danger_alert');
            $browser->assertSee('The first name field is required.');
            $browser->waitForText('The last name field is required.');
            $browser->assertSee('The email field is required.');

            $user = $user->fresh();
            $this->assertEquals($old->first_name, $user->first_name);
            $this->assertEquals($old->last_name, $user->last_name);
            $this->assertEquals($old->email, $user->email);
            $this->assertEquals($old->password, $user->password);
        });
    }
}
