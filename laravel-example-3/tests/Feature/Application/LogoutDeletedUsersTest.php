<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

// @codingStandardsIgnoreFile
class LogoutDeletedUsersTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_logs_out_deleted_users_when_attempting_access_the_site()
    {
        $user = $this->signInWithEvents();
        $user->delete();

        $this->assertNotNull(auth()->user());
        $this->assertEquals($user->id, auth()->id());

        $response = $response = $this->get(route('dashboard'));

        $this->assertNull(auth()->user());
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
    * @test
    */
    public function it_does_not_logout_active_users_when_attempting_access_the_site()
    {
        $user = $this->signInWithEvents();

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
    }
}
