<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SharedEnvSeeder::class);

        // Login the system user so the job created event will have a user.
        Auth::login(User::where('name', 'System')->first());

        $this->call(LocalSeeder::class);
        $this->call(StagingSeeder::class);
    }
}
