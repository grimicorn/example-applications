<?php

namespace Database\Factories;

use App\Domain\Supports\Location;
use App\Models\Linkable;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Linkable::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'linkable_type' => Location::class,
            'linkable_id' => Location::factory(),
        ];
    }
}
