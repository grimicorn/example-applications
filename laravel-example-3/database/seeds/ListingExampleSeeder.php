<?php

use App\Support\SeederHelper;
use Illuminate\Database\Seeder;

class ListingExampleSeeder extends Seeder
{
    use SeederHelper;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Make sure we have business categories
        $this->call(BusinessCategoriesSeeder::class);

        // Add the examples.
        for ($i = 1; $i <= 3; $i++) {
            // Example 1
            $listing1Attributes = factory('App\Listing')
                                  ->states("example-{$i}")->make();

            // Split out the photo
            $photo = $listing1Attributes['listing_photo'];
            unset($listing1Attributes['listing_photo']);

            // Creates the listing
            $listing = factory('App\Listing')->create(
                $this->removeNonFillableToArray($listing1Attributes)
            );

            $this->addPhoto($listing, $photo, 'photos');
        }
    }
}
