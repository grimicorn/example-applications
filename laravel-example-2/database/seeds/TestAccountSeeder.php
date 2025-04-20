<?php

use App\User;
use App\Enums\UserRoleEnum;
use Illuminate\Database\Seeder;

class TestAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'name' => 'Test Developer',
            'email' => 'test-developer@example.com',
            'password' => bcrypt('password'),
        ])->assignRole(UserRoleEnum::DEVELOPER);

        User::firstOrCreate([
            'name' => 'Test Administrator',
            'email' => 'test-administrator@example.com',
            'password' => bcrypt('password'),
        ])->assignRole(UserRoleEnum::ADMINISTRATOR);
    }
}
