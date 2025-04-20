<?php

use App\User;
use App\Enums\UserRoleEnum;
use Illuminate\Database\Seeder;

class DeveloperAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            [
                'name' => 'Dan Holloran',
                'email' => 'dholloran@matchboxdesigngroup.com',
            ],
            [
                'name' => 'Jim Courtois',
                'email' => 'jcourtois@matchboxdesigngroup.com',
            ],
            [
                'name' => 'Cullen Whitmore',
                'email' => 'cwhitmore@matchboxdesigngroup.com',
            ],
            [
                'name' => 'Vanessa Sickles',
                'email' => 'vsickles@matchboxdesigngroup.com',
            ],
        ])->each(function ($attributes) {
            $attributes = array_merge([
                'password' => bcrypt('password'),
            ], array_filter($attributes));
            User::firstOrCreate($attributes)->assignRole(UserRoleEnum::DEVELOPER);
        });
    }
}
