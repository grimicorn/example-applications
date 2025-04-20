<?php

namespace App\QueryBuilder\Filters;

use App\Domain\Supports\Geocoder;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FilterMaxDistance implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if (! request()->has('address')) {
            return;
        }

        $location = resolve(Geocoder::class)->enable()->geocodeAddress(request('address'));
        $latitude = $location->get('lat');
        $longitude = $location->get('lng');

        if (is_null($latitude) or is_null($longitude)) {
            return;
        }

        $query->whereRaw('
            ST_Distance_Sphere(
                point(longitude, latitude),
                point(?, ?)
            ) * .000621371192 < ?
        ', [
            $longitude,
            $latitude,
            $value,
        ]);
    }
}
