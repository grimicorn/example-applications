<?php

namespace App\Support;

use App\Support\Geocode\Mapquest;
use Illuminate\Support\Facades\Log;

trait GetsCoordinates
{
    public function getZipCodeCoordinates($zipCode)
    {
        $location = $this->getLocationByZipCode($zipCode);

        if (!$location) {
            return [];
        }

        try {
            return [
                'lat' => $location->get('latLng')->get('lat'),
                'lng' => $location->get('latLng')->get('lng'),
            ];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getLocationByZipCode($zipCode)
    {
        return (new Mapquest)->getLocationByZipCode($zipCode);
    }
}
