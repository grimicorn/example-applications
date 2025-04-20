<?php

// phpcs:ignoreFile

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Location;
use App\Models\LocationIcon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calculates_distance_between_the_location_and_an_address()
    {
        // ~124 miles from St. Louis
        $location = Location::factory()->blueSpring()->create();
        $distance = $location->distanceFromAddress('63129');
        $this->assertIsNumeric($distance);
        $this->assertGreaterThan(90, $distance);
        $this->assertLessThan(110, $distance);
    }

    /** @test */
    public function it_only_calculates_distance_between_the_location_if_an_address_exists()
    {
        $location = Location::factory()->blueSpring()->create();
        $distance = $location->distanceFromAddress('');
        $this->assertNull($distance);
    }

    /** @test */
    public function it_gets_the_address_attribute()
    {
        $location = Location::factory()->blueSpring()->create();
        $this->assertEquals(
            'Blue Spring, Eminence Township, Missouri 63638, US',
            $location->address
        );
    }

    /** @test */
    public function it_get_sunrise_attribute()
    {
        Carbon::setTestNow(Carbon::create(2020, 8, 8, 12));

        $location = Location::factory()->blueSpring()->create();
        $this->assertEquals(
            'Sat Aug 08 2020 06:14:38 GMT-0500',
            $location->sunrise->toString()
        );
    }

    /** @test */
    public function it_get_sunrise_golden_hour_attribute()
    {
        Carbon::setTestNow(Carbon::create(2020, 8, 8, 12));
        $location = Location::factory()->blueSpring()->create();

        $this->assertEquals(
            'Sat Aug 08 2020 06:44:38 GMT-0500',
            $location->sunrise_golden_hour->toString()
        );
    }

    /** @test */
    public function it_get_sunrise_blue_hour_attribute()
    {
        Carbon::setTestNow(Carbon::create(2020, 8, 8, 12));
        $location = Location::factory()->blueSpring()->create();

        $this->assertEquals(
            'Sat Aug 08 2020 05:44:38 GMT-0500',
            $location->sunrise_blue_hour->toString()
        );
    }

    /** @test */
    public function it_get_sunset_attribute()
    {
        Carbon::setTestNow(Carbon::create(2020, 8, 8, 12));

        $location = Location::factory()->blueSpring()->create();
        $this->assertEquals(
            'Sat Aug 08 2020 20:05:47 GMT-0500',
            $location->sunset->toString()
        );
    }

    /** @test */
    public function it_get_sunset_golden_hour_attribute()
    {
        Carbon::setTestNow(Carbon::create(2020, 8, 8, 12));

        $location = Location::factory()->blueSpring()->create();
        $this->assertEquals(
            'Sat Aug 08 2020 19:35:47 GMT-0500',
            $location->sunset_golden_hour->toString()
        );
    }

    /** @test */
    public function it_get_sunset_blue_hour_attribute()
    {
        Carbon::setTestNow(Carbon::create(2020, 8, 8, 12));

        $location = Location::factory()->blueSpring()->create();
        $this->assertEquals(
            'Sat Aug 08 2020 20:35:47 GMT-0500',
            $location->sunset_blue_hour->toString()
        );
    }

    /** @test */
    public function it_retrieves_the_locations_icon()
    {
        LocationIcon::factory()->count(5)->create();
        $expectedIcon = LocationIcon::all()->random();
        $location = Location::factory()->create([
            'icon_id' => $expectedIcon->id,
        ]);

        $this->assertNotNull($location->icon);
        $this->assertEquals($expectedIcon->id, $location->icon->id);
        $this->assertEquals($expectedIcon->url, $location->icon->url);
        $this->assertEquals($expectedIcon->name, $location->icon->name);
    }
}
