<?php

namespace Tests\Browser\Application;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileSettingsTwoFactorAuthenticationTest extends DuskTestCase
{
    use DatabaseMigrations;

     /**
     * @test
     * @group failing
     */
    public function it_enables_two_factor_authentication()
    {
        $this->browse(function (Browser $browser) {
            // Setup
            $user = factory(User::class)->create();

            // Visit the page.
            $browser->loginAs($user)->visit(route('profile.settings.edit'));

            // Fill out the required password fields.
            $browser->type('country_code', '1');
            $browser->type('phone', '555-555-5555');

            // Submit the form and check for the success alert.
            $browser->click('.enable-two-factor-auth-form-submit');

            // Assert that the password was updated
            $browser->whenAvailable('#modal-show-two-factor-reset-code', function () use ($user) {
                $this->assertTrue($user->fresh()->uses_two_factor_auth);
            });
        });
    }
}
