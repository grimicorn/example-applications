<?php

namespace Tests\Unit;

use App\User;
use App\Listing;
use Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

// phpcs:ignorefile
class UserGeocodingTest extends TestCase
{
    /**
    * @test
    */
    public function it_geocodes_alabama_zipcodes()
    {
        $this->assertStateIsGeocoded('AL');
    }

    /**
    * @test
    */
    public function it_geocodes_arizona_zipcodes()
    {
        $this->assertStateIsGeocoded('AZ');
    }

    /**
    * @test
    */
    public function it_geocodes_arkansas_zipcodes()
    {
        $this->assertStateIsGeocoded('AR');
    }

    /**
    * @test
    */
    public function it_geocodes_california_zipcodes()
    {
        $this->assertStateIsGeocoded('CA');
    }

    /**
    * @test
    */
    public function it_geocodes_colorado_zipcodes()
    {
        $this->assertStateIsGeocoded('CO');
    }

    /**
    * @test
    */
    public function it_geocodes_connecticut_zipcodes()
    {
        $this->assertStateIsGeocoded('CT');
    }

    /**
    * @test
    */
    public function it_geocodes_delaware_zipcodes()
    {
        $this->assertStateIsGeocoded('DE');
    }

    /**
    * @test
    */
    public function it_geocodes_florida_zipcodes()
    {
        $this->assertStateIsGeocoded('FL');
    }

    /**
    * @test
    */
    public function it_geocodes_georgia_zipcodes()
    {
        $this->assertStateIsGeocoded('GA');
    }

    /**
    * @test
    */
    public function it_geocodes_idaho_zipcodes()
    {
        $this->assertStateIsGeocoded('ID');
    }

    /**
    * @test
    */
    public function it_geocodes_illinois_zipcodes()
    {
        $this->assertStateIsGeocoded('IL');
    }

    /**
    * @test
    */
    public function it_geocodes_indiana_zipcodes()
    {
        $this->assertStateIsGeocoded('IN');
    }

    /**
    * @test
    */
    public function it_geocodes_iowa_zipcodes()
    {
        $this->assertStateIsGeocoded('IA');
    }

    /**
    * @test
    */
    public function it_geocodes_kansas_zipcodes()
    {
        $this->assertStateIsGeocoded('KS');
    }

    /**
    * @test
    */
    public function it_geocodes_kentucky_zipcodes()
    {
        $this->assertStateIsGeocoded('KY');
    }

    /**
    * @test
    */
    public function it_geocodes_louisiana_zipcodes()
    {
        $this->assertStateIsGeocoded('LA');
    }

    /**
    * @test
    */
    public function it_geocodes_maine_zipcodes()
    {
        $this->assertStateIsGeocoded('ME');
    }

    /**
    * @test
    */
    public function it_geocodes_maryland_zipcodes()
    {
        $this->assertStateIsGeocoded('MD');
    }

    /**
    * @test
    */
    public function it_geocodes_massachusetts_zipcodes()
    {
        $this->assertStateIsGeocoded('MA');
    }

    /**
    * @test
    */
    public function it_geocodes_michigan_zipcodes()
    {
        $this->assertStateIsGeocoded('MI');
    }

    /**
    * @test
    */
    public function it_geocodes_minnesota_zipcodes()
    {
        $this->assertStateIsGeocoded('MN');
    }

    /**
    * @test
    */
    public function it_geocodes_mississippi_zipcodes()
    {
        $this->assertStateIsGeocoded('MS');
    }

    /**
    * @test
    */
    public function it_geocodes_missouri_zipcodes()
    {
        $this->assertStateIsGeocoded('MO');
    }

    /**
    * @test
    */
    public function it_geocodes_montana_zipcodes()
    {
        $this->assertStateIsGeocoded('MT');
    }

    /**
    * @test
    */
    public function it_geocodes_nebraska_zipcodes()
    {
        $this->assertStateIsGeocoded('NE');
    }

    /**
    * @test
    */
    public function it_geocodes_nevada_zipcodes()
    {
        $this->assertStateIsGeocoded('NV');
    }

    /**
    * @test
    */
    public function it_geocodes_new_hampshire_zipcodes()
    {
        $this->assertStateIsGeocoded('NH');
    }

    /**
    * @test
    */
    public function it_geocodes_new_jersey_zipcodes()
    {
        $this->assertStateIsGeocoded('NJ');
    }

    /**
    * @test
    */
    public function it_geocodes_new_mexico_zipcodes()
    {
        $this->assertStateIsGeocoded('NM');
    }

