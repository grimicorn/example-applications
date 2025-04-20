<?php

namespace App\Support\Listing;

use Illuminate\Support\Carbon;

trait FiltersSearchQuery
{
    /**
     * Adds the business categories query filters
     *
     * @param Builder $query
     * @return Builder
     */
    protected function addBusinessCategoriesQueryFilters($query)
    {
        $business_categories = collect($this->queryArgs->get('business_categories', []));
        if ($business_categories->isEmpty()) {
            return $query;
        }

        $query->where(function ($q) use ($business_categories) {
            foreach ($business_categories as $business_category) {
                $q->orWhere('business_category_id', $business_category)
                ->orWhere('business_sub_category_id', $business_category);
            }
        });

        return $query;
    }

    /**
     * Adds the listing updated query filters
     *
     * @param Builder $query
     * @return Builder
     */
    protected function addListingUpdatedQueryFilters($query)
    {
        $updated = collect($this->queryArgs->get('listing_updated'))->filter();

        if ($updated->isEmpty()) {
            return $query;
        }

        $start = null;
        $end = Carbon::now()->endOfDay();

        if ($updated->contains('last_year')) {
            $start = Carbon::now()->subYear()->startOfDay();
            $query->whereBetween('updated_at', [$start, $end]);
        } elseif ($updated->contains('last_three_months')) {
            $start = Carbon::now()->subMonths(3)->startOfDay();
            $query->whereBetween('updated_at', [$start, $end]);
        } elseif ($updated->contains('last_month')) {
            $start = Carbon::now()->subMonth()->startOfDay();
            $query->whereBetween('updated_at', [$start, $end]);
        }

        return $query;
    }

    /**
     * Adds the shared query filters
     *
     * @param Builder $query
     * @param int $listing_id
     * @return Builder
     */
    protected function addQueryFilters($query, $listing_id = null)
    {
        if ($listing_id) {
            $query->where('id', $listing_id);
        }

        // Remove unpublished listings if not disabled.
        if (!$this->restrictionDisabled()) {
            $query = $query->published();
        }

        $query->where(function ($q) {
            // Filter specific user
            if ($userId = intval($this->queryArgs->get('user'))) {
                $q->where('user_id', $userId)->where('display_listed_by', true);
            }

            // Filter state
            if ($state = $this->queryArgs->get('state')) {
                $q->where('state', $state);
            }

            // Discretionary Cash Flow Maximum
            if ($this->queryArgs->get('cash_flow_max')) {
                $q->where('discretionary_cash_flow', '<=', $this->queryArgs->get('cash_flow_max'));
            }

            // Discretionary Cash Flow Minimum
            if ($this->queryArgs->get('cash_flow_min')) {
                $q->where('discretionary_cash_flow', '>=', $this->queryArgs->get('cash_flow_min'));
            }

            // Pre-Tax Earnings Maximum
            if ($this->queryArgs->get('pre_tax_income_max')) {
                $q->where('pre_tax_earnings', '<=', $this->queryArgs->get('pre_tax_income_max'));
            }

            // Pre-Tax Earnings Minimum
            if ($this->queryArgs->get('pre_tax_income_min')) {
                $q->where('pre_tax_earnings', '>=', $this->queryArgs->get('pre_tax_income_min'));
            }

            // EBITDA Maximum
            if ($this->queryArgs->get('ebitda_max')) {
                $q->where('ebitda', '<=', $this->queryArgs->get('ebitda_max'));
            }

            // EBITDA Minimum
            if ($this->queryArgs->get('ebitda_min')) {
                $q->where('ebitda', '>=', $this->queryArgs->get('ebitda_min'));
            }

            // Revenue Maximum
            if ($this->queryArgs->get('revenue_max')) {
                $q->where('revenue', '<=', $this->queryArgs->get('revenue_max'));
            }

            // Revenue Minimum
            if ($this->queryArgs->get('revenue_min')) {
                $q->where('revenue', '>=', $this->queryArgs->get('revenue_min'));
            }

            // Asking Price Maximum
            if ($this->queryArgs->get('asking_price_max')) {
                $q->where('asking_price', '<=', $this->queryArgs->get('asking_price_max'));
            }

            // Asking Price Minimum
            if ($this->queryArgs->get('asking_price_min')) {
                $q->where('asking_price', '>=', $this->queryArgs->get('asking_price_min'));
            }
        });

        // Business categories
        $query = $this->addBusinessCategoriesQueryFilters($query);

        // Listing updated at
        $query = $this->addListingUpdatedQueryFilters($query);

        return $query;
    }
}
