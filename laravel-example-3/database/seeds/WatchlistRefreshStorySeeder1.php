<?php

use App\User;
use App\Listing;
use App\SavedSearch;
use App\BusinessCategory;
use Illuminate\Database\Seeder;

// phpcs:ignore
class WatchlistRefreshStorySeeder1 extends Seeder
{
    use HasSeederHelpers;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Make sure the business categories are seeded.
        $this->call(\BusinessCategoriesSeeder::class);

        // Buyers
        $buyer1 = $this->userByEmailOrCreate('wlbuyer01@mailinator.com');
        $buyer2 = $this->userByEmailOrCreate('wlbuyer02@mailinator.com');
        $buyer3 = $this->userByEmailOrCreate('wlbuyer03@mailinator.com');

        // Sellers
        $seller1 = $this->userByEmailOrCreate('wlseller01@mailinator.com');
        $seller2 = $this->userByEmailOrCreate('wlseller02@mailinator.com');
        $seller3 = $this->userByEmailOrCreate('wlseller03@mailinator.com');

        // Saved Searches

        // Buyer 1
        $search1 = $this->savedSearchOrCreate([
            'user_id' => $buyer1->id,
            'name' => 'Retail sales charlotte',
            'business_categories' => $this->getBusinesCategoryIds(['Retail Sales']),
            'zip_code' => 28202,
            'zip_code_radius' => 50,
            'asking_price_min' => null,
            'asking_price_max' => null,
            'keyword' => null,
        ]);
        $search2 = $this->savedSearchOrCreate([
            'user_id' => $buyer1->id,
            'name'=> 'bar keyword',
            'business_categories' => $this->getBusinesCategoryIds(['Restaurants']),
            'zip_code' => null,
            'zip_code_radius' => null,
            'asking_price_min' => null,
            'asking_price_max' => null,
            'keyword' => 'bar',
        ]);
        $search3 = $this->savedSearchOrCreate([
            'user_id' => $buyer1->id,
            'name'=> 'test keyword',
            'business_categories' => null,
            'zip_code' => null,
            'zip_code_radius' => null,
            'asking_price_min' => null,
            'asking_price_max' => null,
            'keyword' => 'test',
        ]);

        // Buyer 2
        $search4 = $this->savedSearchOrCreate([
            'user_id' => $buyer2->id,
            'name' => 'NYC distro',
            'business_categories' => $this->getBusinesCategoryIds(['Wholesale & Distribution']),
            'zip_code' => '10003',
            'zip_code_radius' => 25,
            'asking_price_min' => null,
            'asking_price_max' => 750000,
            'keyword' => null,
        ]);
        $search5 = $this->savedSearchOrCreate([
            'user_id' => $buyer2->id,
            'name' => 'under 2mm',
            'business_categories' => null,
            'zip_code' => null,
            'zip_code_radius' => null,
            'asking_price_min' => null,
            'asking_price_max' => 2000000,
            'keyword' => null,
        ]);
        $search6 = $this->savedSearchOrCreate([
            'user_id' => $buyer2->id,
            'name' => 'NY retail 500k',
            'business_categories' => $this->getBusinesCategoryIds(['Retail sales']),
            'zip_code' => '10003',
            'zip_code_radius' => 50,
            'asking_price_min' => null,
            'asking_price_max' => 500000,
            'keyword' => null,
        ]);

        // Buyer 3
        $search7 = $this->savedSearchOrCreate([
            'user_id' => $buyer3->id,
            'name' => 'Tampa BPS',
            'business_categories' => $this->getBusinesCategoryIds(['Business & Personal Services']),
            'zip_code' => 33618,
            'zip_code_radius' => 50,
            'asking_price_min' => null,
            'asking_price_max' => 1000000,
            'keyword' => null,
        ]);
        $search8 = $this->savedSearchOrCreate([
            'user_id' => $buyer3->id,
            'name' => 'Tampa car wash',
            'business_categories' => $this->getBusinesCategoryIds(['Car Wash']),
            'zip_code' => 33618,
            'zip_code_radius' => 30,
            'asking_price_min' => null,
            'asking_price_max' => null,
            'keyword' => null,
        ]);
        $search9 = $this->savedSearchOrCreate([
            'user_id' => $buyer3->id,
            'name' => 'Retail under 1mm',
            'business_categories' => $this->getBusinesCategoryIds(['Retail Sales']),
            'zip_code' => null,
            'zip_code_radius' => null,
            'asking_price_min' => null,
            'asking_price_max' => 1000000,
            'keyword' => null,
        ]);

        // Listings

        // Seller 1
        $listing1 = $this->listingOrCreate([
            'user_id' => $seller1->id,
            'title' => 'Test listing 1',
            'business_category_id' => $this->getBusinesCategoryId('Retail Sales'),
            'business_sub_category_id' => $this->getBusinesCategoryId('Restaurants'),
            'zip_code' => 28203,
            'asking_price' => 600000,
            'summary_business_description'=> 'test',
            'business_description' => 'bar',
        ]);
        $listing2 = $this->listingOrCreate([
            'user_id' => $seller1->id,
            'title' => 'Test listing 2',
            'business_category_id' => $this->getBusinesCategoryId('Wholesale & Distribution'),
            'business_sub_category_id' => $this->getBusinesCategoryId('Durable Goods'),
            'zip_code' => 10005,
            'asking_price' => 400000,
            'summary_business_description'=> 'test',
            'business_description' => null,
        ]);
        $listing3 = $this->listingOrCreate([
            'user_id' => $seller1->id,
            'title' => 'Test listing 3',
            'business_category_id' => $this->getBusinesCategoryId('Retail Sales'),
            'business_sub_category_id' => $this->getBusinesCategoryId('Restaurants'),
            'zip_code' => 28205,
            'asking_price' => 650000,
            'summary_business_description'=> 'test',
            'business_description' => null,
        ]);

