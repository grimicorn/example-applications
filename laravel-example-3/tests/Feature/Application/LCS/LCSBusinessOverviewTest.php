<?php

namespace Tests\Feature\Application\LCS;

use App\Listing;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Support\AssetIncludedOptionType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Support\ListingCompletionScore\ListingCompletionScore;

/**
 * @group lcs
 * @codingStandardsIgnoreFile
 */
class LCSBusinessOverviewTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_calculates_listing_basics_lcs()
    {
        $listing = factory('App\Listing')->states('lcs-empty')->make([
            'name_visible' => true,
        ]);
        $section = 'basics';

        // Make sure the listing has the correct empty listing score.
        $this->assertEmptyListingCompletionScore($listing, $section);

        // A listing with just a Title should have a section completion score of 1
        // A listing with just a Title should have a section completion score percentage of 6
        $listing->title = 'Title';
        $this->assertSectionCompletionScore($listing, $section, 1, 6);
        $listing->title = null;

        // A listing with just a Name should have a section completion score of 1
        // A listing with just a Name should have a section completion score percentage of 6
        $listing->business_name = 'Name';
        $this->assertSectionCompletionScore($listing, $section, 1, 6);
        $listing->business_name = null;

        // A listing with just a Asking Price should have a section completion score of 2
        // A listing with just a Asking Price should have a section completion score percentage of 13
        $listing->asking_price = 2000;
        $this->assertSectionCompletionScore($listing, $section, 2, 13);
        $listing->asking_price = null;

        // A listing with just a Summary Description should have a section completion score of 3
        // A listing with just a Summary Description should have a section completion score percentage of 19
        $listing->summary_business_description = 'Description';
        $this->assertSectionCompletionScore($listing, $section, 3, 19);
        $listing->summary_business_description = null;

        // A listing with a City should have a section completion score of 1
        // A listing with a City should have a section completion score percentage of 6
        $listing->city = 'St. Louis';
        $this->assertSectionCompletionScore($listing, $section, 1, 6);
        $listing->city = null;

        // A listing with a City should have a section completion score of 1
        // A listing with a City should have a section completion score percentage of 6
        $listing->state = 'MO';
        $this->assertSectionCompletionScore($listing, $section, 1, 6);
        $listing->state = null;

        // A listing with a Zip Code should have a section completion score of 1
        // A listing with a Zip Code should have a section completion score percentage of 6
        $listing->zip_code = '63103';
        $this->assertSectionCompletionScore($listing, $section, 1, 6);
        $listing->zip_code = null;

        // A listing with just a Business Overview should have a section completion score of 5
        // A listing with just a Business Overview should have a section completion score percentage of 31
        $listing->business_description = 'Overview';
        $this->assertSectionCompletionScore($listing, $section, 5, 31);
        $listing->business_description = null;

        // A listing with just both a Business Category and Business Sub-category should have a section completion score of 1
        // A listing with just both a Business Category and Business Sub-category should have a section completion score percentage of 6
        $listing->business_category_id = 1;
        $listing->business_sub_category_id = 2;
        $this->assertSectionCompletionScore($listing, $section, 1, 6);
        $listing->business_category_id = null;
        $listing->business_sub_category_id = null;

        // A listing with only a Business Category should have a section completion score of 0
        // A listing with only a Business Category should have a section completion score percentage of 0
        $listing->business_category_id = 1;
        $listing->business_sub_category_id = null;
        $this->assertSectionCompletionScore($listing, $section, 0, 0);
        $listing->business_category_id = null;
        $listing->business_sub_category_id = null;

        // A listing with only Business Sub-category should have a section completion score of 0
        // A listing with only Business Sub-category should have a section completion score percentage of 0
        $listing->business_category_id = 1;
        $listing->business_sub_category_id = null;
        $this->assertSectionCompletionScore($listing, $section, 0, 0);
        $listing->business_category_id = null;
        $listing->business_sub_category_id = null;

        // A listing with a Title and Name should have a section completion score of 2
        // A listing with a Title and Name should have a section completion score percentage of 13
        $listing->title = 'Title';
        $listing->business_name = 'Name';
        $this->assertSectionCompletionScore($listing, $section, 2, 13);

        // A listing with a Title, Name and Asking Price should have a section completion score of 4
        // A listing with a Title, Name and Asking Price should have a section completion score percentage of 25
        $listing->asking_price = 2000;
        $this->assertSectionCompletionScore($listing, $section, 4, 25);

        // A listing with a Title, Name, Summary Description and Asking Price should have a section completion score of 7
        // A listing with a Title, Name, Summary Description and Asking Price should have a section completion score percentage of 44
        $listing->summary_business_description = 'Description';
        $this->assertSectionCompletionScore($listing, $section, 7, 44);

        // A listing with a Title, Name, Summary Description, Business Overview and Asking Price should have a section completion score of 12
        // A listing with a Title, Name, Summary Description, Business Overview and Asking Price should have a section completion score percentage of 75
        $listing->business_description = 'Description';
        $this->assertSectionCompletionScore($listing, $section, 12, 75);

        // A listing with a Title, Name, Summary Description, Business Overview, Category and Asking Price should have a section completion score of 13
        // A listing with a Title, Name, Summary Description, Business Overview, Category and Asking Price should have a section completion score percentage of 81
        $listing->business_category_id = 1;
        $listing->business_sub_category_id = 2;
        $this->assertSectionCompletionScore($listing, $section, 13, 81);

        // A listing with a Title, Name, Summary Description, Business Overview, Category, City and Asking Price should have a section completion score of 14
        // A listing with a Title, Name, Summary Description, Business Overview, Category, City and Asking Price should have a section completion score percentage of 88
        $listing->city = 'St. Louis';
        $this->assertSectionCompletionScore($listing, $section, 14, 88);

        // A listing with a Title, Name, Summary Description, Business Overview, Category, City, State and Asking Price should have a section completion score of 15
        // A listing with a Title, Name, Summary Description, Business Overview, Category, City, State and Asking Price should have a section completion score percentage of 94
        $listing->state = 'St. Louis';
        $this->assertSectionCompletionScore($listing, $section, 15, 94);

        // A listing with a Title, Name, Summary Description, Business Overview, Category, City, State, Zip Code and Asking Price should have a section completion score of 16
        // A listing with a Title, Name, Summary Description, Business Overview, Category, City, State, Zip Code and Asking Price should have a section completion score percentage of 100
        $listing->zip_code = 'St. Louis';
        $this->assertSectionCompletionScore($listing, $section, 16, 100);
    }

    /**
    * @test
    */
    public function it_calculates_listing_more_about_the_business_lcs()
    {
        $listing = factory('App\Listing')->states('lcs-empty')->make([
            'name_visible' => true,
        ]);
        $section = 'more_about_the_business';

        // Make sure the listing has the correct empty listing score.
        $this->assertEmptyListingCompletionScore($listing, $section);

        // A listing with only a Year Established should have a section total of 1
        // A listing with only a Year Established should have a section total percentage of 17
        $listing->year_established = Carbon::now()->subYears(10)->format('Y');
        $this->assertSectionCompletionScore($listing, $section, 1, 17);
        $listing->year_established = null;

        // A listing with only Number of Employees should have a section total of 1
        // A listing with only Number of Employees should have a section total percentage of 17
        $listing->number_of_employees = 10;
        $this->assertSectionCompletionScore($listing, $section, 1, 17);
        $listing->number_of_employees = null;

        // A listing with an Address Line 1 should have a section total of 1
        // A listing with an Address Line 1 should have a section total percentage of 17
        $listing->address_1 = '111 Test Rd';
        $this->assertSectionCompletionScore($listing, $section, 1, 17);
        $listing->address_1 = null;

        // A listing without an Address Line 1 should have a section total of 0
        // A listing without an Address Line 1 should have a section total percentage of 0
        $listing->address_1 = null;
        $this->assertSectionCompletionScore($listing, $section, 0, 0);
        $listing->address_1 = null;

        // A listing with only a Location Description should have a section total of 3
        // A listing with only a Location Description should have a section total percentage of 50
        $listing->location_description = 'Description';
        $this->assertSectionCompletionScore($listing, $section, 3, 50);
        $listing->location_description = null;

        // Year Established and Number of Employees completion score of 2
        // Year Established and Number of Employees completion score percentage of 33
        $listing->year_established = Carbon::now()->subYears(10)->format('Y');
        $listing->number_of_employees = 10;
        $this->assertSectionCompletionScore($listing, $section, 2, 33);

        // Address Line 1, Year Established and Number of Employees completion score of 3
        // Address Line 1, Year Established and Number of Employees completion score percentage of 50
        $listing->address_1 = '111 Test Rd';
        $this->assertSectionCompletionScore($listing, $section, 3, 50);

        // Location Description, Address, Year Established and Number of Employees completion score of 6
        // Location Description, Address, Year Established and Number of Employees completion score percentage of 100
        $listing->location_description = 'Description';
        $this->assertSectionCompletionScore($listing, $section, 6, 100);
    }

    /**
    * @test
    */
    public function it_calculates_listing_financial_details_lcs()
    {
        $listing = factory('App\Listing')->states('lcs-empty')->make([
            'name_visible' => true,
        ]);
        $section = 'financial_details';

        // Make sure the listing has the correct empty listing score.
        $this->assertEmptyListingCompletionScore($listing, $section);

        // A Listing with only Revenue should have a section completion score of 5
        // A Listing with only Revenue should have a section completion score percentage of 10
        $listing->revenue = 2000;
        $this->assertSectionCompletionScore($listing, $section, 5, 10);
        $listing->revenue = null;

        // A Listing with only EBITDA should have a section completion score of 5
        // A Listing with only EBITDA should have a section completion score percentage of 10
        $listing->ebitda = 3000;
        $this->assertSectionCompletionScore($listing, $section, 5, 10);
        $listing->ebitda = null;

        // A Listing with only Pre-Tax Earnings should have a section completion score of 5
        // A Listing with only Pre-Tax Earnings should have a section completion score percentage of 10
        $listing->pre_tax_earnings = 5000;
        $this->assertSectionCompletionScore($listing, $section, 5, 10);
        $listing->pre_tax_earnings = null;

        // A Listing with only Discretionary Cash Flow should have a section completion score of 5
        // A Listing with only Discretionary Cash Flow should have a section completion score percentage of 10
        $listing->discretionary_cash_flow = 8000;
        $this->assertSectionCompletionScore($listing, $section, 5, 10);
        $listing->discretionary_cash_flow = null;

        // A Listing with only Inventory Toggle Selector should have a section completion score of 5
        // A Listing with only Inventory Toggle Selector should have a section completion score percentage of 10
        $listing->inventory_included = AssetIncludedOptionType::INCLUDED;
        $this->assertSectionCompletionScore($listing, $section, 5, 10);
        $listing->inventory_included = null;

        // A Listing with only Inventory should have a section completion score of 2
        // A Listing with only Inventory should have a section completion score percentage of 4
        $listing->inventory_estimated = 3000;
        $this->assertSectionCompletionScore($listing, $section, 2, 4);
        $listing->inventory_estimated = null;

        // A Listing with only Inventory Description should have a section completion score of 3
        // A Listing with only Inventory Description should have a section completion score percentage of 6
        $listing->inventory_description = 'Inventory Description';
        $this->assertSectionCompletionScore($listing, $section, 3, 6);
        $listing->inventory_description = null;

        // A Listing with only Equipment/Assets Toggle selector should have a section completion score of 5
        // A Listing with only Equipment/Assets Toggle selector should have a section completion score percentage of 10
        $listing->fixtures_equipment_included = AssetIncludedOptionType::INCLUDED;
        $this->assertSectionCompletionScore($listing, $section, 5, 10);
        $listing->fixtures_equipment_included = null;

        // A Listing with only Equipment/Assets should have a section completion score of 2
        // A Listing with only Equipment/Assets should have a section completion score percentage of 4
        $listing->fixtures_equipment_estimated = 5000;
        $this->assertSectionCompletionScore($listing, $section, 2, 4);
        $listing->fixtures_equipment_estimated = null;

        // A Listing with only Equipment/Assets Description should have a section completion score of 3
        // A Listing with only Equipment/Assets Description should have a section completion score percentage of 6
        $listing->fixtures_equipment_description = 'Fixtures equipment description';
        $this->assertSectionCompletionScore($listing, $section, 3, 6);
        $listing->fixtures_equipment_description = null;

        // A Listing with only Real Estate toggle selector should have a section completion score of 5
        // A Listing with only Real Estate toggle selector should have a section completion score percentage of 10
        $listing->real_estate_included = AssetIncludedOptionType::INCLUDED;
        $this->assertSectionCompletionScore($listing, $section, 5, 10);
        $listing->real_estate_included = null;

        // A Listing with only Real Estate should have a section completion score of 2
        // A Listing with only Real estate should have a section completion score percentage of 4
        $listing->real_estate_estimated = 2000;
        $this->assertSectionCompletionScore($listing, $section, 2, 4);
        $listing->real_estate_estimated = null;

        // A Listing with only Real Estate Description should have a section completion score of 3
        // A Listing with only Real Estate Description should have a section completion score percentage of 6
        $listing->real_estate_description = 'Real estate description';
        $this->assertSectionCompletionScore($listing, $section, 3, 6);
        $listing->real_estate_description = null;

        // A Listing with Inventory Toggle Selector set to N/A should have a section completion score of 10
        // A Listing with Inventory Toggle Selector set to N/A should have a section completion score percentage of 20
        $listing->inventory_included = AssetIncludedOptionType::N_A;
        $this->assertSectionCompletionScore($listing, $section, 10, 20);
        $listing->inventory_included = null;

        // A Listing with Equipment/Assets Toggle set to N/A selector should have a section completion score of 10
        // A Listing with Equipment/Assets Toggle set to N/A selector should have a section completion score percentage of 20
        $listing->fixtures_equipment_included = AssetIncludedOptionType::N_A;
        $this->assertSectionCompletionScore($listing, $section, 10, 20);
        $listing->fixtures_equipment_included = null;

        // A Listing with Real Estate Toggle set to N/A selector should have a section completion score of 10
        // A Listing with Real Estate Toggle set to N/A selector should have a section completion score percentage of 20
        $listing->real_estate_included = AssetIncludedOptionType::N_A;
        $this->assertSectionCompletionScore($listing, $section, 10, 20);
        $listing->real_estate_included = null;

        // A listing with Revenue and EBITDA should have a section score total of 10 and a section score percentage of 20
        $listing->revenue = 2000;
        $listing->ebitda = 3000;
        $this->assertSectionCompletionScore($listing, $section, 10, 20);

        // A listing with Pre-Tax Earnings, Revenue and EBITDA should have a section score total of 15 and a section score percentage of 30
        $listing->pre_tax_earnings = 5000;
        $this->assertSectionCompletionScore($listing, $section, 15, 30);

        // A listing with Discretionary Cash Flow, Pre-Tax Earnings, Revenue and EBITDA should have a section score total of 20 and a section score percentage of 40
        $listing->discretionary_cash_flow = 8000;
        $this->assertSectionCompletionScore($listing, $section, 20, 40);

        // A listing with Inventory Toggle Selector, Discretionary Cash Flow, Pre-Tax Earnings, Revenue and EBITDA should have a section score total of 25 and a section score percentage of 50
        $listing->inventory_included = AssetIncludedOptionType::INCLUDED;
        $this->assertSectionCompletionScore($listing, $section, 25, 50);

        // A listing with Inventory, Inventory Toggle Selector, Discretionary Cash Flow, Pre-Tax Earnings, Revenue and EBITDA should have a section score total of 27 and a section score percentage of 54
        $listing->inventory_estimated = 3000;
        $this->assertSectionCompletionScore($listing, $section, 27, 54);

        // A listing with Inventory Description, Inventory, Inventory Toggle Selector, Discretionary Cash Flow, Pre-Tax Earnings, Revenue and EBITDA  should have a section score total of 30 and a section score percentage of 60
        $listing->inventory_description = 'Inventory Description';
        $this->assertSectionCompletionScore($listing, $section, 30, 60);

        // A listing with Equipment/Assets Toggle selector, Inventory Description, Inventory, Inventory Toggle Selector, Discretionary Cash Flow, Pre-Tax Earnings, Revenue and EBITDA should have a section score total of 35 and a section score percentage of 70
        $listing->fixtures_equipment_included = AssetIncludedOptionType::INCLUDED;
        $this->assertSectionCompletionScore($listing, $section, 35, 70);

        // A listing with Equipment/Assets, Equipment/Assets Toggle selector, Inventory Description, Inventory, Inventory Toggle Selector, Discretionary Cash Flow, Pre-Tax Earnings, Revenue and EBITDA should have a section score total of 37 and a section score percentage of 74
        $listing->fixtures_equipment_estimated = 5000;
        $this->assertSectionCompletionScore($listing, $section, 37, 74);

        // A listing with Equipment/Assets Description, Equipment/Assets Toggle selector, Inventory Description, Inventory, Inventory Toggle Selector, Discretionary Cash Flow, Pre-Tax Earnings, Revenue and EBITDA should have a section score total of 40 and a section score percentage of 80
        $listing->fixtures_equipment_description = 'Fixtures equipment description';
        $this->assertSectionCompletionScore($listing, $section, 40, 80);

        // A listing with Real Estate toggle selector, Equipment/Assets Description, Equipment/Assets Toggle selector, Inventory Description, Inventory, Inventory Toggle Selector, Discretionary Cash Flow, Pre-Tax Earnings, Revenue and EBITDA should have a section score total of 45 and a section score percentage of 90
        $listing->real_estate_included = AssetIncludedOptionType::INCLUDED;
        $this->assertSectionCompletionScore($listing, $section, 45, 90);

        // A listing with Real Estate, Real Estate toggle selector, Equipment/Assets Description, Equipment/Assets Toggle selector, Inventory Description, Inventory, Inventory Toggle Selector, Discretionary Cash Flow, Pre-Tax Earnings, Revenue and EBITDA should have a section score total of 47 and a section score percentage of 94
        $listing->real_estate_estimated = 2000;
        $this->assertSectionCompletionScore($listing, $section, 47, 94);

        // A listing with Real Estate Description, Real Estate, Real Estate toggle selector, Equipment/Assets Description, Equipment/Assets Toggle selector, Inventory Description, Inventory, Inventory Toggle Selector, Discretionary Cash Flow, Pre-Tax Earnings, Revenue and EBITDA should have a section score total of 50 and a section score percentage of 100
        $listing->real_estate_description = 'Real estate description';
        $this->assertSectionCompletionScore($listing, $section, 50, 100);
    }

    /**
    * @test
    */
    public function it_calculates_listing_business_details_lcs()
    {
        $listing = factory('App\Listing')->states('lcs-empty')->make([
            'name_visible' => true,
        ]);
        $section = 'business_details';

        // Make sure the listing has the correct empty listing score.
        $this->assertEmptyListingCompletionScore($listing, $section);

        // A Listing with only Products/Services should have a section completion score of 5
        // A Listing with only Products/Services should have a section completion score percentage of 25
        $listing->products_services = 'Products services';
        $this->assertSectionCompletionScore($listing, $section, 5, 25);
        $listing->products_services = null;

        // A Listing with only Market Overview should have a section completion score of 5
        // A Listing with only Market Overview should have a section completion score percentage of 25
        $listing->market_overview = 'Market overview';
        $this->assertSectionCompletionScore($listing, $section, 5, 25);
        $listing->market_overview = null;

        // A Listing with only Competitive Position should have a section completion score of 5
        // A Listing with only Competitive Position should have a section completion score percentage of 25
        $listing->competitive_position = 'Competitive position';
        $this->assertSectionCompletionScore($listing, $section, 5, 25);
        $listing->competitive_position = null;

        // A Listing with only Performance/Outlook should have a section completion score of 5
        // A Listing with only Performance/Outlook should have a section completion score percentage of 25
        $listing->business_performance_outlook = 'Business performance outlook';
        $this->assertSectionCompletionScore($listing, $section, 5, 25);
        $listing->business_performance_outlook = null;

        // A listing with Market Overview and Products/Services should have a section completion score of 10
        // A listing with Market Overview and Products/Services should have a section completion score percentage of 50
        $listing->products_services = 'Products services';
        $listing->market_overview = 'Market overview';
        $this->assertSectionCompletionScore($listing, $section, 10, 50);

        // A listing with Competitive Position, Market Overview and Products/Services should have a section completion score of 15
        // A listing with Competitive Position, Market Overview and Products/Services should have a section completion score percentage of 75
        $listing->competitive_position = 'Competitive position';
        $this->assertSectionCompletionScore($listing, $section, 15, 75);


        // A listing with Performance/Outlook, Competitive Position, Market Overview and Products/Services should have a section completion score of 20
        // A listing with Performance/Outlook, Competitive Position, Market Overview and Products/Services should have a section completion score percentage of 100
        $listing->business_performance_outlook = 'Business performance outlook';
        $this->assertSectionCompletionScore($listing, $section, 20, 100);
    }

    /**
    * @test
    */
    public function it_calculates_listing_transaction_considerations_lcs()
    {
        $listing = factory('App\Listing')->states('lcs-empty')->make([
            'name_visible' => true,
        ]);
        $section = 'transaction_considerations';

        // Make sure the listing has the correct empty listing score.
        $this->assertEmptyListingCompletionScore($listing, $section);

        // A Listing with only Seller Financing Yes/No should have a section completion score of 2
        // A Listing with only Seller Financing Yes/No should have a section completion percentage score of 10
        $listing->financing_available = true;
        $this->assertSectionCompletionScore($listing, $section, 2, 10);
        $listing->financing_available = null;

        // A Listing with only seller Financing Text should have a section completion score of 3
        // A Listing with only seller Financing Text hashould ve a section completion percentage score of 15
        $listing->financing_available_description = 'Financing available description';
        $this->assertSectionCompletionScore($listing, $section, 3, 15);
        $listing->financing_available_description = null;

        // A Listing with only Support Yes/No should have a section completion score of 2
        // A Listing with only Support Yes/No should have a section completion percentage score of 10
        $listing->support_training = true;
        $this->assertSectionCompletionScore($listing, $section, 2, 10);
        $listing->support_training = null;

        // A Listing with only support Text should have a section completion score of 3
        // A Listing with only support Text should have a section completion percentage score of 15
        $listing->support_training_description = 'Support training description';
        $this->assertSectionCompletionScore($listing, $section, 3, 15);
        $listing->support_training_description = null;

        // A Listing with only Reason for Selling should have a section completion score of 3
        // A Listing with only Reason for Selling should have a section completion percentage score of 15
        $listing->reason_for_selling = 'Reason for selling';
        $this->assertSectionCompletionScore($listing, $section, 3, 15);
        $listing->reason_for_selling = null;

        // A Listing with only Desired Sale Date/Timeline have a section completion score of 2
        // A Listing with only Desired Sale Date/Timeline have a section completion percentage score of 10
        $listing->desired_sale_date = 'Desired sale date';
        $this->assertSectionCompletionScore($listing, $section, 2, 10);
        $listing->desired_sale_date = null;

        // A Listing with only Non-compete Yes/No should have a section completion score of 5
        // A Listing with only Non-compete Yes/No should have a section completion percentage score of 25
        $listing->seller_non_compete = true;
        $this->assertSectionCompletionScore($listing, $section, 5, 25);
        $listing->seller_non_compete = null;

        // Financing Available set to not available should still give full credit
        $listing->financing_available = false;
        $listing->financing_available_description = null;
        $this->assertSectionCompletionScore($listing, $section, 5, 25);
        $listing->financing_available = null;

        // Support Training set to not available should still give full credit
        $listing->support_training = false;
        $listing->support_training_description = null;
        $this->assertSectionCompletionScore($listing, $section, 5, 25);
        $listing->support_training = null;

        // Seller Financing Yes/No and Seller Financing Text should have a section completion score of 5
        // Seller Financing Yes/No and Seller Financing Text should haveve a section completion percentage score of 25
        $listing->financing_available = true;
        $listing->financing_available_description = 'Financing available description';
        $this->assertSectionCompletionScore($listing, $section, 5, 25);

        // Support Yes/No, Seller Financing Yes/No and Seller Financing Text should have a section completion score of 7
        // Support Yes/No, Seller Financing Yes/No and Seller Financing Text should have a section completion percentage score of 35
        $listing->support_training = true;
        $this->assertSectionCompletionScore($listing, $section, 7, 35);

        // Support Text, Support Yes/No, Seller Financing Yes/No and Seller Financing Text should have a section completion score of 10
        // Support Text, Support Yes/No, Seller Financing Yes/No and Seller Financing Text should have a section completion percentage score of 50
        $listing->support_training_description = 'Support training description';
        $this->assertSectionCompletionScore($listing, $section, 10, 50);

        // Reason for Selling, Support Text, Support Yes/No, Seller Financing Yes/No and Seller Financing Text should have a section completion score of 13
        // Reason for Selling, Support Text, Support Yes/No, Seller Financing Yes/No and Seller Financing Text should have a section completion percentage score of 65
        $listing->reason_for_selling = 'Reason for selling';
        $this->assertSectionCompletionScore($listing, $section, 13, 65);

        // Desired Sale Date/Timeline, Reason for Selling, Support Text, Support Yes/No, Seller Financing Yes/No and Seller Financing Text have a section completion score of 15
        // Desired Sale Date/Timeline, Reason for Selling, Support Text, Support Yes/No, Seller Financing Yes/No and Seller Financing Text have a section completion percentage score of 75
        $listing->desired_sale_date = 'Desired sale date';
        $this->assertSectionCompletionScore($listing, $section, 15, 75);

        // Non-compete Yes/No, Desired Sale Date/Timeline, Reason for Selling, Support Text, Support Yes/No, Seller Financing Yes/No and Seller Financing Text should have a section completion score of 20
        // Non-compete Yes/No, Desired Sale Date/Timeline, Reason for Selling, Support Text, Support Yes/No, Seller Financing Yes/No and Seller Financing Text should have a section completion percentage score of 100
        $listing->seller_non_compete = true;
        $this->assertSectionCompletionScore($listing, $section, 20, 100);
    }

    /**
    * @test
    */
    public function it_calculates_listing_uploads_lcs()
    {
        $listing = factory('App\Listing')->states('lcs-empty')->make([
            'name_visible' => true,
        ]);
        $section = 'uploads';

        // Make sure the listing has the correct empty listing score.
        $this->assertEmptyListingCompletionScore($listing, $section);

        // Now save/attach a photo to simulate the cover photo
        $listing->save();
        $listing = $listing->fresh();
        $listing->addMediaFromUrl('http://via.placeholder.com/350x150')
        ->toMediaCollection('photos');
        $listing = $listing->fresh();

        // A Listing with only a Cover Photo should have a section completion score of 3
        // A Listing with only a Cover Photo should have a section completion percentage score of 100
        $this->assertSectionCompletionScore($listing, $section, 3, 100);
    }

    /**
    * @test
    */
    public function it_calculates_listing_overview_total_score()
    {
        $listing = factory('App\Listing')->states('lcs-full')->create();
        $listing->addMediaFromUrl('http://via.placeholder.com/350x150')
        ->toMediaCollection('photos');
        $listing = $listing->fresh();

        // Totals
        $basics_total = 16;
        $more_about_the_business_total = 6;
        $financial_details_total = 50;
        $business_details_total = 20;
        $transaction_considerations_total = 20;
        $uploads_total = 3;
        $total = $basics_total + $more_about_the_business_total + $financial_details_total + $business_details_total + $transaction_considerations_total + $uploads_total;

        // Basics Section
        $this->assertSectionCompletionScore(
            $listing,
            'basics',
            $basics_total,
            100
        );

        // More About The Business Section
        $this->assertSectionCompletionScore(
            $listing,
            'more_about_the_business',
            $more_about_the_business_total,
            100
        );

        // Financial Details Section
        $this->assertSectionCompletionScore(
            $listing,
            'financial_details',
            $financial_details_total,
            100
        );

        // Business Details Section
        $this->assertSectionCompletionScore(
            $listing,
            'business_details',
            $business_details_total,
            100
        );

        // Transaction Considerations Section
        $this->assertSectionCompletionScore(
            $listing,
            'transaction_considerations',
            $transaction_considerations_total,
            100
        );

        // Uploads Section
        $this->assertSectionCompletionScore(
            $listing,
            'uploads',
            $uploads_total,
            100
        );

        // Overall total
        $overview = (new ListingCompletionScore($listing))->businessOverviewCalculations;
        $this->assertEquals(1, $overview->totalPercentage());
        $this->assertEquals($total, $overview->total());
    }

    /**
     * Validates the listing is empty and has a completion score of 0
     *
     * @param App\Listing $listing
     * @param string $section
     * @return void
     */
    protected function assertEmptyListingCompletionScore(Listing $listing, string $section)
    {
        $lcs = new ListingCompletionScore($listing);
        $overview = $lcs->businessOverviewCalculations;
        $hsf = $lcs->historicalFinancialCalculations;

        // An empty listing should have a completion score percentage of 0
        // An empty listing should have a section completion score percentage of 0
        $this->assertEquals(0, $lcs->totalPercentage());
        $this->assertEquals(0, $overview->sectionPercentageForDisplay($section));
    }

    /**
     * Validates listing completion score section.
     *
     * @param App\Listing $listing
     * @param string $section
     * @param int $score
     * @param int $percentage
     * @return void
     */
    protected function assertSectionCompletionScore(Listing $listing, string $section, int $score, int $percentage)
    {
        $lcs = (new ListingCompletionScore($listing))->businessOverviewCalculations;
        $this->assertEquals($percentage, $lcs->sectionPercentageForDisplay($section));
        $this->assertEquals($score, $lcs->sectionTotal($section));
    }
}
