<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Location;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocationControllerShowTest extends TestCase
{
    /** @test */
    public function it_shows_a_user_an_individual_location()
    {
        $location = Location::factory()->create(['user_id' => $this->loginUser()->id]);

        $this->get(route('locations.show', ['location' => $location]))
            ->assertStatus(200)
            ->assertSee($location->name);
    }
}
