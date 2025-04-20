<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Tag;
use App\Models\Taggable;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaggableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Taggable::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tag_id' => Tag::factory(),
            'taggable_id' => Location::factory(),
            'taggable_type' => Location::class,
        ];
    }
}
