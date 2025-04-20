<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\SearchLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

class SearchLocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SearchLocation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'address' => $this->faker->address(),
            'user_id' => User::factory(),
        ];
    }
}
