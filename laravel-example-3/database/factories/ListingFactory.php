<?php

use App\BusinessCategory;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;
use Tests\Support\TestApplicationData;
use App\Support\AssetIncludedOptionType;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Listing::class, function (\Faker\Generator $faker) {
    $data = new TestApplicationData;
    $business_category_id = BusinessCategory::inRandomOrder()
                                             ->where('parent_id', 0)->take(1)->get()->first()->id;
    $business_sub_category_id = BusinessCategory::inRandomOrder()
                                             ->where('parent_id', $business_category_id)->take(1)->get()->first()->id;

            // 'business_name' => 'required|max:1000',
            // 'address_1' => 'required|max:1000',
            // 'city' => 'required|max:1000',
            // 'state' => 'required|max:1000',
            // 'zip_code' => 'required',
            // 'summary_business_description' => 'required|max:1000',
            // 'business_description' => 'required|max:1000',
            // 'business_category_id' => 'required',
            // 'business_sub_category_id' => 'required',
            // 'year_established' => 'nullable|date_format:Y',
            // 'asking_price' => 'numeric|required',
            // 'revenue' => 'nullable|numeric',
            // 'discretionary_cash_flow' => 'nullable|numeric',
            // 'pre_tax_earnings' => 'nullable|numeric',
            // 'ebitda' => 'nullable|numeric',
            // 'real_estate_estimated' => 'nullable|numeric',
            // 'real_estate_description' => 'nullable|max:1000',
            // 'fixtures_equipment_estimated' => 'nullable|numeric',
            // 'fixtures_equipment_description' => 'nullable|max:1000',
            // 'inventory_estimated' => 'nullable|numeric',
            // 'inventory_description' => 'nullable|max:1000',
            // 'location_description' => 'nullable|max:1000',
            // 'products_services' => 'nullable|max:1000',
            // 'market_overview' => 'nullable|max:1000',
            // 'competitive_advantage' => 'nullable|max:1000',
            // 'business_performance_outlook' => 'nullable|max:1000',
            // 'financing_available_description' => 'nullable|max:1000',
            // 'support_training_description' => 'nullable|max:1000',
            // 'reason_for_selling' => 'nullable|max:1000',
            // 'photos' => 'listing_photos_under_limit',
            // 'links.*' => new ValidInvalidUrl,
            // 'photos.new.*' => 'nullable|mimes:jpg,jpeg,png|max:4096',
            // 'files.new.*' => 'nullable|mimes:doc,docx,pdf,xls,xlsx,jpg,jpeg,png,bmp,pptx,ppt|max:5120',


    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
    //        'type' => $data->getTestBusinessType(),
        'title' => $faker->words($faker->numberBetween(1, 3), true),
        'business_name' => $faker->words($faker->numberBetween(1, 3), true),
        'asking_price' => $faker->randomFloat(2),
        'name_visible' => $faker->boolean(),
        'summary_business_description' => substr($faker->paragraphs($faker->numberBetween(1, 3), true), 0, 350),
        'business_description' => substr($faker->paragraphs($faker->numberBetween(1, 3), true), 0, 350),
        'business_category_id' => function () use ($business_category_id) {
            return $business_category_id;
        },
        'business_sub_category_id' => function () use ($business_sub_category_id) {
            return $business_sub_category_id;
        },
        'year_established' => $faker->year(),
        'number_of_employees' => $faker->randomNumber(),
        'revenue' => $faker->randomFloat(2),
        'discretionary_cash_flow' => $faker->randomFloat(2),
        'address_1' => $faker->streetAddress,
        'address_2' => $faker->secondaryAddress,
        'city' => $faker->city,
        'state' => $data->getTestStateAbbreviation(),
        'zip_code' => $faker->postcode,
        'address_visible' => $faker->boolean(),
        'location_description' => substr($faker->paragraphs($faker->numberBetween(1, 3), true), 0, 350),
        'pre_tax_earnings' => $faker->randomFloat(2),
        'ebitda' => $faker->randomFloat(2),
        'real_estate_included' => $real_estate_included = $faker->numberBetween(1, 3),
        'real_estate_estimated' => $real_estate_included === 1 ? $faker->randomFloat(2) : null,
        'real_estate_description' => $real_estate_included === 1 ? $faker->paragraphs(3, true) : null,
        'fixtures_equipment_included' => $fixtures_equipment_included = $faker->numberBetween(1, 3),
        'fixtures_equipment_estimated' => $fixtures_equipment_included === 1 ? $faker->randomFloat(2) : null,
        'fixtures_equipment_description' => $fixtures_equipment_included === 1 ? $faker->paragraphs(3, true) : null,
        'inventory_included' => $inventory_included = $faker->numberBetween(1, 3),
        'inventory_estimated' => $inventory_included === 1 ? $faker->randomFloat(2) : null,
        'inventory_description' => $inventory_included === 1 ? substr($faker->paragraphs($faker->numberBetween(1, 3), true), 0, 350) : null,
        'products_services' => $faker->paragraphs($faker->numberBetween(1, 3), true),
        'market_overview' => $faker->paragraphs($faker->numberBetween(1, 3), true),
        'competitive_position' => $faker->paragraphs($faker->numberBetween(1, 3), true),
        'competitive_advantage' => $faker->paragraphs($faker->numberBetween(1, 3), true),
        'business_performance_outlook' => $faker->paragraphs($faker->numberBetween(1, 3), true),
        'financing_available' => $financing_available = $faker->boolean(),
        'financing_available_description' => $financing_available ? $faker->paragraphs($faker->numberBetween(1, 3), true) : null,
        'support_training' => $support_training = $faker->boolean(),
        'support_training_description' => $support_training ? $faker->paragraphs($faker->numberBetween(1, 3), true) : null,
        'reason_for_selling' => $faker->paragraphs(3, true),
        'desired_sale_date' => date('Y-m-d', Carbon::now()->addYear()->timestamp),
        'seller_non_compete' => $faker->boolean(),
        'hf_most_recent_year' => Carbon::now(),
        'hf_most_recent_quarter' => Carbon::now()->quarter,
        'should_display_encouragement_modal' => true,
    ];
});

