<?php

use App\Site;
use App\User;
use Illuminate\Database\Seeder;

class LocalUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::whereEmail('dtholloran@gmail.com')->exists()) {
            return;
        }

        $user = factory(User::class)->states('verified')->create([
            'email' => 'dtholloran@gmail.com',
            'password' => bcrypt($password = 'password'),
            'first_name' => 'Dan',
            'last_name' => 'Holloran',
        ]);

        factory(Site::class)->create([
            'user_id' => $user->id,
            'sitemap_url' => url('sitemap-example.xml'),
            'name' => 'srcWatch',
        ]);

        $this->command->info(
            "Test password for dtholloran@gmail.com is \"{$password}\""
        );
    }
}
