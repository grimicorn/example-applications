<?php

//phpcs:ignoreFile

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCurrentLocationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_an_authenticated_user_to_update_a_location()
    {
        $this->post(route('user-location.store'), [
            'latitude' => 38.4729088,
            'longitude' => -90.3479296,
        ])
            ->assertRedirect('/login');
    }

    /** @test */
    public function it_stores_the_users_current_location()
    {
        $user = $this->loginUser();
        $this->post(route('user-location.store'), [
            'latitude' => 38.4729088,
            'longitude' => -90.3479296,
        ])
            ->assertJson([
                'data' => [
                    'address' => '3722 Gumtree Ln, St. Louis, MO 63129, USA',
                    'success_message' => 'Location updated successfully!',
                    'success' => true,
                ],
            ]);

        $user = $user->fresh();
        $this->assertEquals(38.4729088, $user->currentLocation->latitude);
        $this->assertEquals(-90.3479296, $user->currentLocation->longitude);
    }

    /** @test */
    public function it_updates_the_users_current_location()
    {
        $user = $this->loginUser();
        $this->post(route('user-location.store'), [
            'latitude' => 38.4729088,
            'longitude' => -90.3479296,
        ]);

        $user = $user->fresh();
        $this->assertEquals(38.4729088, $user->currentLocation->latitude);
        $this->assertEquals(-90.3479296, $user->currentLocation->longitude);

        $user = $this->loginUser();
        $this->post(route('user-location.store'), [
            'latitude' => 37.1660725,
            'longitude' => -91.1643914,
        ])
            ->assertJson([
                'data' => [
                    'address' => 'Co Rd 533, Ellington, MO 63638, USA',
                    'success_message' => 'Location updated successfully!',
                    'success' => true,
                ],
            ]);

        $user = $user->fresh();
        $this->assertEquals(37.1660725, $user->currentLocation->latitude);
        $this->assertEquals(-91.1643914, $user->currentLocation->longitude);
    }

    /** @test */
    public function it_requires_latitude_and_longitude()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /** @test */
    public function it_requires_latitude_to_be_numeric()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /** @test */
    public function it_requires_longitude_to_be_numeric()
    {
        $this->markTestIncomplete('Not implemented');
    }
}
