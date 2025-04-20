<?php

namespace App;

use App\BaseModel;
use App\BusinessCategory;
use Illuminate\Database\Eloquent\Model;

class UserDesiredPurchaseCriteria extends BaseModel
{
    protected $table = 'user_desired_purchase_criterias';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'business_categories' => 'array',
        'locations' => 'string',
    ];

    /**
     * The attribute defaults.
     *
     * @var array
     */
    protected $attributes = [
       'business_categories' => '[]',
        'locations' => '',
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_categories',
        'locations',
        'asking_price_minimum',
        'asking_price_maximum',
        'revenue_minimum',
        'revenue_maximum',
        'ebitda_minimum',
        'ebitda_maximum',
        'pre_tax_income_minimum',
        'pre_tax_income_maximum',
        'discretionary_cash_flow_minimum',
        'discretionary_cash_flow_maximum',
    ];

    /*
      * The business categories that belong to the user purchase criteria.
     */
    public function businessCategories()
    {
        return BusinessCategory::whereIn('id', $this->business_categories)->get();
    }

    public function businessCategoriesFiltered()
    {
        // If a parent category is selected show the parent category and no child categories.
        // Otherwise show all categories.

        $categories = $this->businessCategories();
        $parents = $categories->filter(function ($category) {
            return $category->parent_id === 0;
        });

        return $categories->reject(function ($category) use ($parents) {
            return $parents->contains($category->parent_id);
        });
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $data = collect($this->toArray())->only([
            'locations',
        ])->toArray();

        $data['business_types'] = $this->businessCategories()
        ->pluck('label');

        return $data;
    }
}
