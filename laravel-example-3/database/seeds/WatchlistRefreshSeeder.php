<?php

use App\User;
use App\Listing;
use App\SavedSearch;
use Illuminate\Database\Seeder;

class WatchlistRefreshSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $listings = factory(Listing::class, 100)->states('published')->create([
            'user_id' => factory(User::class)->create([
                'email' => md5(uniqid() . time()) . '@' . md5(uniqid() . time()) . '.test',
            ])->id,
        ]);
        $titles = $listings->pluck('title');
        $zip_codes = $listings->pluck('zip_code');

        $watchlist_user_1 = factory(User::class)->create([
            'email' => md5(uniqid() . time()) . '@' . md5(uniqid() . time()) . '.test',
        ]);
        $watchlist_user_2 = factory(User::class)->create([
            'email' => md5(uniqid() . time()) . '@' . md5(uniqid() . time()) . '.test',
        ]);
        $watchlist_user_3 = factory(User::class)->create([
            'email' => md5(uniqid() . time()) . '@' . md5(uniqid() . time()) . '.test',
        ]);
        $watchlist_user_4 = factory(User::class)->create([
            'email' => md5(uniqid() . time()) . '@' . md5(uniqid() . time()) . '.test',
        ]);
        $watchlist_user_5 = factory(User::class)->create([
            'email' => md5(uniqid() . time()) . '@' . md5(uniqid() . time()) . '.test',
        ]);
        $watchlist_user_6 = factory(User::class)->create([
            'email' => md5(uniqid() . time()) . '@' . md5(uniqid() . time()) . '.test',
        ]);
        $watchlist_user_7 = factory(User::class)->create([
            'email' => md5(uniqid() . time()) . '@' . md5(uniqid() . time()) . '.test',
        ]);
        $watchlist_user_8 = factory(User::class)->create([
            'email' => md5(uniqid() . time()) . '@' . md5(uniqid() . time()) . '.test',
        ]);

        factory(SavedSearch::class, 25)->states('asking-price-min-only')->create([
            'user_id' => $watchlist_user_1->id,
        ]);
        factory(SavedSearch::class, 25)->states('asking-price-min-only')->create([
            'user_id' => $watchlist_user_2->id,
        ]);
        factory(SavedSearch::class, 25)->states('asking-price-max-only')->create([
            'user_id' => $watchlist_user_3->id,
        ]);
        factory(SavedSearch::class, 25)->states('asking-price-max-only')->create([
            'user_id' => $watchlist_user_4->id,
        ]);
        for ($i=0; $i < 10; $i++) {
            factory(SavedSearch::class)->states('empty')->create([
                'keyword' => $titles->random(),
                'user_id' => $watchlist_user_5->id,
            ]);
            factory(SavedSearch::class)->states('empty')->create([
                'keyword' => $titles->random(),
                'user_id' => $watchlist_user_6->id,
            ]);
        }
        for ($i = 0; $i < 10; $i++) {
            factory(SavedSearch::class)->states('empty')->create([
                'zip_code' => $zip_codes->random(),
                'zip_code_radius' => $faker->numberBetween(5, 100),
                'user_id' => $watchlist_user_7->id,
            ]);
            factory(SavedSearch::class)->states('empty')->create([
                'zip_code' => $zip_codes->random(),
                'zip_code_radius' => $faker->numberBetween(5, 100),
                'user_id' => $watchlist_user_8->id,
            ]);
        }
    }
}
