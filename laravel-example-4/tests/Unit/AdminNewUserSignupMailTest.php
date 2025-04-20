<?php

use Tests\TestCase;
use App\Mail\AdminNewUserSignup;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminNewUserSignupMailTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_sends_a_new_user_sign_up_notification_to_the_admin_when_a_user_signs_up()
    {
        // Setup
        $data = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        Mail::fake();

        // Execute
        App\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // Assert
        Mail::assertSent(AdminNewUserSignup::class, function ($mail) use ($data) {
            $hasUserName = ($mail->user->name === $data['name']);
            $hasUserEmail = ($mail->user->email === $data['email']);
            $hasAdminEmail = ($mail->to[0]['address'] === config('mail.to'));
            $hasAdminName = ($mail->to[0]['name'] === config('mail.name'));

            return $hasUserName && $hasUserEmail && $hasAdminEmail && $hasAdminName;
        });
    }
}