$factory->state(App\Listing::class, 'published', function (Faker $faker) {
    return [
        'published' => true,
    ];
});

$factory->state(App\Listing::class, 'unpublished', function (Faker $faker) {
    return [
        'published' => false,
    ];
});


$factory->state(App\Listing::class, 'override', function (Faker $faker) {
    return [
        'asking_price' => 1200000,
        'revenue' => 3000000,
        'discretionary_cash_flow' => 175000,
        'pre_tax_earnings' => 120000,
        'ebitda' => 200000,
        'real_estate_included' => false,
        'fixtures_equipment_included' => true,
        'fixtures_equipment_estimated' => 375000,
        'inventory_included' => true,
        'inventory_estimated' => 10000,
    ];
});

$factory->state(App\Listing::class, 'published', function (Faker $faker) {
    return [
        'published' => true,
    ];
});
$factory->state(App\Listing::class, 'example-1', function (Faker $faker) {
    return [
        'published' => true,
        'type' => 'Established Business',
        'title' => 'Popular local brewery and restaurant looking for a new owner',
        'business_name' => 'Main Street Brewery LLC',
        'asking_price' => 1200000,
        'name_visible' => true,
        'summary_business_description' => 'Grab a once in a lifetime opportunity to own a part of the resurgent downtown entertainment landscape!',
        'business_description' => 'Located in historic Laclede’s Landing, a designated “entertainment” area Main Street Brewery is acclaimed for traditional and gourmet American food, award-winning lager beer offerings, and premium event space. A wide variety of indoor and outdoor dining, bar and entertainment spaces within the Main Street Brewery facility make this a popular destination for a wide variety of patrons including families, tourists, business people, sports fans and the nightclub crowd. Catering and party venues are utilized for everything from corporate meetings to wedding receptions. Craft beer is brewed onsite and is also distributed throughout the metro area. Business gets significant support from the franchise parent in exchange for a 5% of revenue paid quarterly. Benefits include strong brand',
        'business_category_id' => BusinessCategory::where('label', 'Retail Sales')
                                  ->get()->first()->id,
        'business_sub_category_id' => BusinessCategory::where('label', 'Restaurants')
                                      ->get()->first()->id,
        'year_established' => 1995,
        'number_of_employees' => 72,
        'revenue' => 3000000,
        'discretionary_cash_flow' => 175000,
        'address_1' => '670 Main St.',
        'address_2' => 'Suite 100',
        'city' => 'Clayton',
        'state' => 'MO',
        'zip_code' => 63105,
        'address_visible' => true,
        'location_description' => 'Downtown location with lots of street traffic near ballpark village. 2 floors of seating plus bar and lounge area.',
        'pre_tax_earnings' => 120000,
        'ebitda' => 200000,
        'real_estate_included' => false,
        'real_estate_estimated' => 'N/A',
        'real_estate_description' => 'Current lease has 3 years remaining. Buyer may have to arrange new terms with property owner',
        'fixtures_equipment_included' => true,
        'fixtures_equipment_estimated' => 375000,
        'fixtures_equipment_description' => 'Kitchen equipment, dining room and bar fixtures',
        'inventory_included' => true,
        'inventory_estimated' => 10000,
        'inventory_description' => 'Restaurant sells T-Shirts and other branded items that will transfer',
        'products_services' => 'Microbrewery, restaurant and event venue. Business also sells branded merchandise such as T-Shirts, Mugs and other items for sale in the restaurant as well as on its website.',
        'market_overview' => 'The craft beer industry continues to grow in the United States. During and since the recession, the craft beer market has grown at the expense of the import beer market. The growing trend to “buy local” benefits the craft beer market as well. Morgan Street is the only lager brewery of the 18 microbreweries in St. Louis and has won awards for its craft beers. The quality food, craft beer, atmosphere varieties and prime location combine to make this restaurant/brewery a unique, popular destination.',
        'competitive_position' => 'The area is home to a number of other establishments, however there is a real sense of cooperation amongst the business owners.  The idea is to grow the pie, not compete over the same customer!',
        'business_performance_outlook' => 'Recent passage of local bill will revitalize the downtown area and provide more green space and pedestrian access/attraction.  Use expanded capacity and distribution to both commercial and retail outlets to extend brand recognition farther outside of St. Louis area. Increase targeted marketing in programs of events/games at area sporting and convention venues. Expand catering operations through more aggressive promotion. Utilize upper level space as leasable office space to increase revenue.',
        'financing_available' => true,
        'financing_available_description' => 'Limited to qualified buyers',
        'support_training' => true,
        'support_training_description' => 'Ownership is retiring and is committed to successfully introducing and integrating the buyer into the systems and procedures of the company.',
        'reason_for_selling' => 'Looking to retire and want to transition the business to a new family',
        'desired_sale_date' => '1Q 2018 or earlier',
        'seller_non_compete' => true,
        'website_links' => ['mainstreetbrewsmo.com', 'stlbrews.com/MainStreet'],
        'listing_photo' => 'listing-1-photo.jpg',
    ];
});

