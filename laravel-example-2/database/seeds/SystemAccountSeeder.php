<?php

use App\User;
use App\Enums\UserRoleEnum;
use Illuminate\Database\Seeder;

class SystemAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate([
            'name' => 'System',
            'email' => 'system@'.parse_url(url(''), PHP_URL_HOST),
            'password' => bcrypt('password'),
        ])->assignRole(UserRoleEnum::SYSTEM);
    }
}
