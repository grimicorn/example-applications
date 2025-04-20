<?php

// phpcs:ignoreFile

namespace Tests\Unit;

use App\Domain\Supports\Geocoder;
use App\Domain\Supports\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeocoderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->enableGeocoding();
    }

    protected function tearDown(): void
    {
        $this->disableGeocoding();
        parent::tearDown();
    }

    /** @test */
    public function it_geocodes_an_address()
    {
        // 5711 Huntington Valley Ct, St. Louis, MO 63129
        // Longitude,Latitude from Google Maps: 38.4705537, -90.3483247
        $geocode = resolve(Geocoder::class)->enable()->geocodeAddress('5711 Huntington Valley Ct, St. Louis, MO 63129');

        $this->assertGreaterThanOrEqual(38.0, $geocode->get('lat'));
        $this->assertLessThanOrEqual(39.0, $geocode->get('lat'));
        $this->assertGreaterThanOrEqual(-91.0, $geocode->get('lng'));
        $this->assertLessThanOrEqual(-90.0, $geocode->get('lng'));
    }

    /** @test */
    public function it_sets_latitude_and_longitude_to_null_when_not_found()
    {
        $geocode = resolve(Geocoder::class)->enable()->geocodeAddress(null, true);

        $this->assertNull($geocode->get('lat'));
        $this->assertNull($geocode->get('lng'));
    }

    /** @test */
    public function it_sets_latitude_and_longitude_to_null_when_address_is_null()
    {
        $geocode = resolve(Geocoder::class)->enable()->geocodeAddress(null);

        $this->assertNull($geocode->get('lat'));
        $this->assertNull($geocode->get('lng'));
    }

    /** @test */
    public function it_does_no_geocode_an_address_when_disabled()
    {
        // 5711 Huntington Valley Ct, St. Louis, MO 63129
        // Longitude,Latitude from Google Maps: 38.4705537, -90.3483247
        $geocode = resolve(Geocoder::class)
            ->disable()
            ->geocodeAddress('5711 Huntington Valley Ct, St. Louis, MO 63129');
        resolve(Geocoder::class)->enable();

        $this->assertNull($geocode->get('lat'));
        $this->assertNull($geocode->get('lng'));
    }

    /** @test */
    public function it_calculates_the_distance_between_to_latitudes_and_longitudes()
    {
        // Total distance: ~100 mi

        // Blue Spring, Eminence Township, MO 63638
        // Longitude,Latitude from Google Maps: 37.1660725, -91.1643914

        // 5711 Huntington Valley Ct, St. Louis, MO 63129
        // Longitude,Latitude from Google Maps: 38.4705537, -90.3483247

        $distance = resolve(Geocoder::class)->enable()->distanceBetweenLocations(
            new Location($longitude1 = -91.1643914, $latitude1 = 37.1660725),
            new Location($longitude2 = -90.3483247, $latitude2 = 38.4705537)
        );

        $this->assertGreaterThanOrEqual(95, $distance);
        $this->assertLessThanOrEqual(105, $distance);
    }

    /** @test */
    public function it_get_address_for_coordinates()
    {
        $address = resolve(Geocoder::class)->enable()->getAddressForCoordinates(38.4705537, -90.3483247);
        $this->assertEquals(
            '5711 Huntington Valley Ct, St. Louis, MO 63129, USA',
            $address->get('formatted_address')
        );
    }
}