$factory->state(App\Listing::class, 'example-2', function (Faker $faker) {
    return [
        'published' => true,
    // (Add to enum) 'type' => 'Established Business',
        'title' => 'Restaurant with real estate, priced to sell',
        'business_name' => 'Smith Family Restaurant INC.',
        'asking_price' => 900000,
        'name_visible' => false,
        'summary_business_description' => 'Own a St. Louis Landmark with one of the most recognized names in town!',
        'business_description' => 'his incredible restaurant opened decades ago featuring a great menu and a relaxed atmosphere. This family has built their reputation and business on great food and never lost touch with where they started. Without any doubt they are one of the areas most successful restaurants. Located in a historic area and room enough to handle any party. You can own a St. Louis Landmark Restaurant with one of the most recognized names in town. Very profitable with huge opportunity for an energetic new owner to expand the catering and banquet components of the business. You can count on training and support of the current owner. Owning the Real Estate makes this rare business an even better long term investment! Business gets significant support from the franchise parent in exchange for a 5% of revenue advertising and operational support including access to national supply chain of inventory. Business runs on franchise provided inventory and sales management platform."',
        'business_category_id' => BusinessCategory::where('label', 'Retail Sales')
        ->get()->first()->id,
        'business_sub_category_id' => BusinessCategory::where('label', 'Restaurants')
            ->get()->first()->id,
        'year_established' => 1971,
        'number_of_employees' => 50,
        'revenue' => 1878828,
        'discretionary_cash_flow' => 146856,
        'address_1' => '401 First Ave.',
        'address_2' => null,
        'city' => 'Ferguson',
        'state' => 'MO',
        'zip_code' => 63136,
        'address_visible' => false,
        'location_description' => 'Primary position in a large shopping mall. 5000 sqft of space!',
        'pre_tax_earnings' => 110871,
        'ebitda' => '196704',
        'real_estate_included' => true,
        'real_estate_estimated' => 300000,
        'real_estate_description' => 'Real estate and land is owned by the business.',
        'fixtures_equipment_included' => true,
        'fixtures_equipment_estimated' => 50000,
        'fixtures_equipment_description' => 'Kitchen equipment, dining room and bar fixtures',
        'inventory_included' => false,
        'inventory_estimated' => 'N/A',
        'inventory_description' => 'New buyer will need to procure during a transition period to be determined.',
        'products_services' => 'Great American style menu',
        'market_overview' => 'Starting a restaurant from scratch is an incredibly daunting task, but with this investment, the work has been done for you! The family has built their business on the promise of great food and you\'ll inherit that head start.',
        'competitive_position' => 'With a prime location, potential customers would have to drive right past your restaurant to get to any of the nearby eateries--a clear advantage to be sure!',
        'business_performance_outlook' => 'While growth has been slow lately, that\'s more a reflection on the ownership shifting to a later stage in their development and wanting to slow down. In the hands of a right owner, there\'s countless opportunities for success: expanded lunch menu, renovating and enlarging the outdoor patio, etc.',
        'financing_available' => true,
        'financing_available_description' => 'Will provide up to 30% of purchase price.',
        'support_training' => true,
        'support_training_description' => 'Current owner is happy to accommodate new buyer to help ensure smooth transition for current employees.',
        'reason_for_selling' => 'Retirement',
        'desired_sale_date' => 'Within the next twelve months',
        'seller_non_compete' => true,
        'website_links' => null,
        'listing_photo' => null,
    ];
});

