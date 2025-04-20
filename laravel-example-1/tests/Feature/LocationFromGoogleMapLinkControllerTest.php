<?php

// phpcs:ignoreFile

namespace Tests\Feature;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationFromGoogleMapLinkControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_location_from_a_google_maps_link()
    {
        $this->withoutExceptionHandling();
        $user = $this->loginUser();

        $request = $this->post(route('location-from-google-map-link.store'), [
            'link' => $link = 'https://www.google.com/maps/place/Blue+Spring/@37.1660768,-91.1643914,17z/data=!3m1!4b1!4m9!1m3!11m2!2s4wqcAsHvNUhImW3yFICJ_IbwLYq94g!3e3!3m4!1s0x87d753b261811af1:0x9faa818714a30c30!8m2!3d37.1660705!4d-91.1622008',
        ]);

        $location = Location::first();

        $request->assertRedirect(route('locations.edit', ['location' => $location]))
        ->assertSessionHas('success_message');

        $this->assertEquals('Blue Spring', $location->name);
        $this->assertEquals(37.1720779, $location->latitude);
        $this->assertEquals(-91.172507, $location->longitude);
        $this->assertEquals(
            'https://www.google.com/maps/dir//County+Road+533%2C+Ellington%2C+Missouri+63638%2C+US/@37.1720779,-91.1725074,13z/',
            $location->google_maps_link
        );
        $this->assertEquals('America/Chicago', $location->timezone);
        $this->assertEquals($user->id, $location->user->id);
    }

    /** @test */
    public function it_only_creates_the_location_if_it_does_not_exist()
    {
        $this->loginUser();

        $this->post(route('location-from-google-map-link.store'), [
            'link' => 'https://www.google.com/maps/place/Blue+Spring/@37.1660768,-91.1643914,17z/data=!3m1!4b1!4m9!1m3!11m2!2s4wqcAsHvNUhImW3yFICJ_IbwLYq94g!3e3!3m4!1s0x87d753b261811af1:0x9faa818714a30c30!8m2!3d37.1660705!4d-91.1622008',
        ]);

        $this->assertCount(1, Location::all());
        $this->post(route('location-from-google-map-link.store'), [
            'link' => 'https://www.google.com/maps/place/Blue+Spring/@37.1660768,-91.1643914,17z/data=!3m1!4b1!4m9!1m3!11m2!2s4wqcAsHvNUhImW3yFICJ_IbwLYq94g!3e3!3m4!1s0x87d753b261811af1:0x9faa818714a30c30!8m2!3d37.1660705!4d-91.1622008',
        ])
            ->assertStatus(302);

        $this->assertCount(1, Location::all());
    }

    /** @test */
    public function it_requires_a_link_parameter()
    {
        $this->loginUser();

        $this->post(route('location-from-google-map-link.store'), [])
            ->assertStatus(302)
            ->assertSessionHasErrors(['link']);
    }

    /** @test */
    public function it_requires_a_link_to_be_a_string()
    {
        $this->loginUser();

        $this->post(route('location-from-google-map-link.store'), [
            'link' => ['not a string'],
        ])
            ->assertStatus(302)
            ->assertSessionHasErrors(['link']);
    }

    /** @test */
    public function it_requires_a_user_to_be_authenticated_to_view_the_create_a_location_form()
    {
        $this->assertEmpty(Location::all());

        $this->get(route('location-from-google-map-link.store'), [
            'link' => $link = 'https://www.google.com/maps/place/Blue+Spring/@37.1660768,-91.1643914,17z/data=!3m1!4b1!4m9!1m3!11m2!2s4wqcAsHvNUhImW3yFICJ_IbwLYq94g!3e3!3m4!1s0x87d753b261811af1:0x9faa818714a30c30!8m2!3d37.1660705!4d-91.1622008',
        ])
            ->assertRedirect('/login');

        $this->assertEmpty(Location::all());
    }

    /** @test */
    public function it_requires_a_user_to_be_authenticated_to_create_a_location()
    {
        $this->assertEmpty(Location::all());

        $this->post(route('location-from-google-map-link.store'), [
            'link' => $link = 'https://www.google.com/maps/place/Blue+Spring/@37.1660768,-91.1643914,17z/data=!3m1!4b1!4m9!1m3!11m2!2s4wqcAsHvNUhImW3yFICJ_IbwLYq94g!3e3!3m4!1s0x87d753b261811af1:0x9faa818714a30c30!8m2!3d37.1660705!4d-91.1622008',
        ])
            ->assertRedirect('/login');

        $this->assertEmpty(Location::all());
    }
}
