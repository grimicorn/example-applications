<?php

use App\MachinePod;
use Illuminate\Database\Seeder;

class MachinePodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MachinePod::class, 10)->create();
    }
}
