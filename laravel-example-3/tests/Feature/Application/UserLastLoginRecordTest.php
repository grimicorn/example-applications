<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class UserLastLoginRecordTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    * @group failing
    */
    public function it_records_users_logins()
    {
        // Make sure new users get a default.
        $user = factory('App\User')->create();
        $prevLogin = $user->last_login;
        $this->assertNotNull($user->last_login);

        // Give it a scond so the update is not the same.
        sleep(1);

        // Sign in and cehck update.
        $this->signInWithEvents($user);
        $this->assertNotEquals($prevLogin, $user->fresh()->last_login);
    }

    /**
    * @test
    */
    public function it_does_not_update_the_users_last_login_when_impersonating_a_user()
    {
        // Setup the impersonator and the user.
        $format = 'm/d/y h:m';
        $impersonator = $this->signInDeveloperWithEvents();
        $user = factory('App\User')->create([ 'last_login' => $last_login = Carbon::now()->subDays(3) ]);
        $this->assertEquals($last_login->format($format), $user->last_login->format($format));

        // Impersonate the user
        $this->get("/spark/kiosk/users/impersonate/{$user->id}");

        // Check that the last login was not updated.
        $user = $user->fresh();
        $this->assertTrue($user->isImpersonatorDeveloper());
        $this->assertEquals($last_login->format($format), $user->last_login->format($format));
    }

    /**
     * @test
     */
    public function it_does_not_update_the_users_last_login_when_stopping_impersonation_of_a_user()
    {
        // Setup the impersonator and the user.
        $format = 'm/d/y h:m';
        $impersonator = $this->signInDeveloperWithEvents();
        $user = factory('App\User')->create(['last_login' => $last_login = Carbon::now()->subDays(3)]);
        $this->assertEquals($last_login->format($format), $user->last_login->format($format));

        // Impersonate the user
        $this->get("/spark/kiosk/users/impersonate/{$user->id}");

        // Check that the last login was not updated.
        $user = $user->fresh();
        $this->assertTrue($user->isImpersonatorDeveloper());
        $this->assertEquals($last_login->format($format), $user->last_login->format($format));

        // Stop impersonation
        $this->get('/spark/kiosk/users/stop-impersonating/');

        // Check that the last login was not updated.
        $user = $user->fresh();
        $this->assertFalse((bool) $user->isImpersonatorDeveloper());
        $this->assertEquals($last_login->format($format), $user->last_login->format($format));
    }
}
