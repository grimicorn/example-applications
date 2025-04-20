<?php

namespace Tests;

use App\User;
use Tests\Support\SitemapPageUrls;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    public function setUp()
    {
        parent::setUp();
    }

    protected function signIn(?User $user = null)
    {
        auth()->login($user = $user ?? create(User::class));

        return $user;
    }

    protected function signInAdmin()
    {
        return $this->signIn(
            factory(User::class)->states('admin')->create()
        );
    }

    protected function sitemapPageUrls()
    {
        return (new SitemapPageUrls)->get();
    }

    protected function sitemap1PageUrls()
    {
        return (new SitemapPageUrls)->sitemap1();
    }

    protected function sitemap2PageUrls()
    {
        return (new SitemapPageUrls)->sitemap2();
    }

    protected function copySitemapStubs()
    {
        $this->clearSitemapStubs();

        // Copy the directory
        File::copyDirectory(
            base_path('tests/stubs/sitemaps'),
            $public = public_path('stubs/sitemaps')
        );


        collect(File::files($public))->each(function ($file) {
            // Set the app url
            $contents = str_replace('http://www.example.com', url('/'), $file->getContents());
            $file->openFile('w')->fwrite($contents);

            // Create a gzip version
            file_put_contents(
                $file->getRealPath(). '.gz',
                gzencode($contents)
            );
        });
    }

    protected function clearSitemapStubs()
    {
        File::deleteDirectory(public_path('stubs/sitemaps'));
        File::deleteDirectory(public_path('stubs'));
    }
}
