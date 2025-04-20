<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

// @codingStandardsIgnoreFile
class LoginViaEmergencyTokenTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function it_allows_2fa_users_to_login_using_via_emergency_token()
    {
        $this->browse(function (Browser $browser) {
            $user = factory('App\User')->create([
                'uses_two_factor_auth' => true,
                'two_factor_reset_code' => bcrypt($reset_code = str_random(40)),
                'password' => bcrypt($password = str_random(30)),
            ]);

            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', $password)
                    ->click('#login-form button[type="submit"]')
                    ->assertPathIs('/login/token')
                    ->click('@lost-your-device-link')
                    ->assertPathIs('/login-via-emergency-token')
                    ->type('token', $reset_code)
                    ->click('#login-via-emergency-token button[type="submit"]')
                    ->assertPathIs('/dashboard');
        });
    }

    /**
    * @test
    */
    public function it_handles_2fa_token_errors_on_login_using_via_emergency_token()
    {
        $this->browse(function (Browser $browser) {
            $user = factory('App\User')->create([
                'uses_two_factor_auth' => 1,
                'two_factor_reset_code' => bcrypt($reset_code = str_random(40)),
                'password' => bcrypt($password = str_random(30)),
            ]);

            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', $password)
                    ->click('#login-form button[type="submit"]')
                    ->assertPathIs('/login/token')
                    ->click('@lost-your-device-link')
                    ->assertPathIs('/login-via-emergency-token')

                    // Empty token
                    ->type('token', '')
                    ->click('#login-via-emergency-token button[type="submit"]')
                    ->waitFor('.alert.alert-danger')
                    ->assertSeeIn('.alert.alert-danger', 'Unable to login successfully. See below for errors.')
                    ->assertSeeIn('.fe-input-token-wrap .fe-input-error-message', 'The field is required.')
                    ->assertPathIs('/login-via-emergency-token')
                    ->assertPathIs('/login-via-emergency-token')

                    // Incorrect token
                    ->type('token', 'not_my_reset_code')
                    ->click('#login-via-emergency-token button[type="submit"]')
                    ->waitFor('.alert.alert-danger')
                    ->assertSeeIn('.alert.alert-danger', 'Unable to login successfully. See below for errors.')
                    ->assertSeeIn('.fe-input-token-wrap .fe-input-error-message', 'The Emergency Token entered was invalid.')
                    ->assertPathIs('/login-via-emergency-token');

            // Successfully login
                    // ->type('token', $reset_code)
                    // ->click('#login-via-emergency-token button[type="submit"]')
                    // ->pause(2000)
                    // ->assertPathIs('/dashboard');
        });
    }
}
