<?php

// phpcs:ignoreFile

namespace Tests\Feature;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationControllerDestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_a_user_to_be_authenticated_to_destroy_a_location()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /** @test */
    public function it_only_allows_a_user_to_destroy_their_own_locations()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /** @test */
    public function it_destroys_a_location()
    {
        $location = Location::factory()->create([
            'user_id' => $this->loginUser()->id,
        ]);
        $this->assertCount(1, Location::all());

        $this->delete(route('locations.destroy', ['location' => $location]))
            ->assertRedirect(route('locations.index'))
            ->assertSessionHas('success_message');

        $this->assertNull($location->fresh());
        $this->assertEmpty(Location::all());
    }
}
