<?php

namespace App\Domain\Supports\Google;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CustomImageSearch
{
    protected $baseUrl = 'https://customsearch.googleapis.com/customsearch/v1';

    public function execute(string $query)
    {
        return Cache::tags(['timestamps_for_coordinates'])
            ->rememberForever($this->getCacheKey($query), function () use ($query) {
                $response = Http::get($this->getUrl($query));

                if (! $response->successful()) {
                    return collect();
                }

                return r_collect($response->json())->get('items', collect());
            });
    }

    protected function getCacheKey(string $query)
    {
        return hash('sha256', $query);
    }

    protected function getUrl(string $query): string
    {
        return collect([
            $this->baseUrl,
            $this->getQueryString($query),
        ])->implode('?');
    }

    protected function getQueryString(string $query): string
    {
        return $this->getParameters($query)
            ->map(function (string $value, string $key) {
                return collect([
                    urlencode($key),
                    urlencode($value),
                ])->implode('=');
            })
            ->implode('&');
    }

    protected function getParameters(string $query): Collection
    {
        return collect([
          'key' => config('domain.google_api_key'),
          'searchType' => 'image',
          'safe' => 'active',
          'as_rights' => $this->getAsRights(),
          'q' => $query,
          'imgType' => 'photo',
          'cx' => config('domain.google_custom_search_engine'),
          'gl' => 'US',
        ]);
    }

    protected function getAsRights(): string
    {
        $asRights = collect([
            'cc_publicdomain',
            'cc_attribute',
            'cc_sharealike',
            'cc_noncommercial',
            'cc_nonderived',
        ])->implode('|');

        return "({$asRights})";
    }
}