$factory->state(App\Listing::class, 'example-3', function (Faker $faker) {
    return [
        'published' => false,
        'type' => 'Established Business',
        'title' => 'High margin restaurant franchise. Turn-key operation!',
        'business_name' => 'Chicken King WUSTL LLC',
        'asking_price' => '4000000',
        'name_visible' => true,
        'summary_business_description' => 'Rare opportunity to own a successful Chicken King franchise and join the fastest growing restaurant chain in the United States.',
        'business_description' => 'Successful Chicken King franchise with a prime location near Washington University campus. As one of the most popular chicken franchise companies in the U.S., the business has been highly popular with students. While the business is located within walking distance to the main campus, a new buyer could potentially explore delivery options to campus that could be quickly accessible by bike and open up additional revenue while providing students a part-time employment opportunity. The business currently employees mostly part-time student workers but has a full time manager and assistant manager that run the day-to-day operations.',
        'business_category_id' => 'Retail Sales',
        'business_sub_category_id' => 'Restaurants',
        'year_established' => '2007',
        'number_of_employees' => '20, 18 part time',
        'revenue' => '2343554',
        'discretionary_cash_flow' => '795400',
        'address_1' => '1 Brookings Drive',
        'address_2' => '',
        'city' => 'St. Louis',
        'state' => 'MO',
        'zip_code' => '63105',
        'address_visible' => true,
        'location_description' => 'Prime location right next to University.',
        'pre_tax_earnings' => '730400',
        'ebitda' => '835300',
        'real_estate_included' => true,
        'real_estate_estimated' => '1000000',
        'real_estate_description' => 'Location is owned by the business. 2000 sqft',
        'fixtures_equipment_included' => true,
        'fixtures_equipment_estimated' => 100000,
        'fixtures_equipment_description' => 'Display cabinets, tables, chairs and delivery bikes',
        'inventory_included' => true,
        'inventory_estimated' => 5000,
        'inventory_description' => 'Everything is included',
        'products_services' => 'Provides a simple but loved menu of various fried chicken options, as well as salads and other health conscious items. Currently only offers dine-in but could expand to include local delivery.',
        'market_overview' => 'Great university location with a strong recurring customer base. The business is the only fast casual restaurant of its kind within walking distance to the school, making it attractive to the student body, while also being in a high-trafficed area for drive-in customers.',
        'competitive_position' => 'Competes with Chick-fil-A and other fast casual restaurants, but one of few within close proximity to the campus.',
        'business_performance_outlook' => 'After a period of strong growth after opening, the business has stabilized in recent years, but has never had a down year in revenue or owner\'s profit. Opportunity for buyer to join a great franchise and make a steady living. Future growth opportunities available from a potential delivery service and increased catering revenue.',
        'financing_available' => false,
        'financing_available_description' => 'Strong cash flows should support bank financing for credible buyers',
        'support_training' => false,
        'support_training_description' => 'Franchisor will provide necessary training',
        'reason_for_selling' => 'Looking to pursue scalable opportunities as franchisor mandates can only operate one location',
        'desired_sale_date' => 'Next six months',
        'seller_non_compete' => false,
        'website_links' => ['chickenking.com'],
        'listing_photo' => 'listing-3-photo.jpg',
        'display_listed_by' => $faker->boolean(),
    ];
});

