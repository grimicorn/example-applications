<?php

namespace Tests\Browser\Application;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileSettingsPasswordTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_updates_the_users_password()
    {
        $this->browse(function (Browser $browser) {
            // Setup
            $user = factory(User::class)->create();
            $new_password = 'new_password_not_the_old_one';

            // Visit the page.
            $browser->loginAs($user)->visit(route('profile.settings.edit'));

            // Click the button to show the password form.
            $browser->click('.profile-settings-change-pasword-btn')
                           ->waitFor('.update-password-form');

            // Fill out the required password fields.
            $browser->type('current_password', $user->password);
            $browser->type('password', $new_password);
            $browser->type('password_confirmation', $new_password);


            // Submit the form and check for the success alert.
            $browser->click('.update-password-form .fe-form-submit')
                           ->waitFor('.alert-success');

            // Assert that the password was updated
            $this->assertEquals($new_password, $user->fresh()->password);
        });
    }
}
