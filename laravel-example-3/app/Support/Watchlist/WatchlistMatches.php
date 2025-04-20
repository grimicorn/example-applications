<?php

namespace App\Support\Watchlist;

use App\User;
use App\SavedSearch;
use App\SavedSearchListing;
use App\Support\Listing\Search;
use Illuminate\Support\Collection;
use App\Jobs\RefreshWatchlistMatchesForUser;
use App\Support\Notification\HasNotifications;
use App\Jobs\RefreshWatchlistMatchesForUserSearch;
use App\Jobs\ProcessRefreshWatchlistMatchesForUser;
use App\Support\Watchlist\WatchlistUserSearchResult;
use App\Jobs\SendRefreshWatchlistForUserNotification;
use App\Support\Watchlist\RefreshForUserNotification;

class WatchlistMatches
{
    protected $notify;

    public function __construct($notify = true)
    {
        $this->notify = $notify;
    }

    public function forSearch(SavedSearch $search)
    {
        return (new Search(
            $queryArgs = $this->queryArgsFromSearch($search),
            $listing_id = null,
            $paginated = false
        ))
        ->execute()
        ->reject(function ($listing) use ($search) {
            return $listing->user_id === $search->user_id;
        });
    }

    public function refreshForSearch(SavedSearch $search)
    {
        $matches = $this->forSearch($search);
        $matched_ids = $matches->pluck('id');

        // Set the refreshed at time
        $search->refreshed_at = now();
        $search->save();

        // Delete the old listings that no longer match.
        $search->savedSearchListings()->whereNotIn('listing_id', $matched_ids)->get()->each->delete();

        // Update the new ids
        $matched_ids->each(function ($id) use ($search) {
            SavedSearchListing::firstOrCreate([
                'saved_search_id' => $search->id,
                'listing_id' => $id,
            ]);
        });

        return $matches;
    }

    public function forUserSearch(SavedSearch $search)
    {
        return new WatchlistUserSearchResult(
            $search,
            $listings = $this->refreshForSearch($search)
        );
    }

    protected function refreshForUserJobs(Collection $searches, User $user)
    {
        $jobs = $searches->map(function ($search) {
            return new RefreshWatchlistMatchesForUserSearch($search, $this);
        });

        if ($this->notify) {
            $jobs->push(
                new SendRefreshWatchlistForUserNotification($user, $this)
            );
        }

        return $jobs;
    }

    public function refreshForUser(User $user)
    {
        $searches = SavedSearch::where('user_id', $user->id)->get();
        if ($searches->isEmpty()) {
            return $user;
        }

        ProcessRefreshWatchlistMatchesForUser::withChain(
            $this->refreshForUserJobs($searches, $user)->toArray()
        )->dispatch($user);

        return $user;
    }

    public function sendRefreshForUserNotification(User $user)
    {
        if (!$this->notify) {
            return;
        }

        (new RefreshForUserNotification($user))->dispatch();
    }

    public function refreshForAllUsers()
    {
        User::orderBy('created_at')
        ->chunk(100, function ($users) {
            $users->each(function ($user) {
                \Illuminate\Support\Facades\Log::info("Refresh for user {$user->name} (user_id:{$user->id})");
                RefreshWatchlistMatchesForUser::dispatch($user, $this);
            });
        });
    }

    protected function queryArgsFromSearch(SavedSearch $search)
    {
        return collect($search->toArray())->only([
            'keyword',
            'zip_code',
            'business_categories',
            'zip_code_radius',
            'asking_price_min',
            'asking_price_max',
        ])->toArray();
    }
}