$factory->define(App\Listing::class, function (\Faker\Generator $faker) {
    $data = new TestApplicationData;
    $business_category_id = BusinessCategory::inRandomOrder()
                                             ->where('parent_id', 0)->take(1)->get()->first()->id;
    $business_sub_category_id = BusinessCategory::inRandomOrder()
                                             ->where('parent_id', $business_category_id)->take(1)->get()->first()->id;
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
//        'type' => $data->getTestBusinessType(),
        'title' => $faker->words($faker->numberBetween(1, 3), true),
        'business_name' => $faker->words($faker->numberBetween(1, 3), true),
        'asking_price' => $faker->randomFloat(2),
        'name_visible' => $faker->boolean(),
        'summary_business_description' => substr($faker->paragraphs($faker->numberBetween(1, 3), true), 0, 350),
        'business_description' => substr($faker->paragraphs($faker->numberBetween(1, 3), true), 0, 350),
        'business_category_id' => function () use ($business_category_id) {
            return $business_category_id;
        },
        'business_sub_category_id' => function () use ($business_sub_category_id) {
            return $business_sub_category_id;
        },
        'year_established' => $faker->year(),
        'number_of_employees' => $faker->randomNumber(),
        'revenue' => $faker->randomFloat(2),
        'discretionary_cash_flow' => $faker->randomFloat(2),
        'address_1' => $faker->streetAddress,
        'address_2' => $faker->secondaryAddress,
        'city' => $faker->city,
        'state' => $data->getTestStateAbbreviation(),
        'zip_code' => $faker->postcode,
        'address_visible' => $faker->boolean(),
        'location_description' => substr($faker->paragraphs($faker->numberBetween(1, 3), true), 0, 350),
        'pre_tax_earnings' => $faker->randomFloat(2),
        'ebitda' => $faker->randomFloat(2),
        'real_estate_included' => $real_estate_included = $faker->numberBetween(1, 3),
        'real_estate_estimated' => $real_estate_included ? $faker->randomFloat(2) : null,
        'fixtures_equipment_included' => $fixtures_equipment_included = $faker->numberBetween(1, 3),
        'fixtures_equipment_estimated' => $fixtures_equipment_included ? $faker->randomFloat(2) : null,
        'inventory_included' => $inventory_included = $faker->numberBetween(1, 3),
        'inventory_estimated' => $inventory_included ? $faker->randomFloat(2) : null,
        'products_services' => $faker->paragraphs($faker->numberBetween(1, 3), true),
        'market_overview' => $faker->paragraphs($faker->numberBetween(1, 3), true),
        'competitive_position' => $faker->paragraphs($faker->numberBetween(1, 3), true),
        'business_performance_outlook' => $faker->paragraphs($faker->numberBetween(1, 3), true),
        'financing_available' => $financing_available = $faker->boolean(),
        'financing_available_description' => $financing_available ? $faker->paragraphs($faker->numberBetween(1, 3), true) : null,
        'support_training' => $support_training = $faker->boolean(),
        'support_training_description' => $support_training ? $faker->paragraphs($faker->numberBetween(1, 3), true) : null,
        'reason_for_selling' => $faker->paragraphs($faker->numberBetween(1, 3), true),
        'desired_sale_date' => date('Y-m-d', Carbon::now()->addYear()->timestamp),
        'seller_non_compete' => $faker->boolean(),
    ];
});

$factory->state(App\Listing::class, 'override', function (Faker $faker) {
    return [
        'asking_price' => 1200000,
        'revenue' => 3000000,
        'discretionary_cash_flow' => 175000,
        'pre_tax_earnings' => 120000,
        'ebitda' => 200000,
        'real_estate_included' => false,
        'fixtures_equipment_included' => true,
        'fixtures_equipment_estimated' => 375000,
        'inventory_included' => true,
        'inventory_estimated' => 10000,
    ];
});

