<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Support\GetsCoordinates;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignorefile
class GetsCoordinatesTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_gets_zip_code_coordinates()
    {
        $getsCoordinates = $this->getMockForTrait(GetsCoordinates::class);
        $coordinates = $getsCoordinates->getZipCodeCoordinates('63103');
        $this->assertNotNull($coordinates);
        $this->assertCount(2, $coordinates);
    }

    /**
    * @test
    */
    public function it_does_not_get_zip_code_coordinates_for_invalid_locations()
    {
        $getsCoordinates = $this->getMockForTrait(GetsCoordinates::class);
        $coordinates = $getsCoordinates->getZipCodeCoordinates('00000');
        $this->assertEmpty($coordinates);
    }

    /**
     * @test
     */
    public function it_gets_a_zip_codes_location()
    {
        $getsCoordinates = $this->getMockForTrait(GetsCoordinates::class);
        $location = $getsCoordinates->getLocationByZipCode('63103');
        $this->assertNotNull($location);
        $this->assertNotEmpty($location);
    }

    /**
     * @test
     */
    public function it_does_not_get_zip_code_location_for_invalid_locations()
    {
        $getsCoordinates = $this->getMockForTrait(GetsCoordinates::class);
        $location = $getsCoordinates->getLocationByZipCode('00000');
        $this->assertNull($location);
    }
}
