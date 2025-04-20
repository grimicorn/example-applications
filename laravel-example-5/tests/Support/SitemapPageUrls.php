<?php

namespace Tests\Support;

class SitemapPageUrls
{
    public function get()
    {
        return $this->sitemap1()->concat(
            $this->sitemap2()
        )->sort();
    }

    public function sitemap1()
    {
        return collect([
            url('test'),
            url('test/page-1'),
            url('test/page-2'),
            url('test/page-3'),
            url('test/page-4'),
        ])->map(function ($url) {
            return "{$url}/";
        })->sort();
    }

    public function sitemap2()
    {
        return collect([
            url('test/posts'),
            url('test/posts/1'),
            url('test/posts/2'),
            url('test/posts/3'),
            url('test/posts/4'),
        ])->map(function ($url) {
            return "{$url}/";
        })->sort();
    }
}
