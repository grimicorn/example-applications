<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ProfileSettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group failing
     */
    public function it_updates_the_users_password()
    {
        $this->withoutExceptionHandling();
        // Setup
        $old_password = 'old_password';
        $user = factory('App\User')->create(['password' => $bcrypt_password = bcrypt($old_password)]);
        $this->assertEquals($bcrypt_password, $user->password);
        $new_password = 'new_password_not_the_old_one';
        $this->signInWithEvents($user);

        // Execute
        $response = $this->patch(route('profile.settings.update'), [
            'current_password' => $old_password,
            'password' => $new_password,
            'password_confirmation' => $new_password,
        ]);

        // Assert
        $this->assertNotEquals($bcrypt_password, $user->fresh()->password);
        $response->assertStatus(302)
                         ->assertSessionHas('status')
                         ->assertSessionHas('success', true);
    }
}
