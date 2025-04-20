<?php

use App\Site;
use App\User;
use Illuminate\Database\Seeder;
use App\Snapshot;

class SiteNeedsReviewMailableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::whereEmail($email = 'site-needs-review-example@srcwatch.io')->first();
        if ($user) {
            return;
        }

        $user = factory(User::class)->create([
            'email' => $email = 'site-needs-review-example@srcwatch.io',
        ]);

        $site = factory(Site::class)->create([
            'user_id' => $user->id,
            'sitemap_url' => url('sitemap-example.xml'),
            'name' => 'srcWatch',
            'difference_threshold' => .9,
        ]);

        $site->fresh()->pages()->each(function ($page) {
            factory(Snapshot::class)->states('baseline')->create([
                'snapshot_configuration_id' => $page->snapshotConfigurations()->first()->id,
            ]);

            factory(Snapshot::class)->create([
                'difference' => .5,
                'snapshot_configuration_id' => $page->snapshotConfigurations()->first()->id,
            ]);
        });

        $this->command->info(
            "Mailable SiteNeedsReview example for user email \"{$email}\" and side id \"{$site->id}\" created."
        );
    }
}
