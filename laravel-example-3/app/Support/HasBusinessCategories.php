<?php

namespace App\Support;

use App\BusinessCategory;
use Illuminate\Support\Facades\Cache;

trait HasBusinessCategories
{
    /**
     * Gets the business categories for selects.
     *
     * @param boolean $flush
     *
     * @return adrray
     */
    public function getBusinessCategoriesForSelect($flush = false)
    {
        $key = 'business_category_options';

        if ($flush) {
            Cache::forget($key);
        }

        return Cache::rememberForever($key, function () {
            $dbCategories = BusinessCategory::all()->groupby('parent_id')->keyBy(function ($category) {
                return $category->pluck('parent_id')->first();
            });

            // Build up the parents.
            $options = $this->convertParentsToSelectOptions($dbCategories->get(0, collect([])));

            // Build up the children
            $options = $this->addChildrenToOptions($dbCategories->slice(1), $options);

            return $options;
        });
    }

    /**
     * Gets the business parent categories for a select list.
     *
     * @return array
     */
    public function getBusinessParentCategoriesForSelect()
    {
        $categories = $this->getBusinessCategoriesForSelect();

        return collect($categories)->pluck('parent')->toArray();
    }

    /**
     * Gets the business child categories for a select list.
     *
     * @return array
     */
    public function getBusinessChildCategoriesForSelect()
    {
        $categories = $this->getBusinessCategoriesForSelect();
        $options = [];


        foreach ($categories as $category) {
            $parentId = intval($category['parent']['value']);
            $children = $category['children'] ?:[];
            $children = collect($children)
            ->map(function ($children) use ($parentId) {
                $children['parentId'] = $parentId;
                return $children;
            })->toArray();
            $options = array_merge($options, $children);
        }

        return $options;
    }

    /**
     * Converts categories to options.
     *
     * @param  Illuminate\Support\Collection $categories
     *
     * @return array
     */
    protected function convertCategoriesToOptions($categories)
    {
        return $categories->map(function ($category) {
            return [
                'value' => $category->id,
                'label' => $category->label,
            ];
        })
        ->toArray();
    }

    /**
     * Adds the children to the select options.
     *
     * @param array $categories
     * @param array $options
     */
    protected function addChildrenToOptions($categories, $options)
    {
        foreach ($categories as $children) {
            $parentId = $children->first()->parent_id;
            if (isset($options[ $parentId ])) {
                $options[ $parentId ]['children'] = $this->convertCategoriesToOptions($children);
            }
        }

        return $options;
    }

    /**
     * Converts parents to options.
     * Each option will be keyed with the parents id to make matching up children easier.
     *
     * @param  array $categories
     *
     * @return array
     */
    protected function convertParentsToSelectOptions($categories)
    {
        $options = [];
        foreach ($this->convertCategoriesToOptions($categories) as $option) {
            $options[ $option['value'] ]['parent'] = $option;
        }

        return $options;
    }

    /**
     * Gets the business category parents.
     *
     * @return Illuminate\Support\Collection
     */
    public function getBusinessCategoryParents()
    {
        return $this->getBusinessCategories()->map(function ($category) {
            return $category->get('parent');
        });
    }

    /**
     * Gets the business category children.
     *
     * @return Illuminate\Support\Collection
     */
    public function getBusinessCategoryChildren()
    {
        return $this->getBusinessCategories()->map(function ($category) {
            return $category->get('children');
        });
    }

    /**
     * Gets the business categories.
     *
     * @return Illuminate\Support\Collection
     */
    public function getBusinessCategories()
    {
        return collect([
            str_slug('Business & Personal Services', '-') => $this->getBusinessPersonalServices(),
            str_slug('Retail Sales', '-') => $this->getRetailSales(),
            str_slug('Internet Related', '-') => $this->getInternetRelated(),
            str_slug('Manufacturing', '-') => $this->getManufacturing(),
            str_slug('Finance & Insurance', '-') => $this->getFinanceInsurance(),
            str_slug('Wholesale & Distribution', '-') => $this->getWholesaleDistribution(),
            str_slug('Real Estate', '-') => $this->getRealEstate(),
            str_slug('Transportation', '-') => $this->getTransportation(),
            str_slug('Construction', '-') => $this->getConstruction(),
            str_slug('Other', '-') => $this->getOther(),
        ]);
    }

    /**
    * Gets business personal services.
    *
    * @return Illuminate\Support\Collection
    */
    protected function getBusinessPersonalServices()
    {
        $parent = 'Business & Personal Services';
        $children = [
            'Advertising',
            'Amusement & Recreation',
            'Architectural Services',
            'Automotive Rental & Leasing',
            'Automotive Repair',
            'Car Wash',
            'Education',
            'Electrical',
            'Engineering',
            'Equipment Rental & Leasing',
            'Graphic Arts & Design',
            'Hair & Nail Salon',
            'Health',
            'Hotels & Motels',
            'Laundry & Dry Cleaning',
            'Legal',
            'Miscellaneous Repair',
            'Movie Theatre',
            'Museums & Galleries',
            'Other Personal Services',
            'Parking & Valet',
            'Pest Control',
            'Photography & Framing',
            'Plumbing',
            'Residential & Commercial Cleaning',
            'Social Services',
            'Towing',
        ];

        return r_collect(compact('parent', 'children'));
    }

