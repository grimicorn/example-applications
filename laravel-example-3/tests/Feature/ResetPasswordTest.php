<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_requires_a_user_to_enter_in_their_two_factor_auth_code_when_enabled_to_reset_password()
    {
        $user = factory('App\User')->create([
            'uses_two_factor_auth' => true,
        ]);
        $originalPassword = $user->password;

        // Handle the reset
        $this->post('/password/email', ['email' => $user->email]);

        // Reset the password
        $this->post('password/reset', [
            'token' => $this->resetToken($user),
            'email' => $user->email,
            'password' => $password = bcrypt('secret'),
            'password_confirmation' => $password,
            'two_factor_token' => '123456', // This will always be valid when testing
        ]);

        // Make sure it was was reset
        $this->assertNotEquals($originalPassword, $user->fresh()->password);
    }

    /**
     * @test
     */
    public function it_does_not_require_a_user_to_enter_in_their_two_factor_auth_code_when_disabled_to_reset_password()
    {
        $user = factory('App\User')->create([
            'uses_two_factor_auth' => false,
        ]);
        $originalPassword = $user->password;

        // Handle the reset
        $this->post('/password/email', ['email' => $user->email]);

        // Reset the password
        $this->post('password/reset', [
            'token' => $this->resetToken($user),
            'email' => $user->email,
            'password' => $password = bcrypt('secret'),
            'password_confirmation' => $password,
        ]);

        // Make sure it was was reset
        $this->assertNotEquals($originalPassword, $user->fresh()->password);
    }

    protected function resetToken($user)
    {
        // Generate a new token
        $token = hash_hmac('sha256', Str::random(40), config('app.key'));

        // Update the user
        app()->get('db')->connection(null)->table('password_resets')->where('email', $user->email)->update([
            'token' => app()->get('hash')->make($token),
        ]);

        return $token;
    }
}
