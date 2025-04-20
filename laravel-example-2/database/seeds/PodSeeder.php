<?php

use App\Pod;
use Illuminate\Database\Seeder;

class PodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Pod::class, 10)->create();
    }
}