    /**
    * Gets Retail Sales.
    *
    * @return Illuminate\Support\Collection
    */
    protected function getRetailSales()
    {
        $parent = 'Retail Sales';
        $children = [
                'Apparel & Accessories',
                'Auto Parts & Accessories',
                'Bars & Lounges',
                'Building Materials & Hardware',
                'Convenience Stores',
                'Florists',
                'Gas Stations',
                'General Merchandise',
                'Grocery Stores',
                'Home Furniture & Furnishing',
                'Liquor Stores',
                'Marine Supplies & Equipment',
                'Miscellaneous',
                'New and Used Car Dealers',
                'Pet Stores',
                'Recreational Vehicles',
                'Restaurants',
                'Supermarkets',
                'Vending Machines',
        ];

        return r_collect(compact('parent', 'children'));
    }

    /**
     * Gets Internet Related.
     *
     * @return Illuminate\Support\Collection
     */
    protected function getInternetRelated()
    {
        $parent = 'Internet Related';
        $children = [
                'Business Services',
                'Consumer Services',
                'Domain Name',
                'Internet Marketing',
                'Internet Software',
                'Mobile Application',
                'Marketing Services',
                'Website Design Services',
        ];

        return r_collect(compact('parent', 'children'));
    }

    /**
     * Gets Manufacturing.
     *
     * @return Illuminate\Support\Collection
     */
    protected function getManufacturing()
    {
        $parent = 'Manufacturing';
        $children = [
                'Apparel',
                'Chemicals',
                'Electrical Components',
                'Fabricated Metals',
                'Food & Kindred Products',
                'Furniture & Fixtures',
                'Industrial Machinery',
                'Leather & Leather Products',
                'Lumber',
                'Mechanical Instruments',
                'Miscellaneous Manufacturing',
                'Paper Products',
                'Petroleum Refining',
                'Primary Metals',
                'Printing & Publishing',
                'Rubber & Plastic',
                'Stone, Glass, and Concrete Products',
                'Textile Mill Products',
                'Tobacco Products',
                'Transportation Equipment',
        ];

        return r_collect(compact('parent', 'children'));
    }

    /**
     * Gets Finance & Insurance.
     *
     * @return Illuminate\Support\Collection
     */
    protected function getFinanceInsurance()
    {
        $parent = 'Finance & Insurance';
        $children =  [
                'Accounting & Tax Services',
                'Agents & Brokers',
                'Consulting',
                'Depository Institutions',
                'Financial Advisory',
                'Insurance Carriers',
                'Nondepository Credit Institutions',
                'Other Investment Services',
                'Securities & Exchanges',
        ];

        return r_collect(compact('parent', 'children'));
    }

    /**
     * Gets Wholesale & Distribution.
     *
     * @return Illuminate\Support\Collection
     */
    protected function getWholesaleDistribution()
    {
        $parent = 'Wholesale & Distribution';
        $children = [
                'Durable Goods',
                'Nondurable Goods',
        ];

        return r_collect(compact('parent', 'children'));
    }

    /**
     * Gets Real Estate.
     *
     * @return Illuminate\Support\Collection
     */
    protected function getRealEstate()
    {
        $parent = 'Real Estate';
        $children = [
                'For Lease',
                'For Sale',
        ];

        return r_collect(compact('parent', 'children'));
    }

    /**
     * Gets Transportation.
     *
     * @return Illuminate\Support\Collection
     */
    protected function getTransportation()
    {
        $parent = 'Transportation';
        $children = [
                'Air Transportation',
                'Communications',
                'Motor Freight Transit',
                'Pipelines',
                'Railroad Transit',
                'Suburban Transit',
                'Transportation Services',
                'Water Transportation',
        ];

        return r_collect(compact('parent', 'children'));
    }

    /**
     * Gets Construction.
     *
     * @return Illuminate\Support\Collection
     */
    protected function getConstruction()
    {
        $parent = 'Construction';
        $children = [
                'Building / General Contractor',
                'Heavy Construction',
                'Special Trades',
        ];

        return r_collect(compact('parent', 'children'));
    }

    /**
     * Gets Other.
     *
     * @return Illuminate\Support\Collection
     */
    protected function getOther()
    {
        $parent = 'Other';
        $children = [
                'Agriculture, Forestry & Fishing',
                'Electric, Gas, and Sanitary Services',
                'Metals & Mining',
                'Oil & Gas',
                'Other',
        ];

        return r_collect(compact('parent', 'children'));
    }

    /**
     * Sets the details.
     *
     * @param string $label
     * @param mixed $value
     */
    protected function setDetails($label, $value = null)
    {
        $value = is_null($value) ? $label : $value;

        return compact('label', 'value');
    }
}
