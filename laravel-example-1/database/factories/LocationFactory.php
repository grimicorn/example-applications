<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Location;
use App\Enums\LocationAccessDifficultyEnum;
use App\Enums\LocationTrafficLevelEnum;
use Spatie\Enum\Laravel\Faker\FakerEnumProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Location::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        FakerEnumProvider::register();

        return [
            'name' => $name = $this->faker->word(),
            'latitude' => 37.1660768,
            'longitude' => -91.1643914,
            'google_maps_link' => "https://www.google.com/maps/place/{$name}/@37.1660768,-91.1643914,17z/data=!3m1!4b1!4m9!1m3!11m2!2s4wqcAsHvNUhImW3yFICJ_IbwLYq94g!3e3!3m4!1s0x87d753b261811af1:0x9faa818714a30c30!8m2!3d37.1660705!4d-91.1622008",
            'user_id' => User::factory(),
            'route' => 'Blue Spring',
            'locality' => 'Eminence Township',
            'administrative_area_level_1_abbreviation' => 'MO',
            'administrative_area_level_1' => 'Missouri',
            'country' => 'US',
            'postal_code' => '63638',
            'timezone' => 'America/Chicago',
            'notes' => $this->faker->paragraphs(3, $asText = true),
        ];
    }

    public function blueSpring()
    {
        return $this->state(function () {
            return [
                'name' => 'Blue Spring',
                'latitude' => 37.1660768,
                'longitude' => -91.1643914,
                'google_maps_link' => 'https://www.google.com/maps/place/Blue+Spring/@37.1660768,-91.1643914,17z/data=!3m1!4b1!4m9!1m3!11m2!2s4wqcAsHvNUhImW3yFICJ_IbwLYq94g!3e3!3m4!1s0x87d753b261811af1:0x9faa818714a30c30!8m2!3d37.1660705!4d-91.1622008',
                'route' => 'Blue Spring',
                'locality' => 'Eminence Township',
                'administrative_area_level_1_abbreviation' => 'MO',
                'administrative_area_level_1' => 'Missouri',
                'country' => 'US',
                'postal_code' => '63638',
            ];
        });
    }

    public function oldRedingsMillBridge()
    {
        return $this->state(function () {
            return [
                'name' => 'Old Redings Mill Bridge',
                'latitude' => 37.0209741,
                'longitude' => -94.514532,
                'google_maps_link' => 'https://www.google.com/maps/place/Old+Redings+Mill+Bridge/@37.0209741,-94.5145316,17z/data=!3m1!4b1!4m9!1m3!11m2!2s4wqcAsHvNUhImW3yFICJ_IbwLYq94g!3e3!3m4!1s0x87c864dd19b5121b:0x5e26cd4348fd4417!8m2!3d37.0209698!4d-94.5123429',
                'route' => 'Redings Mill Rd',
                'locality' => 'Joplin',
                'administrative_area_level_1_abbreviation' => 'MO',
                'administrative_area_level_1' => 'Missouri',
                'country' => 'US',
                'postal_code' => '64804',
            ];
        });
    }

    public function maramecSpringPark()
    {
        return $this->state(function () {
            return [
                'name' => 'Maramec Spring Park',
                'latitude' => 37.9548132,
                'longitude' => -91.5313397,
                'google_maps_link' => 'https://www.google.com/maps/place/Maramec+Spring+Park/@37.9548132,-91.5313397,17z/data=!3m1!4b1!4m9!1m3!11m2!2s4wqcAsHvNUhImW3yFICJ_IbwLYq94g!3e3!3m4!1s0x87da4aa4e69241e1:0xc33e058504f49433!8m2!3d37.954809!4d-91.529151',
                'route' => '21880 Maramec Spring Dr',
                'locality' => 'St James',
                'administrative_area_level_1_abbreviation' => 'MO',
                'administrative_area_level_1' => 'Missouri',
                'country' => 'US',
                'postal_code' => '65559',
            ];
        });
    }
}
