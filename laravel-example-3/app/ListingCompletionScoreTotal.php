<?php

namespace App;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class ListingCompletionScoreTotal extends BaseModel
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        // 'listing_id',

        // Total
        'total',

        // Historical Financials.
        'historical_financials',
        'sources_of_income',
        'employee_related_expenses',
        'office_related_expenses',
        'selling_general_and_administrative_expenses',
        'finance_related_expenses',
        'other_cash_flow_items',
        'non_recurring_personal_or_extra_expenses',
        'balance_sheet_recurring_assets',
        'balance_sheet_long_term_assets',
        'balance_sheet_current_liabilities',
        'balance_sheet_long_term_liabilities',
        'balance_sheet_shareholders_equity',

        // Business Overview
        'business_overview',
        'basics',
        'more_about_the_business',
        'financial_details',
        'business_details',
        'transaction_considerations',
        'uploads',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'listing_id' => 'integer',
        'total' => 'integer',
        'historical_financials' => 'integer',
        'sources_of_income' => 'integer',
        'employee_related_expenses' => 'integer',
        'office_related_expenses' => 'integer',
        'selling_general_and_administrative_expenses' => 'integer',
        'finance_related_expenses' => 'integer',
        'other_cash_flow_items' => 'integer',
        'non_recurring_personal_or_extra_expenses' => 'integer',
        'balance_sheet_recurring_assets' => 'integer',
        'balance_sheet_long_term_assets' => 'integer',
        'balance_sheet_current_liabilities' => 'integer',
        'balance_sheet_long_term_liabilities' => 'integer',
        'balance_sheet_shareholders_equity' => 'integer',
        'business_overview' => 'integer',
        'basics' => 'integer',
        'more_about_the_business' => 'integer',
        'financial_details' => 'integer',
        'business_details' => 'integer',
        'transaction_considerations' => 'integer',
        'uploads' => 'integer',
        'custom_penalty' => 'integer',
    ];

    /**
     * Get the listing that owns the listing completion score.
     */
    public function listing()
    {
        return $this->belongsTo('App\Listing');
    }

    public function getCustomPenaltyAttribute($value)
    {
        return intval($value);
    }
}
