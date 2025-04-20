<?php

namespace Tests\Feature\Application\Notification;

use Tests\TestCase;
use App\Mail\NewNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use Tests\Support\HasNotificationTestHelpers;
use App\Support\Notification\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Support\Notification\LoginNotificationCookie;

// @codingStandardsIgnoreFile
class LoginNotificationTest extends TestCase
{
    use RefreshDatabase;
    use HasNotificationTestHelpers;

    /**
    * @test
    */
    public function it_sends_a_login_notification_if_a_user_has_requested_it()
    {
        Mail::fake();

        // Create a user with login notifications enabled.
        $user = factory('App\User')->create([
            'uses_two_factor_auth' => false,
        ]);
        $user->emailNotificationSettings->enable_login = true;
        $user->emailNotificationSettings->save();

        // Log that user in.
        $this->signInWithEvents($user);

        // Make sure the email was sent.
        $this->assertNotificationCount(1, NotificationType::LOGIN);
    }

    /**
    * @test
    */
    public function it_sends_a_login_notification_once_per_new_device()
    {
        Mail::fake();

        // Create a user with login notifications enabled.
        $user = factory('App\User')->create([
            'password' => bcrypt($password = 'secret'),
        ]);
        $user->emailNotificationSettings->enable_login = true;
        $user->emailNotificationSettings->save();

        // Log that user in. Make sure the cookie was set and the notification was sent.
        $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ])->assertCookie((new LoginNotificationCookie)->name());
        $this->assertNotificationCount(1, NotificationType::LOGIN);

        // Log the user back out
        $this->post('/logout');

        // Log them back in and make sure it is not sent.
        $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);
        $this->assertNotificationCount(1, NotificationType::LOGIN);
    }

    /**
    * @test
    */
    public function it_does_not_send_a_login_notification_when_a_user_is_logged_in_after_creation()
    {
        Mail::fake();

        $this->post('register', [
            'first_name' => 'First',
            'last_name' => 'Last',
            'email' => $this->faker->email(),
            'password' => 'secret',
            'terms' => 'on',
            'usa_resident' => 'on',
        ]);

        $this->assertNotNull(auth()->user());
        $this->assertNotificationCount(0, NotificationType::LOGIN);
    }

    /**
    * @test
    */
    public function it_sends_a_login_notification_if_a_user_has_two_factor_enabled()
    {
        Mail::fake();

        // Create a user with 2fa enabled.
        $user = factory('App\User')->create([
            'uses_two_factor_auth' => true,
        ]);
        $user->emailNotificationSettings->enable_login = true;
        $user->emailNotificationSettings->save();

        // Log that user in.
        $this->signInWithEvents($user);

        // Make sure the email was sent.
        $this->assertNotificationCount(1, NotificationType::LOGIN);
    }

    /**
    * @test
    */
    public function it_does_not_send_a_login_notification_if_a_user_has_disabled_it()
    {
        // Create a user with 2fa and notifications disabled.
        $user = factory('App\User')->create([
            'uses_two_factor_auth' => false,
        ]);
        $user->emailNotificationSettings->enable_login = false;
        $user->emailNotificationSettings->save();

        Mail::fake();

        // Log that user in.
        $this->signInWithEvents($user);

        // Make sure the email was sent.
        $this->assertNotificationCount(0, NotificationType::LOGIN);
    }
}
