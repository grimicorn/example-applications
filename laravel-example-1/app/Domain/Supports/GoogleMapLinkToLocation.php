<?php

namespace App\Domain\Supports;

use App\Domain\Supports\Geocoder;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Collection;

class GoogleMapLinkToLocation
{
    public function firstOrCreate(string $link, array $attributes = [], ?User $user = null): Location
    {
        $attributes = collect($attributes);

        return $this->storeLocation($link, $attributes, $user)
            ->storeTaxonomyByNames(explode(',', $attributes->get('tags', '')), 'tag')
            ->fresh();
    }

    protected function splitLinkParts($link)
    {
        $link = str_replace('https://www.google.com/maps/place/', '', $link);
        $parts = collect(explode('/', $link));
        $coordinates = collect(explode(',', $parts->get(1)));

        return collect([
            'latitude' => str_replace('@', '', $coordinates->get(0)),
            'longitude' => $coordinates->get(1),
            'name' => urldecode($parts->get(0)),
        ]);
    }

    protected function storeLocation(string $link, Collection $attributes, ?User $user = null)
    {
        $user = $user ?? auth()->user();
        $parts = $this->splitLinkParts($link);

        $location = Location::firstOrNew(
            array_merge(
                [
                    'name' => $parts->get('name'),
                    'user_id' => $user->id,
                ],
                $this->addressComponents(
                    $parts->get('latitude'),
                    $parts->get('longitude')
                )
            ),
            array_merge(
                [
                    'timezone' => (new Timezone)->fromCoordinates(
                        $parts->get('latitude'),
                        $parts->get('longitude')
                    ),
                ],
                $parts->toArray(),
                $attributes->except(['tags'])->toArray()
            ),
        );

        $location = $location->setGeocodeAttributesFromAddress($location->address);
        $location->save();

        return $location;
    }

    protected function addressComponents(float $latitude, float $longitude): array
    {
        $components = (new Geocoder)->getAddressForCoordinates($latitude, $longitude)
            ->get('address_components', collect());

        return [
            'route' => $this->componentByKey($components, 'route')->long_name,
            'locality' => $this->componentByKey($components, 'locality')->long_name,
            'administrative_area_level_1' => $this->componentByKey(
                $components,
                'administrative_area_level_1'
            )->long_name,
            'administrative_area_level_1_abbreviation' => $this->componentByKey(
                $components,
                'administrative_area_level_1'
            )->short_name,
            'country' => $this->componentByKey($components, 'country')->short_name,
            'postal_code' => $this->componentByKey($components, 'postal_code')->short_name,
        ];
    }

    protected function componentByKey(Collection $components, string $key)
    {
        return optional($components->filter(function ($item) use ($key) {
            return in_array($key, $item->types);
        })->first());
    }
}
