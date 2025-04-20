<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignorefile
class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_allows_admins_to_access_admin_routes()
    {
        $this->signInAdmin();
        $this->get(route('styleguide.index'))->assertStatus(200);
    }

    /**
    * @test
    */
    public function it_does_not_allow_non_admins_to_access_admin_routes()
    {
        $this->signIn();
        $this->get(route('styleguide.index'))->assertStatus(403);
    }

    /**
    * @test
    */
    public function it_requires_authentication_to_access_admin_routes()
    {
        $this->get(route('styleguide.index'))->assertStatus(403);
    }
}
