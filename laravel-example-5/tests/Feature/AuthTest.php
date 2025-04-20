<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignorefile
class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_registers_a_user()
    {
        $this->withoutExceptionHandling();
        $user = make(User::class);

        $this->post(route('register'), [
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'password' => $password = uniqid(),
            'password_confirmation' => $password,
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
        ]);
    }

    /**
    * @test
    */
    public function it_authenticates_a_user()
    {
        $user = create(User::class, [
            'password' => bcrypt($password = uniqid()),
        ]);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => $password,
        ])->assertRedirect(route('dashboard'));
    }

    /**
     * @test
     */
    public function it_allows_a_user_to_request_a_forgotten_password_reset()
    {
        $user = create(User::class);

        Notification::fake();

        $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $this->assertDatabaseHas('password_resets', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo(
            $user,
            ResetPassword::class
        );
    }
}
