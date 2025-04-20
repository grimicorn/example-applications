<?php

namespace Database\Factories;

use App\Models\LocationIcon;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationIconFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LocationIcon::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'filename' => $this->faker->slug(4, true),
        ];
    }
}
