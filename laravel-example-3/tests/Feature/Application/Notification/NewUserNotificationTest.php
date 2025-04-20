<?php

namespace Tests\Feature\Application\Notification;

use Tests\TestCase;
use App\Mail\NewNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class NewUserNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_sends_a_new_user_notification()
    {
        Mail::fake();

        // Create a user with login notifications enabled.
        $user = factory('App\User')->create();

        // Make sure the email was sent.
        Mail::assertSent(NewNotification::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}
