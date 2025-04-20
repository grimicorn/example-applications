<?php

use App\User;
use App\Listing;
use App\SavedSearch;
use App\BusinessCategory;
use Illuminate\Support\Facades\Artisan;

// phpcs:ignore
trait HasSeederHelpers
{
    protected function userByEmailOrCreate($email)
    {
        if ($user = User::where('email', $email)->first()) {
            return $user;
        }

        return factory(User::class)->create([
            'email' => $email,
        ]);
    }

    protected function savedSearchOrCreate($attributes)
    {
        $attributes = collect($attributes)->except([
            'business_categories',
        ])->filter()->toArray();

        if ($search = SavedSearch::where($attributes)->first()) {
            return $search;
        }

        return factory(SavedSearch::class)->states('empty')->create($attributes);
    }

    protected function listingOrCreate($attributes)
    {
        if ($listing = Listing::where($attributes)->first()) {
            return $listing;
        }

        return factory(Listing::class)->states('published')->create($attributes);
    }

    protected function getBusinesCategoryIds($labels)
    {
        return collect($labels)->map(function ($label) {
            return $this->getBusinesCategoryId($label);
        })->toArray();
    }

    protected function getBusinesCategoryId($label)
    {
        return optional(BusinessCategory::where('label', $label)->first())->id;
    }

    protected function resetSavedSearch(SavedSearch $search)
    {
        $search->notifications->map->delete();
        $search->listings->map->delete();
    }

    protected function resetApplicationState()
    {
        Artisan::call('queue:clear');
        Artisan::call('cache:clear');
        Artisan::call('queue:flush');
    }
}
