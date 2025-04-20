<?php

namespace App\Support\Geocode;

use Zttp\Zttp;
use Illuminate\Support\Facades\Cache;

class Mapquest
{
    protected $key;

    public function __construct()
    {
        $this->key = config('services.mapquest.api_key');
        $this->url = 'http://www.mapquestapi.com/geocoding/v1/address';
    }

    public function getLocationByZipCode($zipCode)
    {
        return $this->get([
            'postalCode' => $zipCode,
        ])
        ->where('adminArea1', 'US')
        ->where('postalCode', $zipCode)
        ->first();
    }

    protected function get(array $params = [])
    {
        $params['adminArea1'] = 'US';
        $cacheKey = 'geocode-location:' . md5(implode('.', $params));
        $params['key'] = $this->key;

        return Cache::rememberForever($cacheKey, function () use ($params) {
            try {
                return r_collect(
                    Zttp::get($this->url, $params)->json()
                )
                ->get('results', collect([]))
                ->first()
                ->get('locations', collect([]));
            } catch (\Exception $e) {
                return collect([]);
            }
        });
    }
}
