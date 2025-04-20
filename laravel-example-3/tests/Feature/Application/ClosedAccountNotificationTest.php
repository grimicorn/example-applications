<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Tests\Support\HasNotificationTestHelpers;
use App\Support\Notification\NotificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class ClosedAccountNotificationTest extends TestCase
{
    use RefreshDatabase;
    use HasNotificationTestHelpers;

    /**
    * @test
    */
    public function it_sends_a_closed_account_notification_when_a_user_closes_their_account()
    {
        Mail::fake();

        // Create a user and then delete them. This will simulate a user closing their account
        $user = $this->signInWithEvents();
        $this->delete(route('profile.destroy'));

        // Make sure the notifications where dispatched correctly
        $this->assertNotificationCount(1, NotificationType::CLOSED_ACCOUNT);
        $this->assertEquals(
            $user->email,
            $this->getEmailNotificationsByType(NotificationType::CLOSED_ACCOUNT)->first()->to[0]['address']
        );
    }

    /**
     * @test
     */
    public function it_does_not_send_an_account_closed_notification_when_an_admin_removes_a_user()
    {
        Mail::fake();

        // Create a user and then delete them. This will simulate an admin removing a users account
        $this->signInWithEvents()->adminRemove();

        // Make sure the notifications where dispatched correctly
        $this->assertNotificationCount(0, NotificationType::CLOSED_ACCOUNT);
    }
}
