<?php

namespace Tests\Feature\Application;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignorefile
class DashboardModuleDismissControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_dismisses_a_dashboard_module()
    {
        $user = $this->signIn();

        $this->assertFalse($user->fresh()->getting_started_dismissed);

        $this->delete(route('dashboard.module-dismiss'));

        $this->assertTrue($user->fresh()->getting_started_dismissed);
    }
}
