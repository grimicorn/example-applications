<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('users')->insert([
            'name' => 'Dan Holloran',
            'email' => 'dtholloran@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'administrator',
        ]);

        DB::table('users')->insert([
            'name' => 'Test User',
            'email' => 'test-user@gmail.com',
            'password' => bcrypt('password'),
        ]);
    }
}
