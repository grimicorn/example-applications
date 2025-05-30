<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BusinessCategoriesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ListingsSeeder::class);
        $this->call(ExchangeSpaceSeeder::class);
    }
}
