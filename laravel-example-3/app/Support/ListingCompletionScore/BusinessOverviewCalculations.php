<?php

namespace App\Support\ListingCompletionScore;

use App\Support\AssetIncludedOptionType;
use App\Support\ListingCompletionScore\Calculations;

class BusinessOverviewCalculations extends Calculations
{
    use AppliesPenalty;

    protected $calculationType = 'overview';

    public function __construct($listing)
    {
        parent::__construct($listing);
    }

    /**
     * {@inheritDoc}
     */
    protected function points()
    {
        return r_collect([
            'basics' => [ // 16
                'title' => 1,
                'business_category_id' => 1,
                'business_name' => 1,
                'asking_price' => 2,
                'summary_business_description' => 3,
                'business_description' => 5,
                'city' => 1,
                'state' => 1,
                'zip_code' => 1,
            ],
            'more_about_the_business' => [ // 6
                'year_established' => 1,
                'number_of_employees' => 1,
                'location_description' => 3,
                'address_1' => 1,
            ],
            'financial_details' => [ // 50
                'revenue' => 5,
                'ebitda' => 5,
                'pre_tax_earnings' => 5,
                'discretionary_cash_flow' => 5,
                'inventory_included' => 5,
                'inventory_estimated' => 2,
                'inventory_description' => 3,
                'fixtures_equipment_included' => 5,
                'fixtures_equipment_estimated' => 2,
                'fixtures_equipment_description' => 3,
                'real_estate_included' => 5,
                'real_estate_estimated' => 2,
                'real_estate_description' => 3,
            ],
            'business_details' => [ // 20
                'products_services' => 5,
                'market_overview' => 5,
                'competitive_position' => 5,
                'business_performance_outlook' => 5,
            ],
            'transaction_considerations' => [ // 20
                'reason_for_selling' => 3,
                'desired_sale_date' => 2,
                'financing_available' => 2,
                'financing_available_description' => 3,
                'support_training' => 2,
                'support_training_description' => 3,
                'seller_non_compete' => 5,
            ],
            'uploads' => [ // 3
                'cover_photo' => 3,
            ],
        ]);
    }


    /**
     * Calculate basics section
     *
     * @return integer
     */
    public function basicsSectionTotal()
    {
        $score = 0;
        $points = $this->points()->get('basics');

        // Title
        if (!is_null($this->listing->title)) {
            $score = $score + floatval($points->get('title'));
        }

        // Business Name
        if (!is_null($this->listing->business_name)) {
            $score = $score + floatval($points->get('business_name'));
        }

        // Asking Price
        if (!is_null($this->listing->asking_price)) {
            $score = $score + floatval($points->get('asking_price'));
        }

        // Summary Business Description
        if (!is_null($this->listing->summary_business_description)) {
            $score = $score + floatval($points->get('summary_business_description'));
        }

        // Business Description
        if (!is_null($this->listing->business_description)) {
            $score = $score + floatval($points->get('business_description'));
        }

        // Business Category
        if (!is_null($this->listing->business_category_id) and !is_null($this->listing->business_sub_category_id)) {
            $score = $score + floatval($points->get('business_category_id'));
        }

        // City
        if (!is_null($this->listing->city)) {
            $score = $score + floatval($points->get('city'));
        }

        // State
        if (!is_null($this->listing->state)) {
            $score = $score + floatval($points->get('state'));
        }

        // Zip Code
        if (!is_null($this->listing->zip_code)) {
            $score = $score + floatval($points->get('zip_code'));
        }

        return $this->calculatePenalty($score, $this->listing, $this->calculationType);
    }

    /**
     * Calculate more about the business section
     *
     * @return integer
     */
    public function moreAboutTheBusinessSectionTotal()
    {
        $score = 0;
        $points = $this->points()->get('more_about_the_business');

        // Address Line 1
        if (!is_null($this->listing->address_1)) {
            $score = $score + floatval($points->get('address_1'));
        }

        // Year Established
        if (!is_null($this->listing->year_established)) {
            $score = $score + floatval($points->get('year_established'));
        }

        // Number of Employees
        if (!is_null($this->listing->number_of_employees)) {
            $score = $score + floatval($points->get('number_of_employees'));
        }

        // Location Description
        if (!is_null($this->listing->location_description)) {
            $score = $score + floatval($points->get('location_description'));
        }

        return $this->calculatePenalty($score, $this->listing, $this->calculationType);
    }

