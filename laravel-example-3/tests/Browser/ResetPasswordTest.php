<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\DatabaseMigrations;

// @codingStandardsIgnoreFile
class ResetPasswordTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_requires_a_user_to_enter_in_their_two_factor_auth_code_when_enabled_to_reset_password()
    {
        $this->browse(function (Browser $browser) {
            $this->withoutMiddleware(['throttle']);

            $user = factory('App\User')->create([
                'uses_two_factor_auth' => true,
            ]);
            $originalPassword = $user->password;

            // Handle the reset
            $browser->visit('/password/reset/')
                    ->type('email', $user->email)
                    ->click('.reset-password-form button[type="submit"]')
                    ->waitFor('.alert.alert-success')
                    ->assertSee('We have e-mailed your password reset link!');

            // Visit the reset page
            $browser->visit($this->resetLink($user))
                    ->type('email', $user->email)
                    ->type('password', $password = bcrypt('secret'))
                    ->type('password_confirmation', $password)
                    ->type('two_factor_token', '123456')
                    ->click('.password-reset-form button[type="submit"]')
                    ->pause(2000)
                    ->assertPathIs('/dashboard');

            // Make sure it was was reset
            $this->assertNotEquals($originalPassword, $user->fresh()->password);
        });
    }

    /**
     * @test
     */
    public function it_does_not_require_a_user_to_enter_in_their_two_factor_auth_code_when_disabled_to_reset_password()
    {
        $this->browse(function (Browser $browser) {
            $this->withoutMiddleware(['throttle']);

            $user = factory('App\User')->create([
                'uses_two_factor_auth' => false,
            ]);
            $originalPassword = $user->password;

            // Handle the reset
            $browser->visit('/password/reset/')
                    ->type('email', $user->email)
                    ->click('.reset-password-form button[type="submit"]')
                    ->waitFor('.alert.alert-success')
                    ->assertSee('We have e-mailed your password reset link!');

            // Visit the reset page
            $browser->visit($this->resetLink($user))
                    ->type('email', $user->email)
                    ->type('password', $password = bcrypt('secret'))
                    ->type('password_confirmation', $password)
                    ->click('.password-reset-form button[type="submit"]')
                    ->pause(2000)
                    ->assertPathIs('/dashboard');

            // Make sure it was was reset
            $this->assertNotEquals($originalPassword, $user->fresh()->password);
        });
    }

    protected function resetLink($user)
    {
        // Generate a new token
        $token = hash_hmac('sha256', Str::random(40), config('app.key'));

        // Update the user
        app()->get('db')->connection(null)->table('password_resets')->where('email', $user->email)->update([
            'token' => app()->get('hash')->make($token),
        ]);

        // Build the URL
        return "/password/reset/{$token}?email={$user->email}";
    }
}
