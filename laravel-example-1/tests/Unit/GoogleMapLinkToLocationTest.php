<?php

// phpcs:ignoreFile

namespace Tests\Unit;

use App\Domain\Supports\GoogleMapLinkToLocation;
use App\Models\Location;
use App\Models\Tag;
use App\Models\Taggable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoogleMapLinkToLocationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_location_from_a_google_maps_link()
    {
        $user = $this->loginUser();

        $link = 'https://www.google.com/maps/place/Blue+Spring/@37.1660768,-91.1643914,17z/data=!3m1!4b1!4m9!1m3!11m2!2s4wqcAsHvNUhImW3yFICJ_IbwLYq94g!3e3!3m4!1s0x87d753b261811af1:0x9faa818714a30c30!8m2!3d37.1660705!4d-91.1622008';
        (new GoogleMapLinkToLocation)->firstOrCreate($link, [
            'tags' => 'Name 2,Name 1,Name 2',
        ]);

        $location = Location::first();
        $this->assertEquals('Blue Spring', $location->name);
        $this->assertEquals(37.1720779, $location->latitude);
        $this->assertEquals(-91.172507, $location->longitude);
        $this->assertEquals(
            'https://www.google.com/maps/dir//County+Road+533%2C+Ellington%2C+Missouri+63638%2C+US/@37.1720779,-91.1725074,13z/',
            $location->google_maps_link
        );
        $this->assertEquals($user->id, $location->user->id);
        $this->assertEquals('County Road 533, Ellington, Missouri 63638, US', $location->address);
        $this->assertEquals('County Road 533', $location->route);
        $this->assertEquals('Ellington', $location->locality);
        $this->assertEquals(
            'MO',
            $location->administrative_area_level_1_abbreviation
        );
        $this->assertEquals('Missouri', $location->administrative_area_level_1);
        $this->assertEquals('US', $location->country);
        $this->assertEquals('63638', $location->postal_code);
    }

    /** @test */
    public function it_creates_tags_for_a_location()
    {
        $this->loginUser();

        $link = 'https://www.google.com/maps/place/Blue+Spring/@37.1660768,-91.1643914,17z/data=!3m1!4b1!4m9!1m3!11m2!2s4wqcAsHvNUhImW3yFICJ_IbwLYq94g!3e3!3m4!1s0x87d753b261811af1:0x9faa818714a30c30!8m2!3d37.1660705!4d-91.1622008';
        (new GoogleMapLinkToLocation)->firstOrCreate($link, [
            'tags' => 'Tag 2,Tag 1,Tag 2',
        ]);

        $location = Location::first();
        $this->assertCount(2, $tags = Tag::all());
        $this->assertEquals(
            collect(['Tag 2', 'Tag 1'])->sort()->values()->toArray(),
            $tags->pluck('name')->toArray()
        );
        $this->assertCount(2, Taggable::all());
        $this->assertEquals(
            collect([
                [
                    'slug' => 'tag-2',
                    'name' => 'Tag 2',
                ],
                [
                    'slug' => 'tag-1',
                    'name' => 'Tag 1',
                ],
            ])->sortBy('slug')->values()->toArray(),
            $location->tags->map->only(['slug', 'name'])->sortBy('slug')->values()->toArray()
        );
    }

    /** @test */
    public function it_only_creates_the_location_if_it_does_not_exist()
    {
        $this->loginUser();

        $link = 'https://www.google.com/maps/place/Blue+Spring/@37.1660768,-91.1643914,17z/data=!3m1!4b1!4m9!1m3!11m2!2s4wqcAsHvNUhImW3yFICJ_IbwLYq94g!3e3!3m4!1s0x87d753b261811af1:0x9faa818714a30c30!8m2!3d37.1660705!4d-91.1622008';
        (new GoogleMapLinkToLocation)->firstOrCreate($link);

        $this->assertCount(1, Location::all());
        (new GoogleMapLinkToLocation)->firstOrCreate($link);

        $this->assertCount(1, Location::all());
    }
}
