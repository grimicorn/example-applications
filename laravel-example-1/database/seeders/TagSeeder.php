<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            'Mountain',
            'Waterfall',
            'Beach',
            'Lake',
            'Sunset',
            'Sunrise',
            'Drone',
            'Trail',
            'Forest',
            'Lodging',
            'Food',
            'Home',
            'Colorado 2020',
            'Visit Next',
        ])->each(function ($name) {
            if ($name) {
                Tag::firstOrCreate(['name' => $name]);
            }
        });
    }
}