        // Seller 2
        $listing4 = $this->listingOrCreate([
            'user_id' => $seller2->id,
            'title' => 'Test listing 4',
            'business_category_id' => $this->getBusinesCategoryId('Retail Sales'),
            'business_sub_category_id' => $this->getBusinesCategoryId('Restaurants'),
            'zip_code' => 10007,
            'asking_price' => 350000,
            'summary_business_description' => 'test',
            'business_description' => 'bar',
        ]);
        $listing5 = $this->listingOrCreate([
            'user_id' => $seller2->id,
            'title' => 'Test listing 5',
            'business_category_id' => $this->getBusinesCategoryId('Business & Personal Services'),
            'business_sub_category_id' => $this->getBusinesCategoryId('Car Wash'),
            'zip_code' => 33625,
            'asking_price' => 150000,
            'summary_business_description' => 'test',
            'business_description' => null,
        ]);
        $listing6 = $this->listingOrCreate([
            'user_id' => $seller2->id,
            'title' => 'Test listing 6',
            'business_category_id' => $this->getBusinesCategoryId('Business & Personal Services'),
            'business_sub_category_id' => $this->getBusinesCategoryId('Car wash'),
            'zip_code' => 33610,
            'asking_price' => 1100000,
            'summary_business_description' => 'test',
            'business_description' => null,
        ]);

        // Seller 3
        $listing7 = $this->listingOrCreate([
            'user_id' => $seller3->id,
            'title' => 'Test listing 7',
            'business_category_id' => $this->getBusinesCategoryId('Retail Sales'),
            'business_sub_category_id' => $this->getBusinesCategoryId('Florists'),
            'zip_code' => 10009,
            'asking_price' => 250000,
            'summary_business_description' => 'test',
            'business_description' => null,
        ]);
        $listing8 = $this->listingOrCreate([
            'user_id' => $seller3->id,
            'title' => 'Test listing 8',
            'business_category_id' => $this->getBusinesCategoryId('Wholesale & Distribution'),
            'business_sub_category_id' => $this->getBusinesCategoryId('Nondurable Goods'),
            'zip_code' => 10005,
            'asking_price' => 275000,
            'summary_business_description' => 'test',
            'business_description' => null,
        ]);
        $listing9 = $this->listingOrCreate([
            'user_id' => $seller3->id,
            'title' => 'Test listing 9',
            'business_category_id' => $this->getBusinesCategoryId('Business & Personal Services'),
            'business_sub_category_id' => $this->getBusinesCategoryId('Plumbing'),
            'zip_code' => 33608,
            'asking_price' => 300000,
            'summary_business_description' => 'test',
            'business_description' => null,
        ]);

        // Make sure the current state is reset
        $this->resetApplicationState();

        // Sellers
        echo "Seller 1: {$seller1->name} | {$seller1->email} | {$seller1->id}\n";
        echo "Seller 2: {$seller2->name} | {$seller2->email} | {$seller2->id}\n";
        echo "Seller 3: {$seller3->name} | {$seller3->email} | {$seller3->id}\n";

        $spacer = '    ';

        // Story 1
        echo "\n** Story 1 **************************\n\n";

        // Buyer 1
        echo "Buyer 1: {$buyer1->name} | {$buyer1->email} | {$buyer1->id}\n";
        echo "{$spacer}Saved Search: {$search1->name} | {$search1->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing1->title} | {$listing1->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing3->title} | {$listing3->id}\n";
        echo "{$spacer}Saved Search: {$search2->name} | {$search2->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing1->title} | {$listing1->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing4->title} | {$listing4->id}\n";
        echo "{$spacer}Saved Search: {$search3->name} | {$search3->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing1->title} | {$listing1->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing2->title} | {$listing2->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing3->title} | {$listing3->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing4->title} | {$listing4->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing5->title} | {$listing5->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing6->title} | {$listing6->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing7->title} | {$listing7->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing8->title} | {$listing8->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing9->title} | {$listing9->id}\n";

        // Buyer 2
        echo "\n*************************************\n\n";
        echo "Buyer 2: {$buyer2->name} | {$buyer2->email} | {$buyer2->id}\n";
        echo "{$spacer}Saved Search: {$search4->name} | {$search4->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing2->title} | {$listing2->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing8->title} | {$listing8->id}\n";
        echo "{$spacer}Saved Search: {$search5->name} | {$search5->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing1->title} | {$listing1->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing2->title} | {$listing2->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing3->title} | {$listing3->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing4->title} | {$listing4->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing5->title} | {$listing5->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing6->title} | {$listing6->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing7->title} | {$listing7->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing8->title} | {$listing8->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing9->title} | {$listing9->id}\n";
        echo "{$spacer}Saved Search: {$search6->name} | {$search6->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing4->title} | {$listing4->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing7->title} | {$listing7->id}\n";

        // Buyer 3
        echo "\n*************************************\n\n";
        echo "Buyer 3: {$buyer3->name} | {$buyer3->email} | {$buyer3->id}\n";
        echo "{$spacer}Saved Search: {$search7->name} | {$search7->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing5->title} | {$listing5->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing9->title} | {$listing9->id}\n";
        echo "{$spacer}Saved Search: {$search8->name} | {$search8->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing5->title} | {$listing5->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing6->title} | {$listing6->id}\n";
        echo "{$spacer}Saved Search: {$search9->name} | {$search9->id}:\n";
        echo "{$spacer}{$spacer}Listing: {$listing1->title} | {$listing1->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing3->title} | {$listing3->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing4->title} | {$listing4->id}\n";
        echo "{$spacer}{$spacer}Listing: {$listing7->title} | {$listing7->id}\n";
    }
}
