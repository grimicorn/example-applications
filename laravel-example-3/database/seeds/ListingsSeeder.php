<?php

use Illuminate\Database\Seeder;

class ListingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Listing', 50)->create();
    }
}
