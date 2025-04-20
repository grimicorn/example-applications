<?php

namespace App\Domain\Supports;

use App\Domain\Supports\Location;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Geocoder\Facades\Geocoder as BaseGeocoder;

class Geocoder
{
    protected $disabled = false;

    public function disable()
    {
        $this->disabled = true;

        return $this;
    }

    public function enable()
    {
        $this->disabled = false;

        return $this;
    }

    public function isDisabled()
    {
        return $this->disabled;
    }

    public function geocodeAddress(?string $address, bool $fresh = false)
    {
        if (! $address or $this->isDisabled()) {
            return $this->defaultGeocodeResponse();
        }

        $key = 'geocoded_address.'.sha1($address);

        if ($fresh) {
            Cache::forget($key);
        }

        return Cache::tags(['geocoded_addresses'])
            ->rememberForever($key, function () use ($address) {
                $result = r_collect(BaseGeocoder::getCoordinatesForAddress($address));
                if ($this->geocodeNotFound($result)) {
                    return $this->defaultGeocodeResponse();
                }

                return $result;
            });
    }

    public function getAddressForCoordinates(?float $latitude, ?float $longitude, bool $fresh = false)
    {
        $key = 'address_for_coordinates.'.sha1("{$latitude},{$longitude}");

        if ($fresh) {
            Cache::forget($key);
        }

        return Cache::tags(['addresses_for_coordinates'])
            ->rememberForever($key, function () use ($latitude, $longitude) {
                try {
                    return r_collect(BaseGeocoder::getAddressForCoordinates($latitude, $longitude));
                } catch (\Exception $e) {
                    return $this->defaultGeocodeResponse();
                }
            });
    }

    public function distanceBetweenLocations(Location $location1, Location $location2)
    {
        $result = DB::selectOne(
            DB::raw('
                select ST_Distance_Sphere(
                    point(:longitude1, :latitude1),
                    point(:longitude2, :latitude2)
                ) * 0.000621371192
            '),
            [
                'longitude1' => $location1->longitude(),
                'latitude1' => $location1->latitude(),
                'longitude2' => $location2->longitude(),
                'latitude2' => $location2->latitude(),
            ]
        );

        return collect($result)->first();
    }

    protected function defaultGeocodeResponse()
    {
        return collect([
            'lat' => null,
            'lng' => null,
            'accuracy' => 'result_not_found',
            'formatted_address' => 'result_not_found',
            'viewport' => 'result_not_found',
        ]);
    }

    protected function geocodeNotFound(Collection $geocode)
    {
        return $geocode->get('accuracy') === 'result_not_found';
    }
}
