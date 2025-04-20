<?php

// phpcs:ignoreFile

namespace Tests\Feature;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationRatingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_a_locations_rating()
    {
        $this->loginUser();

        $location = Location::factory()->create(['rating' => null]);
        $this->from(route('locations.index'))
            ->patch(route('location-rating', ['location' => $location]), [
                'rating' => 3,
            ])
            ->assertRedirect(route('locations.index'));

        $this->assertEquals(3, $location->fresh()->rating);
    }

    /** @test */
    public function it_requires_a_user_to_be_authenticated_to_update_a_rating_using_put()
    {
        $location = Location::factory()->create(['rating' => null]);
        $this->put(route('location-rating', ['location' => $location]), [
            'rating' => 3,
        ])
            ->assertRedirect('/login');
    }

    /** @test */
    public function it_requires_a_user_to_be_authenticated_to_update_a_rating_using_patch()
    {
        $location = Location::factory()->create(['rating' => null]);
        $this->patch(route('location-rating', ['location' => $location]), [
            'rating' => 3,
        ])
            ->assertRedirect('/login');
    }

    /** @test */
    public function it_requires_a_user_to_be_the_location_owner_to_update_a_rating_using_put()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /** @test */
    public function it_requires_a_user_to_be_the_location_owner_to_update_a_rating_using_patch()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /** @test */
    public function it_requires_a_locations_rating_to_be_numeric()
    {
        $this->loginUser();

        $location = Location::factory()->create(['rating' => null]);
        $this->from(route('locations.index'))
            ->patch(route('location-rating', ['location' => $location]), [
                'rating' => 'not numeric',
            ])
            ->assertSessionHasErrors(['rating']);

        $this->assertNull($location->fresh()->rating);
    }

    /** @test */
    public function it_requires_a_locations_rating_to_be_greater_than_or_equal_to_zero()
    {
        $this->loginUser();

        $location = Location::factory()->create(['rating' => null]);
        $this->from(route('locations.index'))
            ->patch(route('location-rating', ['location' => $location]), [
                'rating' => -0.1,
            ])
            ->assertSessionHasErrors(['rating']);

        $this->assertNull($location->fresh()->rating);
    }

    /** @test */
    public function it_requires_a_locations_rating_to_be_less_than_or_equal_to_5()
    {
        $this->loginUser();

        $location = Location::factory()->create(['rating' => null]);
        $this->from(route('locations.index'))
            ->patch(route('location-rating', ['location' => $location]), [
                'rating' => 5.1,
            ])
            ->assertSessionHasErrors(['rating']);

        $this->assertNull($location->fresh()->rating);
    }
}