$factory->state(App\Listing::class, 'example-1', function (Faker $faker) {
    return [
        'published' => true,
        'type' => 'Established Business',
        'title' => 'Popular local brewery and restaurant looking for a new owner',
        'business_name' => 'Main Street Brewery LLC',
        'asking_price' => 1200000,
        'name_visible' => true,
        'summary_business_description' => 'Grab a once in a lifetime opportunity to own a part of the resurgent downtown entertainment landscape!',
        'business_description' => 'Located in historic Laclede’s Landing, a designated “entertainment” area Main Street Brewery is acclaimed for traditional and gourmet American food, award-winning lager beer offerings, and premium event space. A wide variety of indoor and outdoor dining, bar and entertainment spaces within the Main Street Brewery facility make this a popular destination for a wide variety of patrons including families, tourists, business people, sports fans and the nightclub crowd. Catering and party venues are utilized for everything from corporate meetings to wedding receptions. Craft beer is brewed onsite and is also distributed throughout the metro area. Business gets significant support from the franchise parent in exchange for a 5% of revenue paid quarterly. Benefits include strong brand',
        'business_category_id' => BusinessCategory::where('label', 'Retail Sales')
                                  ->get()->first()->id,
        'business_sub_category_id' => BusinessCategory::where('label', 'Restaurants')
                                      ->get()->first()->id,
        'year_established' => 1995,
        'number_of_employees' => 72,
        'revenue' => 3000000,
        'discretionary_cash_flow' => 175000,
        'address_1' => '670 Main St.',
        'address_2' => 'Suite 100',
        'city' => 'Clayton',
        'state' => 'MO',
        'zip_code' => 63105,
        'address_visible' => true,
        'location_description' => 'Downtown location with lots of street traffic near ballpark village. 2 floors of seating plus bar and lounge area.',
        'pre_tax_earnings' => 120000,
        'ebitda' => 200000,
        'real_estate_included' => false,
        'real_estate_estimated' => 'N/A',
        'real_estate_description' => 'Current lease has 3 years remaining. Buyer may have to arrange new terms with property owner',
        'fixtures_equipment_included' => true,
        'fixtures_equipment_estimated' => 375000,
        'fixtures_equipment_description' => 'Kitchen equipment, dining room and bar fixtures',
        'inventory_included' => true,
        'inventory_estimated' => 10000,
        'inventory_description' => 'Restaurant sells T-Shirts and other branded items that will transfer',
        'products_services' => 'Microbrewery, restaurant and event venue. Business also sells branded merchandise such as T-Shirts, Mugs and other items for sale in the restaurant as well as on its website.',
        'market_overview' => 'The craft beer industry continues to grow in the United States. During and since the recession, the craft beer market has grown at the expense of the import beer market. The growing trend to “buy local” benefits the craft beer market as well. Morgan Street is the only lager brewery of the 18 microbreweries in St. Louis and has won awards for its craft beers. The quality food, craft beer, atmosphere varieties and prime location combine to make this restaurant/brewery a unique, popular destination.',
        'competitive_position' => 'The area is home to a number of other establishments, however there is a real sense of cooperation amongst the business owners.  The idea is to grow the pie, not compete over the same customer!',
        'business_performance_outlook' => 'Recent passage of local bill will revitalize the downtown area and provide more green space and pedestrian access/attraction.  Use expanded capacity and distribution to both commercial and retail outlets to extend brand recognition farther outside of St. Louis area. Increase targeted marketing in programs of events/games at area sporting and convention venues. Expand catering operations through more aggressive promotion. Utilize upper level space as leasable office space to increase revenue.',
        'financing_available' => true,
        'financing_available_description' => 'Limited to qualified buyers',
        'support_training' => true,
        'support_training_description' => 'Ownership is retiring and is committed to successfully introducing and integrating the buyer into the systems and procedures of the company.',
        'reason_for_selling' => 'Looking to retire and want to transition the business to a new family',
        'desired_sale_date' => '1Q 2018 or earlier',
        'seller_non_compete' => true,
        'website_links' => ['mainstreetbrewsmo.com', 'stlbrews.com/MainStreet'],
        'listing_photo' => 'listing-1-photo.jpg',
    ];
});

