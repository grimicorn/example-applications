<?php

namespace App\QueryBuilder\Sorts;

use App\Domain\Supports\Geocoder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DistanceSort implements \Spatie\QueryBuilder\Sorts\Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
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

        return $query->orderBy(
            DB::raw("
                (select ST_Distance_Sphere(
                    point(locations.longitude, locations.latitude),
                    point({$longitude}, {$latitude})
                ) * 0.000621371192)
            "),
            $descending ? 'desc' : 'asc'
        );
    }
}
