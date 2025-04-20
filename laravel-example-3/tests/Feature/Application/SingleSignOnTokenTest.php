<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class SingleSignOnTokenTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_sets_a_single_sign_on_token_when_a_user_logs_in()
    {
        // Create the user
        $user = factory('App\User')->create();
        $this->assertNull($user->single_sign_on_token);

        // Sign the user in
        $this->signInWithEvents($user);

        // Check the session/sign on was set
        $user = $user->fresh();
        $this->assertNotNull($user->single_sign_on_token);
        $this->assertEquals(
            $user->single_sign_on_token,
            session()->get('single_sign_on_token')
        );
    }

    /**
    * @test
    * @group failing
    */
    public function it_checks_if_a_user_is_the_single_sign_on()
    {
        // Create the user
        $user = factory('App\User')->create();
        $this->assertNull($user->single_sign_on_token);

        // Sign the user in and save it as
        // "old" user so we can check it later.
        $this->signInWithEvents($user);
        $oldUser = $user->fresh();

        // Check user single sign on
        $this->assertTrue($oldUser->isSingleSignOn());

        // Sign in the user again
        $this->signInWithEvents($user);
        $newUser = $user->fresh();

        // Make sure the new user is single sign on and old one is not.
        $this->assertTrue($newUser->isSingleSignOn());
        $this->assertFalse($oldUser->isSingleSignOn());
    }
}
