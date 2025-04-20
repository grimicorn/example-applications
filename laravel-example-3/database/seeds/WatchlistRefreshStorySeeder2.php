<?php

use App\User;
use App\Listing;
use App\SavedSearch;
use Illuminate\Database\Seeder;

// phpcs:ignore
class WatchlistRefreshStorySeeder2 extends Seeder
{
    use HasSeederHelpers;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $buyer1 = User::where('email', 'wlbuyer01@mailinator.com')->first();
        $buyer2 = User::where('email', 'wlbuyer02@mailinator.com')->first();
        $buyer3 = User::where('email', 'wlbuyer03@mailinator.com')->first();
        $seller1 = User::where('email', 'wlseller01@mailinator.com')->first();
        $seller2 = User::where('email', 'wlseller02@mailinator.com')->first();
        $seller3 = User::where('email', 'wlseller03@mailinator.com')->first();
        $search1 = SavedSearch::where([
            'name' => 'Retail sales charlotte',
            'user_id' => $buyer1->id,
        ])->first();
        $search2 = SavedSearch::where([
            'name' => 'bar keyword ',
            'user_id' => $buyer1->id,
        ])->first();
        $search3 = SavedSearch::where([
            'name' => 'test keyword',
            'user_id' => $buyer1->id,
        ])->first();
        $search4 = SavedSearch::where([
            'name' => 'NYC distro',
            'user_id' => $buyer2->id,
        ])->first();
        $search5 = SavedSearch::where([
            'name' => 'under 2mm',
            'user_id' => $buyer2->id,
        ])->first();
        $search6 = SavedSearch::where([
            'name' => 'NY retail 500k',
            'user_id' => $buyer2->id,
        ])->first();
        $search7 = SavedSearch::where([
            'name' => 'Tampa BPS',
            'user_id' => $buyer3->id,
        ])->first();
        $search8 = SavedSearch::where([
            'name' => 'Tampa car wash',
            'user_id' => $buyer3->id,
        ])->first();
        $search9 = SavedSearch::where([
            'name' => 'Retail under 1mm',
            'user_id' => $buyer3->id,
        ])->first();


        // Seller 1
        $listing10 = $this->listingOrCreate([
            'user_id' => $seller1->id,
            'title' => 'Test listing 10',
            'business_category_id' => $this->getBusinesCategoryId('Retail Sales'),
            'business_sub_category_id' => $this->getBusinesCategoryId('Gas Station'),
            'zip_code' => 28204,
            'asking_price' => 250000,
            'summary_business_description' => 'test',
            'business_description' => null,
        ]);

        // Seller 2
        $listing11 = $this->listingOrCreate([
            'user_id' => $seller2->id,
            'title' => 'Test listing 11',
            'business_category_id' => $this->getBusinesCategoryId('Business & Personal Services'),
            'business_sub_category_id' => $this->getBusinesCategoryId('Health'),
            'zip_code' => 33615,
            'asking_price' => 75000,
            'summary_business_description' => 'test',
            'business_description' => null,
        ]);
        $listing12 = $this->listingOrCreate([
            'user_id' => $seller2->id,
            'title' => 'Test listing 12',
            'business_category_id' => $this->getBusinesCategoryId('Business & Personal Services'),
            'business_sub_category_id' => $this->getBusinesCategoryId('Car Wash'),
            'zip_code' => 33613,
            'asking_price' => 850000,
            'summary_business_description' => 'test',
            'business_description' => null,
        ]);

        // Seller 3
        $listing13 = $this->listingOrCreate([
            'user_id' => $seller3->id,
            'title' => 'Test listing 13',
            'business_category_id' => $this->getBusinesCategoryId('Retail Sales'),
            'business_sub_category_id' => $this->getBusinesCategoryId('Restaurants'),
            'zip_code' => 10009,
            'asking_price' => 385000,
            'summary_business_description' => 'test',
            'business_description' => 'bar',
        ]);

        // Make sure the current state is reset
        $this->resetApplicationState();

        $spacer = '    ';

        echo "\n** Story 2 **************************\n\n";
        echo "Buyer 1: {$buyer1->name} | {$buyer1->email} | {$buyer1->id}\n";
        echo "{$spacer}Saved Search: {$search1->name} | {$search1->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing10->title} | {$listing10->id}\n";
        echo "{$spacer}Saved Search: {$search2->name} | {$search2->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing13->title} | {$listing13->id}\n";
        echo "{$spacer}Saved Search: {$search3->name} | {$search3->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing10->title} | {$listing10->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing11->title} | {$listing11->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing12->title} | {$listing12->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing13->title} | {$listing13->id}\n";

        echo "\n*************************************\n\n";
        echo "Buyer 2: {$buyer2->name} | {$buyer2->email} | {$buyer2->id}\n";
        echo "{$spacer}Saved Search: {$search4->name} | {$search4->id}:\n";
        echo "No new listings";
        echo "{$spacer}Saved Search: {$search5->name} | {$search5->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing10->title} | {$listing10->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing11->title} | {$listing11->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing12->title} | {$listing12->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing13->title} | {$listing13->id}\n";
        echo "{$spacer}Saved Search: {$search6->name} | {$search6->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing13->title} | {$listing13->id}\n";

        echo "\n*************************************\n\n";
        echo "Buyer 3: {$buyer3->name} | {$buyer3->email} | {$buyer3->id}\n";
        echo "{$spacer}Saved Search: {$search7->name} | {$search7->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing11->title} | {$listing11->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing12->title} | {$listing12->id}\n";
        echo "{$spacer}Saved Search: {$search8->name} | {$search8->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing12->title} | {$listing12->id}\n";
        echo "{$spacer}Saved Search: {$search9->name} | {$search9->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing10->title} | {$listing10->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing13->title} | {$listing13->id}\n";
    }
}