$factory->state(App\Listing::class, 'example-2', function (Faker $faker) {
    return [
        'published' => true,
    // (Add to enum) 'type' => 'Established Business',
        'title' => 'Restaurant with real estate, priced to sell',
        'business_name' => 'Smith Family Restaurant INC.',
        'asking_price' => 900000,
        'name_visible' => false,
        'summary_business_description' => 'Own a St. Louis Landmark with one of the most recognized names in town!',
        'business_description' => 'his incredible restaurant opened decades ago featuring a great menu and a relaxed atmosphere. This family has built their reputation and business on great food and never lost touch with where they started. Without any doubt they are one of the areas most successful restaurants. Located in a historic area and room enough to handle any party. You can own a St. Louis Landmark Restaurant with one of the most recognized names in town. Very profitable with huge opportunity for an energetic new owner to expand the catering and banquet components of the business. You can count on training and support of the current owner. Owning the Real Estate makes this rare business an even better long term investment! Business gets significant support from the franchise parent in exchange for a 5% of revenue advertising and operational support including access to national supply chain of inventory. Business runs on franchise provided inventory and sales management platform."',
        'business_category_id' => BusinessCategory::where('label', 'Retail Sales')
        ->get()->first()->id,
        'business_sub_category_id' => BusinessCategory::where('label', 'Restaurants')
            ->get()->first()->id,
        'year_established' => 1971,
        'number_of_employees' => 50,
        'revenue' => 1878828,
        'discretionary_cash_flow' => 146856,
        'address_1' => '401 First Ave.',
        'address_2' => null,
        'city' => 'Ferguson',
        'state' => 'MO',
        'zip_code' => 63136,
        'address_visible' => false,
        'location_description' => 'Primary position in a large shopping mall. 5000 sqft of space!',
        'pre_tax_earnings' => 110871,
        'ebitda' => '196704',
        'real_estate_included' => true,
        'real_estate_estimated' => 300000,
        'real_estate_description' => 'Real estate and land is owned by the business.',
        'fixtures_equipment_included' => true,
        'fixtures_equipment_estimated' => 50000,
        'fixtures_equipment_description' => 'Kitchen equipment, dining room and bar fixtures',
        'inventory_included' => false,
        'inventory_estimated' => 'N/A',
        'inventory_description' => 'New buyer will need to procure during a transition period to be determined.',
        'products_services' => 'Great American style menu',
        'market_overview' => 'Starting a restaurant from scratch is an incredibly daunting task, but with this investment, the work has been done for you! The family has built their business on the promise of great food and you\'ll inherit that head start.',
        'competitive_position' => 'With a prime location, potential customers would have to drive right past your restaurant to get to any of the nearby eateries--a clear advantage to be sure!',
        'business_performance_outlook' => 'While growth has been slow lately, that\'s more a reflection on the ownership shifting to a later stage in their development and wanting to slow down. In the hands of a right owner, there\'s countless opportunities for success: expanded lunch menu, renovating and enlarging the outdoor patio, etc.',
        'financing_available' => true,
        'financing_available_description' => 'Will provide up to 30% of purchase price.',
        'support_training' => true,
        'support_training_description' => 'Current owner is happy to accommodate new buyer to help ensure smooth transition for current employees.',
        'reason_for_selling' => 'Retirement',
        'desired_sale_date' => 'Within the next twelve months',
        'seller_non_compete' => true,
        'website_links' => null,
        'listing_photo' => null,
    ];
});

$factory->state(App\Listing::class, 'lcs-empty', function (\Faker\Generator $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'title' => null,
        'business_name' => null,
        'asking_price' => null,
        'name_visible' => null,
        'summary_business_description' => null,
        'business_description' => null,
        'business_category_id' => null,
        'business_sub_category_id' => null,
        'year_established' => null,
        'number_of_employees' => null,
        'revenue' => null,
        'discretionary_cash_flow' => null,
        'address_1' => null,
        'address_2' => null,
        'city' => null,
        'state' => null,
        'zip_code' => null,
        'address_visible' => true,
        'location_description' => null,
        'pre_tax_earnings' => null,
        'ebitda' => null,
        'real_estate_included' => null,
        'real_estate_estimated' => null,
        'fixtures_equipment_included' => null,
        'fixtures_equipment_estimated' => null,
        'inventory_included' => null,
        'inventory_estimated' => null,
        'products_services' => null,
        'market_overview' => null,
        'competitive_position' => null,
        'business_performance_outlook' => null,
        'financing_available' => null,
        'financing_available_description' => null,
        'support_training' => null,
        'support_training_description' => null,
        'reason_for_selling' => null,
        'desired_sale_date' => null,
        'seller_non_compete' => null,
        'hf_most_recent_year' => Carbon::now(),
        'hf_most_recent_quarter' => Carbon::now()->quarter,
    ];
});

