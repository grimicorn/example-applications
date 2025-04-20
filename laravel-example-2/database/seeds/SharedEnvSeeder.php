<?php

use Illuminate\Database\Seeder;

class SharedEnvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserRoleSeeder::class);
        $this->call(SystemAccountSeeder::class);
    }
}
