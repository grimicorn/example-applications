<?php

namespace Tests\Unit;

use Auth;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdministratorGuardTest extends TestCase
{
    /**
     * @test
     */
    public function it_guards_administrator_area_from_non_administrators()
    {
        // Setup
        $user = factory(User::class)->create(['role' => 'subscriber']);
        Auth::login($user, true);

        // Execute
        $response = $this->get('/admin');

        // Assert
        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function it_guards_administrator_area_from_non_authenticated_users()
    {
        // Execute
        $response = $this->get('/admin');

        // Assert
        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function it_allows_administrators_access_to_the_administrator_area()
    {
        // Setup
        $user = factory(User::class)->create(['role' => 'administrator']);
        Auth::login($user, true);

        // Execute
        $response = $this->get('/admin');

        // Assert
        $response->assertStatus(200);
    }
}
