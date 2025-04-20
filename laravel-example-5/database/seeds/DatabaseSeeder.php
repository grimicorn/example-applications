<?php

use App\Site;
use App\User;
use Illuminate\Database\Seeder;

//phpcs:ignorefile
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            \LocalUsersSeeder::class,
            \SiteNeedsReviewMailableSeeder::class,
        ]);
    }
}