    /**
     * Calculate financial details section
     *
     * @return integer
     */
    public function financialDetailsSectionTotal()
    {
        $score = 0;
        $points = $this->points()->get('financial_details');

        // Revenue
        if (!is_null($this->listing->revenue)) {
            $score = $score + floatval($points->get('revenue'));
        }

        // EBITDA
        if (!is_null($this->listing->ebitda)) {
            $score = $score + floatval($points->get('ebitda'));
        }

        // Pre-Tax Earnings
        if (!is_null($this->listing->pre_tax_earnings)) {
            $score = $score + floatval($points->get('pre_tax_earnings'));
        }

        // Discretionary Cash Flow
        if (!is_null($this->listing->discretionary_cash_flow)) {
            $score = $score + floatval($points->get('discretionary_cash_flow'));
        }

        // Inventory Included
        // If N/A then they get all of the points
        // Other wise each individual section needs to be checked individually
        if ($this->listing->inventory_included === AssetIncludedOptionType::N_A) {
            // Inventory Included (N/A)
            $score = $score + floatval($points->get('inventory_included'));
            $score = $score + floatval($points->get('inventory_estimated'));
            $score = $score + floatval($points->get('inventory_description'));
        } else {
            // Inventory Included (Included/Not Included)
            if (!is_null($this->listing->inventory_included)) {
                $score = $score + floatval($points->get('inventory_included'));
            }

            // Inventory Included Estimated
            if (!is_null($this->listing->inventory_estimated)) {
                $score = $score + floatval($points->get('inventory_estimated'));
            }

            // Inventory Included Description
            if (!is_null($this->listing->inventory_description)) {
                $score = $score + floatval($points->get('inventory_description'));
            }
        }

        // Fixtures Equipment
        // If N/A then they get all of the points
        // Other wise each individual section needs to be checked individually
        if ($this->listing->fixtures_equipment_included === AssetIncludedOptionType::N_A) {
            // Fixtures Equipment (N/A)
            $score = $score + floatval($points->get('fixtures_equipment_included'));
            $score = $score + floatval($points->get('fixtures_equipment_estimated'));
            $score = $score + floatval($points->get('fixtures_equipment_description'));
        } else {
            // Fixtures Equipment (Included/Not Included)
            if (!is_null($this->listing->fixtures_equipment_included)) {
                $score = $score + floatval($points->get('fixtures_equipment_included'));
            }

            // Fixtures Equipment Estimated
            if (!is_null($this->listing->fixtures_equipment_estimated)) {
                $score = $score + floatval($points->get('fixtures_equipment_estimated'));
            }

            // Fixtures Equipment Description
            if (!is_null($this->listing->fixtures_equipment_description)) {
                $score = $score + floatval($points->get('fixtures_equipment_description'));
            }
        }

        // Real Estate
        // If N/A then they get all of the points
        // Other wise each individual section needs to be checked individually
        if ($this->listing->real_estate_included === AssetIncludedOptionType::N_A) {
            // Real Estate (N/A)
            $score = $score + floatval($points->get('real_estate_included'));
            $score = $score + floatval($points->get('real_estate_estimated'));
            $score = $score + floatval($points->get('real_estate_description'));
        } else {
            // Real Estate (Included/Not Included)
            if (!is_null($this->listing->real_estate_included)) {
                $score = $score + floatval($points->get('real_estate_included'));
            }

            // Real Estate Estimated
            if (!is_null($this->listing->real_estate_estimated)) {
                $score = $score + floatval($points->get('real_estate_estimated'));
            }

            // Real Estate Description
            if (!is_null($this->listing->real_estate_description)) {
                $score = $score + floatval($points->get('real_estate_description'));
            }
        }

        return $this->calculatePenalty($score, $this->listing, $this->calculationType);
    }

