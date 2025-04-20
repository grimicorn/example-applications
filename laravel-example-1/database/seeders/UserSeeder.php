<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! User::where('email', 'dtholloran@gmail.com')->first()) {
            User::factory()->create([
                'name' => 'dholloran',
                'email' => 'dtholloran@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt("^U<a]dUCgrjq=npsygy%kB$5'Vn+!("),
            ]);
        }
    }
}
