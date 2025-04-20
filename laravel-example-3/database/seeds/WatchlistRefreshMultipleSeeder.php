<?php

use Illuminate\Database\Seeder;

class WatchlistRefreshMultipleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 5; $i++) {
            $this->call(\WatchlistRefreshSeeder::class);
            sleep(1);
        }
    }
}
