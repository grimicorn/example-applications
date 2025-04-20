<?php

namespace App\Tools;

use App\User;
use App\Listing;
use App\SavedSearch;
use App\SavedSearchNotification;
use App\Support\Watchlist\WatchlistMatches;

class WatchlistStory
{
    protected $spacer = '&nbsp;&nbsp;&nbsp;&nbsp;';
    protected $watchlistMatches;

    public function __construct()
    {
        $this->watchlistMatches = new WatchlistMatches;
    }

    public function getResults()
    {
        $buyer1 = User::where('email', 'wlbuyer01@mailinator.com')->first();
        $buyer2 = User::where('email', 'wlbuyer02@mailinator.com')->first();
        $buyer3 = User::where('email', 'wlbuyer03@mailinator.com')->first();

        return collect([
            SavedSearch::where([
                'name' => 'Retail sales charlotte',
                'user_id' => $buyer1->id,
            ])->first(),
            SavedSearch::where([
                'name' => 'bar keyword ',
                'user_id' => $buyer1->id,
            ])->first(),
            SavedSearch::where([
                'name' => 'test keyword',
                'user_id' => $buyer1->id,
            ])->first(),
            SavedSearch::where([
                'name' => 'NYC distro',
                'user_id' => $buyer2->id,
            ])->first(),
            SavedSearch::where([
                'name' => 'under 2mm',
                'user_id' => $buyer2->id,
            ])->first(),
            SavedSearch::where([
                'name' => 'NY retail 500k',
                'user_id' => $buyer2->id,
            ])->first(),
            SavedSearch::where([
                'name' => 'Tampa BPS',
                'user_id' => $buyer3->id,
            ])->first(),
            SavedSearch::where([
                'name' => 'Tampa car wash',
                'user_id' => $buyer3->id,
            ])->first(),
            SavedSearch::where([
                'name' => 'Retail under 1mm',
                'user_id' => $buyer3->id,
            ])->first(),
        ])->filter()
        ->groupBy('user_id')
        ->map(function ($searches) {
            $user = $searches->first()->user;
            $lines = collect([
                "<strong>User:</strong> {$user->name} | {$user->email} | {$user->id}",
            ]);

            return $lines->concat($this->searchLines($searches))->flatten()->implode('<br><br>');
        })
        ->values()->implode('<br><br>*************************************<br><br>');
    }

    protected function searchLines($searches)
    {
        return $searches->map(function ($search) {
            return collect([
                "{$this->spacer}<strong>Saved Search:</strong> {$search->name} | {$search->id}:",
            ])
            ->concat($this->searchParameterLines($search))
            ->concat($this->listingLines($search))
            ->concat($this->expectedMatchLines($search))
            ->implode('<br>');
        });
    }

    protected function searchParameterLines(SavedSearch $search)
    {
        return collect([
            "{$this->spacer}{$this->spacer}<strong>Parameters:</strong>",
            "{$this->spacer}{$this->spacer}{$this->spacer}<strong>Business Categories:</strong> " . collect($search->business_category_labels ?? [])->implode(','),
            "{$this->spacer}{$this->spacer}{$this->spacer}<strong>Keyword:</strong> {$search->keyword}",
            "{$this->spacer}{$this->spacer}{$this->spacer}<strong>Zip Code Radius:</strong> {$search->zip_code_radius}",
            "{$this->spacer}{$this->spacer}{$this->spacer}<strong>Zip Code:</strong> {$search->zip_code}",
            "{$this->spacer}{$this->spacer}{$this->spacer}<strong>Asking Price Min:</strong> {$search->asking_price_min}",
            "{$this->spacer}{$this->spacer}{$this->spacer}<strong>Asking Price Max:</strong> {$search->asking_price_max}"
        ]);
    }

    protected function listingLines(SavedSearch $search)
    {
        $notifications = SavedSearchNotification::where([
            'saved_search_id' => $search->id,
        ])->get();
        $listings = $search->listings;
        $count = $listings->count();

        $listingLines = $listings->map(function ($listing) use ($search, $notifications) {
            return collect([
                "{$this->spacer}{$this->spacer}{$this->spacer}<strong>Business:</strong> {$listing->title} | {$listing->id}",
            ])
            ->concat($this->listingNotifications($listing, $notifications))
            ->implode('<br>');
        });

        return collect([
            "{$this->spacer}{$this->spacer}<strong>Matched Businesses ({$count}):</strong>"
        ])->concat($listingLines);
    }

    protected function listingNotifications(Listing $listing, $notifications)
    {
        $count = $notifications->where('listing_id', $listing->id)->count();
        $color = ($count === 1) ? 'mediumseagreen' : 'red';

        return collect([
            "{$this->spacer}{$this->spacer}{$this->spacer}<span style='color:{$color}'><strong>Notification Count:</strong> {$count}</span>",
        ]);

        return $notificationLines;
    }

    protected function expectedMatchLines(SavedSearch $search)
    {
        $listings = $this->watchlistMatches->forSearch($search);
        $count = $listings->count();
        $listingLines = $listings->map(function ($listing) {
            return "{$this->spacer}{$this->spacer}{$this->spacer}<strong>Listing:</strong> {$listing->title} | {$listing->id}";
        });
        return collect([
            "{$this->spacer}{$this->spacer}<strong>Expected Matches ($count):</strong>",
        ])->concat($listingLines);
    }
}
