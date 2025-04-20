<?php

use App\ExchangeSpace;
use Illuminate\Database\Seeder;

class ExchangeSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BusinessCategoriesSeeder::class);
        foreach (factory(App\ExchangeSpace::class, 50)->create() as $space) {
            // Create a seller
            factory('App\ExchangeSpaceMember')
            ->states('seller')
            ->create([
                'exchange_space_id' => $space->id,
                'user_id' => $space->user->id,
            ]);

            // Create a buyer
            factory('App\ExchangeSpaceMember')
            ->states('buyer')
            ->create(['exchange_space_id' => $space->id]);

            // Create random users.
            factory('App\ExchangeSpaceMember', 10)
            ->states('not-seller-or-buyer')
            ->create(['exchange_space_id' => $space->id]);
        }
    }
}
