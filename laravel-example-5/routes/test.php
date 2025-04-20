<?php
if (!app()->environment('production')) {
    (new \Tests\Support\SitemapPageUrls)
        ->get()->each(function ($url) {
            $url = str_replace(url('/'), '', $url);
            Route::view($url, 'test.page', ['url' => $url]);
        });
}
