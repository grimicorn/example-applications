<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function developers_can_access_nova()
    {
        $this->signInDeveloper();
        $this->get(config('nova.path'))->assertStatus(200);
    }

    /** @test */
    public function admins_can_not_access_nova()
    {
        $this->signInAdmin();
        $this->get(config('nova.path'))->assertStatus(403);
    }

    /** @test */
    public function general_users_can_not_access_nova()
    {
        $this->signIn();
        $this->get(config('nova.path'))->assertStatus(403);
    }

    /** @test */
    public function guests_can_not_access_nova()
    {
        $this->get(config('nova.path'))->assertRedirect(route('nova.login'));
    }

    /** @test */
    public function developers_can_access_telescope()
    {
        $this->signInDeveloper();
        $this->get(config('telescope.path'))->assertStatus(200);
    }

    /** @test */
    public function admins_can_not_access_telescope()
    {
        $this->signInAdmin();
        $this->get(config('telescope.path'))->assertStatus(403);
    }

    /** @test */
    public function general_users_can_not_access_telescope()
    {
        $this->signIn();
        $this->get(config('telescope.path'))->assertStatus(403);
    }

    /** @test */
    public function guests_can_not_access_telescope()
    {
        $this->get(config('telescope.path'))->assertStatus(403);
    }

    /** @test */
    public function developers_can_access_horizon()
    {
        $this->signInDeveloper();
        $this->get(config('horizon.path'))->assertStatus(200);
    }

    /** @test */
    public function admins_can_not_access_horizon()
    {
        $this->signInAdmin();
        $this->get(config('horizon.path'))->assertStatus(403);
    }

    /** @test */
    public function general_users_can_not_access_horizon()
    {
        $this->signIn();
        $this->get(config('horizon.path'))->assertStatus(403);
    }

    /** @test */
    public function guests_can_not_access_horizon()
    {
        $this->get(config('horizon.path'))->assertStatus(403);
    }
}
