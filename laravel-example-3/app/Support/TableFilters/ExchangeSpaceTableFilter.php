<?php

namespace App\Support\TableFilters;

use App\ExchangeSpace;
use App\ExchangeSpaceMember;
use App\Support\ExchangeSpaceStatusType;
use App\Support\TableFilters\ExchangeSpaceFilter;

class ExchangeSpaceTableFilter extends ExchangeSpaceFilter
{
    protected $dateSortKey = 'updated_at';

    /**
     * {@inheritDoc}
     */
    protected function filterResults($results)
    {
        $listing_id = intval(request()->get('listing_id'));
        if ($listing_id <= 0) {
            return $results;
        }

        return $results->filter(function ($result) use ($listing_id) {
            return intval($result->listing->id) === $listing_id;
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function searchResults($results)
    {
        return $this->searchSharedColumnResults($results, function ($search, $result) {
            // Search for a matching status.
            if ($this->searchStatus($search, $result)) {
                return true;
            }


            return false;
        });
    }

    /**
     * Searches against the statuses.
     *
     * @param  string $search
     * @param  App\ExchangeSpace $result
     *
     * @return boolean
     */
    protected function searchStatus($search, $result)
    {
        return !collect(ExchangeSpaceStatusType::getLabels())
        ->filter(function ($label) use ($search, $result) {
            $label = strtolower($label);
            $search = strtolower($search);

            // First make sure that a label matches.
            if (!str_contains($label, $search)) {
                return false;
            }

            // Now match the status label.
            $statusLabel = ExchangeSpaceStatusType::getLabel($result->status);
            return strtolower($statusLabel) === $label;
        })->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    protected function sortResults($results)
    {
        $results = $this->sortSharedColumnResults($results, function ($sortKey, $results) {
            switch ($sortKey) {
                case 'stage':
                    $results = $results->sortBy(function ($result) {
                        return $result->deal;
                    });
                    break;
            }

            return $results;
        });

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    protected function query()
    {
        // First get the member exchange space ids.
        $exchangeSpaceIds = ExchangeSpaceMember::ofCurrentUser()
                            ->active()
                            ->approved()
                            ->get()
                            ->pluck('exchange_space_id')
                            ->toArray();

        return ExchangeSpace::whereIn('id', $exchangeSpaceIds)
        ->notInquiries()
        ->with('conversations.messages', 'listing', 'members.user');
    }


    /**
     * {@inheritdoc}
     */
    public function getSortKey()
    {
        return $this->request->get('sortKey', 'updated_at');
    }

    /**
     * Gets the sort order.
     *
     * @return string
     */
    protected function getSortOrder()
    {
        $default = ($this->getSortKey() === 'updated_at') ? 'desc' : 'asc';
        return $this->request->get('sortOrder', $default);
    }
}
