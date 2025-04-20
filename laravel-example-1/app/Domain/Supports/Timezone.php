<?php

namespace App\Domain\Supports;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Timezone
{
    public function fromCoordinates(float $latitude, float $longitude, bool $fresh = false): ?string
    {
        $key = 'timestamp_for_coordinates.'.sha1("{$latitude},{$longitude}");

        if ($fresh) {
            Cache::forget($key);
        }

        return Cache::tags(['timestamps_for_coordinates'])
            ->rememberForever($key, function () use ($latitude, $longitude) {
                $response = Http::get($this->getUrl($latitude, $longitude));
                if (! $response->successful()) {
                    return null;
                }

                return $response->json()['timeZoneId'] ?? null;
            });
    }

    protected function getUrl(float $latitude, float $longitude): string
    {
        $time = time();
        $key = config('geocoder.key');
        $args = collect([
            "location={$latitude},{$longitude}",
            "timestamp={$time}",
            "key={$key}",
        ])->implode('&');

        return "https://maps.googleapis.com/maps/api/timezone/json?{$args}";
    }
}