    /**
     * Calculate business details section
     *
     * @return integer
     */
    public function businessDetailsSectionTotal()
    {
        $score = 0;
        $points = $this->points()->get('business_details');

        // Products Services
        if (!is_null($this->listing->products_services)) {
            $score = $score + floatval($points->get('products_services'));
        }

        // Market Overview
        if (!is_null($this->listing->market_overview)) {
            $score = $score + floatval($points->get('market_overview'));
        }

        // Competitive Position
        if (!is_null($this->listing->competitive_position)) {
            $score = $score + floatval($points->get('competitive_position'));
        }

        // Business Performance Outlook
        if (!is_null($this->listing->business_performance_outlook)) {
            $score = $score + floatval($points->get('business_performance_outlook'));
        }

        return $this->calculatePenalty($score, $this->listing, $this->calculationType);
    }

    /**
     * Calculate transaction considerations section
     *
     * @return integer
     */
    public function transactionConsiderationsSectionTotal()
    {
        $score = 0;
        $points = $this->points()->get('transaction_considerations');

        // Financing Available
        if (!is_null($this->listing->financing_available)) {
            $score = $score + floatval($points->get('financing_available'));
        }

        // Financing Available Description
        if (!is_null($this->listing->financing_available_description) or $this->listing->financing_available === false) {
            $score = $score + floatval($points->get('financing_available_description'));
        }

        // Support Training
        if (!is_null($this->listing->support_training)) {
            $score = $score + floatval($points->get('support_training'));
        }

        // Support Training Description
        if (!is_null($this->listing->support_training_description) or $this->listing->support_training === false) {
            $score = $score + floatval($points->get('support_training_description'));
        }

        // Reason For Selling
        if (!is_null($this->listing->reason_for_selling)) {
            $score = $score + floatval($points->get('reason_for_selling'));
        }

        // Desired Sale Date
        if (!is_null($this->listing->desired_sale_date)) {
            $score = $score + floatval($points->get('desired_sale_date'));
        }

        // Seller Non Compete
        if (!is_null($this->listing->seller_non_compete)) {
            $score = $score + floatval($points->get('seller_non_compete'));
        }

        return $this->calculatePenalty($score, $this->listing, $this->calculationType);
    }

    /**
     * Calculate uploads section
     *
     * @return integer
     */
    public function uploadsSectionTotal()
    {
        $score = 0;
        $points = $this->points()->get('uploads');

        if (!is_null($this->listing->cover_photo)) {
            $score = $score + floatval($points->get('cover_photo'));
        }

        return $this->calculatePenalty($score, $this->listing, $this->calculationType);
    }

    /**
     * {@inheritDoc}
     */
    public function total()
    {
        return array_sum([
            $this->basicsSectionTotal(),
            $this->moreAboutTheBusinessSectionTotal(),
            $this->financialDetailsSectionTotal(),
            $this->businessDetailsSectionTotal(),
            $this->transactionConsiderationsSectionTotal(),
            $this->uploadsSectionTotal(),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function totalPossible()
    {
        return $this->points()->map(function ($section, $key) {
            return $this->sectionTotalPossible($key);
        })->sum();
    }


    /**
     * {@inheritDoc}
     */
    public function sectionTotalPossible($section)
    {
        $points = $this->points()->get($section, collect([]));

        if ('uploads' === $section) {
            return $points->filter(function ($item, $key) {
                return $key !== 'up_to_5';
            })->sum();
        }

        return $points->sum();
    }

    /**
     * {@inheritDoc}
     */
    public function sectionTotal($section)
    {
        // We need to find a matching method.
        // Methods should be section keys that are snake case converted to camel case with SectionTotal appended.
        // Example: more_about_the_business -> moreAboutTheBusinessSectionTotal
        $method = camel_case("{$section}_section_total");
        if (!method_exists($this, $method)) {
            return 0;
        }

        return floatval($this->$method());
    }
}