$factory->state(App\Listing::class, 'lcs-full', function (\Faker\Generator $faker) {
    return [
        'title' => 'Title',
        'business_name' => 'Name',
        'asking_price' => 2000,
        'summary_business_description' => 'Description',
        'business_description' => 'Overview',
        'business_category_id' => 1,
        'business_sub_category_id' => 2,
        'year_established' => Carbon::now()->subYears(10)->format('Y'),
        'number_of_employees' => 10,
        'address_visible' => true,
        'address_1' => '111 Test Rd',
        'address_2' => '111',
        'city' => 'St. Louis',
        'state' => 'MO',
        'zip_code' => '63103',
        'location_description' => 'Description',
        'revenue' => 2000,
        'ebitda' => 3000,
        'pre_tax_earnings' => 5000,
        'discretionary_cash_flow' => 8000,
        'inventory_included' => AssetIncludedOptionType::INCLUDED,
        'inventory_estimated' => 3000,
        'inventory_description' => 'Inventory Description',
        'fixtures_equipment_included' => AssetIncludedOptionType::INCLUDED,
        'fixtures_equipment_estimated' => 5000,
        'fixtures_equipment_description' => 'Fixtures equipment description',
        'real_estate_included' => AssetIncludedOptionType::INCLUDED,
        'real_estate_estimated' => 2000,
        'real_estate_description' => 'Real estate description',
        'products_services' => 'Products services',
        'market_overview' => 'Market overview',
        'competitive_position' => 'Competitive position',
        'business_performance_outlook' => 'Business performance outlook',
        'financing_available' => true,
        'financing_available_description' => 'Financing available description',
        'support_training' => true,
        'support_training_description' => 'Support training description',
        'reason_for_selling' => 'Reason for selling',
        'desired_sale_date' => 'Desired sale date',
        'seller_non_compete' => true,
        'hf_most_recent_year' => Carbon::now(),
        'hf_most_recent_quarter' => Carbon::now()->quarter,
    ];
});

$factory->state(App\Listing::class, 'lcs-hsf-zero', function (Faker $faker) {
    return [
        'title' => 'Title',
        'business_name' => 'Name',
        'asking_price' => 2000,
        'summary_business_description' => 'Description',
        'business_description' => 'Overview',
        'business_category_id' => 1,
        'business_sub_category_id' => 2,
        'year_established' => Carbon::now()->subYears(10)->format('Y'),
        'number_of_employees' => 10,
        'address_visible' => true,
        'address_1' => '111 Test Rd',
        'address_2' => '111',
        'city' => 'St. Louis',
        'state' => 'MO',
        'zip_code' => '63103',
        'location_description' => 'Description',
        'revenue' => 2000,
        'ebitda' => 3000,
        'pre_tax_earnings' => 5000,
        'discretionary_cash_flow' => 8000,
        'inventory_included' => AssetIncludedOptionType::INCLUDED,
        'inventory_estimated' => 3000,
        'inventory_description' => 'Inventory Description',
        'fixtures_equipment_included' => AssetIncludedOptionType::INCLUDED,
        'fixtures_equipment_estimated' => 5000,
        'fixtures_equipment_description' => 'Fixtures equipment description',
        'real_estate_included' => AssetIncludedOptionType::INCLUDED,
        'real_estate_estimated' => 2000,
        'real_estate_description' => 'Real estate description',
        'products_services' => 'Products services',
        'market_overview' => 'Market overview',
        'competitive_position' => 'Competitive position',
        'business_performance_outlook' => 'Business performance outlook',
        'financing_available' => true,
        'financing_available_description' => 'Financing available description',
        'support_training' => true,
        'support_training_description' => 'Support training description',
        'reason_for_selling' => 'Reason for selling',
        'desired_sale_date' => 'Desired sale date',
        'seller_non_compete' => true,
        'hf_most_recent_year' => Carbon::now(),
        'hf_most_recent_quarter' => Carbon::now()->quarter,
    ];
});


$factory->state(App\Listing::class, 'required-only', function (\Faker\Generator $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'name_visible' => true,
        'year_established' => null,
        'number_of_employees' => null,
        'revenue' => null,
        'discretionary_cash_flow' => null,
        'address_2' => null,
        'address_visible' => true,
        'location_description' => null,
        'pre_tax_earnings' => null,
        'ebitda' => null,
        'real_estate_included' => null,
        'real_estate_estimated' => null,
        'fixtures_equipment_included' => null,
        'fixtures_equipment_estimated' => null,
        'inventory_included' => null,
        'inventory_estimated' => null,
        'products_services' => null,
        'market_overview' => null,
        'competitive_position' => null,
        'business_performance_outlook' => null,
        'financing_available' => null,
        'financing_available_description' => null,
        'support_training' => null,
        'support_training_description' => null,
        'reason_for_selling' => null,
        'desired_sale_date' => null,
        'seller_non_compete' => null,
        'hf_most_recent_year' => Carbon::now(),
        'hf_most_recent_quarter' => Carbon::now()->quarter,
    ];
});
