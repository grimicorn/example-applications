<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

// @codingStandardsIgnoreStart
class ProfileNotificationsControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_enables_all_of_the_users_email_notifications()
    {
        // Setup
        $user = $this->signInWithEvents();
        $user->emailNotificationSettings->update([
            'enable_all' => false,
            'enable_due_diligence' => false,
            'enable_login' => false,
            'due_diligence_digest' => false,
        ]);

        // Execute
        $response = $this->patch(
            route('profile.notifications.update', []),
            $this->getTestValues()
        );

        // Check response
        $response->assertStatus(302)
                 ->assertSessionHas('status')
                 ->assertSessionHas('success', true);

        // Assert
        $emailNotificationSettings = $user->fresh()->emailNotificationSettings;
        $this->assertTrue($emailNotificationSettings->enable_all);
        $this->assertTrue($emailNotificationSettings->enable_due_diligence);
        $this->assertTrue($emailNotificationSettings->enable_login);
        $this->assertTrue($emailNotificationSettings->due_diligence_digest);
    }

    /**
     * @test
     */
    public function it_disables_all_of_the_users_email_notifications()
    {
        // Setup
        $user = $this->signInWithEvents();
        $user->emailNotificationSettings->update(['enable_all' => false]);

        // Execute
        $response = $this->patch(
            route('profile.notifications.update', []),
            $this->getTestValues([
                'enable_all' => 'off',
                'enable_due_diligence' => 'off',
                'enable_login' => 'off',
                'due_diligence_digest' => 'off',
            ])
        );

        // Check response
        $response->assertStatus(302)
                 ->assertSessionHas('status')
                 ->assertSessionHas('success', true);

        // Assert
        $emailNotificationSettings = $user->fresh()->emailNotificationSettings;
        $this->assertFalse($emailNotificationSettings->enable_all);
        $this->assertFalse($emailNotificationSettings->enable_due_diligence);
        $this->assertFalse($emailNotificationSettings->enable_login);
        $this->assertFalse($emailNotificationSettings->due_diligence_digest);
    }

    protected function getTestValues($override = [])
    {
        return [
            'emailNotificationSettings' => array_merge([
                'enable_all' => 'on',
                'enable_due_diligence' => 'on',
                'enable_login' => 'on',
                'due_diligence_digest' => 'on',
            ], $override),
        ];
    }
}
