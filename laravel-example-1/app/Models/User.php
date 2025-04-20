<?php

namespace App\Models;

use App\Domain\Supports\Geocoder;
use App\Domain\Supports\Location as GeocoderLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function getCurrentLocationAttribute()
    {
        $location = optional(new \stdClass);
        $location->latitude = session('currentLocation', collect())->get('latitude');
        $location->longitude = session('currentLocation', collect())->get('longitude');
        $location->address = session('currentLocation', collect())->get('address');

        return $location;
    }

    public function getDistanceBetweenAddressAndCurrentLocation($address)
    {
        $current = $this->currentLocation;
        if (! $address or is_null($current->latitude) or is_null($current->longitude)) {
            return;
        }
        $geocoder = resolve(Geocoder::class);
        $addressCoordinates = $geocoder->geocodeAddress($address);

        return $geocoder->distanceBetweenLocations(
            new GeocoderLocation($addressCoordinates->get('lng'), $addressCoordinates->get('lat')),
            new GeocoderLocation($current->longitude, $current->latitude)
        );
    }

    public function getCurrentLocation($latitude, $longitude)
    {
        session([
            'currentLocation' => $location = collect([
                'latitude' => $latitude,
                'longitude' => $longitude,
                'address' => resolve(Geocoder::class)->enable()->getAddressForCoordinates(
                    $latitude,
                    $longitude
                )->get('formatted_address'),
            ]),
        ]);

        return $location;
    }

    protected function getSearchAddressDistanceAttribute()
    {
        return $this->getDistanceBetweenAddressAndCurrentLocation(request('address'));
    }

    public function searchLocations()
    {
        return $this->hasMany(SearchLocation::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
