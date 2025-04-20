<?php

// phpcs:ignoreFile

namespace Tests\Unit;

use App\Domain\Supports\Timezone;
use Tests\TestCase;

class TimezoneTest extends TestCase
{
    /** @test */
    public function it_gets_the_timezone_from_coordinates()
    {
        // 5711 Huntington Valley Ct, St. Louis, MO 63129
        // Longitude,Latitude from Google Maps: 38.4705537, -90.3483247
        $this->assertEquals(
            'America/Chicago',
            (new Timezone)->fromCoordinates(38.4705537, -90.3483247)
        );
    }
}
