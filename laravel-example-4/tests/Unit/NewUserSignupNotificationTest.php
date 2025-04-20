<?php

use App\User;
use Tests\TestCase;
use App\Notifications\NewUserSignup;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewUserSignupNotificationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_sends_the_user_a_notification_when_they_sign_up()
    {
        // Setup
        Notification::fake();
        $data = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        // Execute
        App\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // Assert
        $user = User::where('email', $data['email'])->get()->first();
        Notification::assertSentTo(
            $user,
            NewUserSignup::class,
            function ($notification, $channels) use ($data) {
                return $notification->user->name === $data['name']
                       && $notification->user->email === $data['email'];
            }
        );
    }
}