    /**
    * @test
    */
    public function it_geocodes_new_york_zipcodes()
    {
        $this->assertStateIsGeocoded('NY');
    }

    /**
    * @test
    */
    public function it_geocodes_north_carolina_zipcodes()
    {
        $this->assertStateIsGeocoded('NC');
    }

    /**
    * @test
    */
    public function it_geocodes_north_dakota_zipcodes()
    {
        $this->assertStateIsGeocoded('ND');
    }

    /**
    * @test
    */
    public function it_geocodes_ohio_zipcodes()
    {
        $this->assertStateIsGeocoded('OH');
    }

    /**
    * @test
    */
    public function it_geocodes_oklahoma_zipcodes()
    {
        $this->assertStateIsGeocoded('OK');
    }

    /**
    * @test
    */
    public function it_geocodes_oregon_zipcodes()
    {
        $this->assertStateIsGeocoded('OR');
    }

    /**
    * @test
    */
    public function it_geocodes_pennsylvania_zipcodes()
    {
        $this->assertStateIsGeocoded('PA');
    }

    /**
    * @test
    */
    public function it_geocodes_rhode_island_zipcodes()
    {
        $this->assertStateIsGeocoded('RI');
    }

    /**
    * @test
    */
    public function it_geocodes_south_carolina_zipcodes()
    {
        $this->assertStateIsGeocoded('SC');
    }

    /**
    * @test
    */
    public function it_geocodes_south_dakota_zipcodes()
    {
        $this->assertStateIsGeocoded('SD');
    }

    /**
    * @test
    */
    public function it_geocodes_tennessee_zipcodes()
    {
        $this->assertStateIsGeocoded('TN');
    }

    /**
    * @test
    */
    public function it_geocodes_texas_zipcodes()
    {
        $this->assertStateIsGeocoded('TX');
    }

    /**
    * @test
    */
    public function it_geocodes_utah_zipcodes()
    {
        $this->assertStateIsGeocoded('UT');
    }

    /**
    * @test
    */
    public function it_geocodes_vermont_zipcodes()
    {
        $this->assertStateIsGeocoded('VT');
    }

    /**
    * @test
    */
    public function it_geocodes_virginia_zipcodes()
    {
        $this->assertStateIsGeocoded('VA');
    }

    /**
    * @test
    */
    public function it_geocodes_washington_zipcodes()
    {
        $this->assertStateIsGeocoded('WA');
    }

    /**
    * @test
    */
    public function it_geocodes_west_virginia_zipcodes()
    {
        $this->assertStateIsGeocoded('WV');
    }

    /**
    * @test
    */
    public function it_geocodes_wisconsin_zipcodes()
    {
        $this->assertStateIsGeocoded('WI');
    }

    /**
    * @test
    */
    public function it_geocodes_wyoming_zipcodes()
    {
        $this->assertStateIsGeocoded('WY');
    }

    /**
    * @test
    */
    public function it_geocodes_hawaii_zipcodes()
    {
        $this->assertStateIsGeocoded('HI');
    }

    /**
    * @test
    */
    public function it_geocodes_alaska_zipcodes()
    {
        $this->assertStateIsGeocoded('AK');
    }

    /**
    * @test
    */
    public function it_geocodes_district_of_columbia_zipcodes()
    {
        $this->assertStateIsGeocoded('DC');
    }

    /**
    * @test
    */
    public function it_geocodes_past_issue_zipcodes()
    {
        $this->assertLocationsAreGeocoded(
            $this->getPastIssueLocations()
        );
    }

    protected function assertStateIsGeocoded($abbreviation)
    {
        $this->assertLocationsAreGeocoded(
            $this->getStates()->get($abbreviation)
        );
    }

    protected function assertLocationsAreGeocoded(Collection $locations)
    {
        $user = factory(User::class)->create();

        $locations->each(function ($location) use ($user) {
            $user->professionalInformation->zip_code = $location->get('zipcode');
            $user->professionalInformation->save();

            $address = "{$location->get('city')} {$location->get('name')}, {$location->get('zipcode')}";
            $geoloc = $user->fresh()->toSearchableArray()['_geoloc'] ?? null;
            $this->assertNotNull($geoloc, "Geocode result is null for {$address}");
            $this->assertCount(2, $geoloc, "Geocode result is missing for {$address}");
        });
    }

    protected function getStates()
    {
        include base_path('tests/mocks/files/states-and-zips.php');

        return $states;
    }

    protected function getPastIssueLocations()
    {
        include base_path('tests/mocks/files/past-issue-locations.php');

        return $locations;
    }
}
