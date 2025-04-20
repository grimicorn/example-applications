<?php

namespace App\Marketing;

trait HasFooterData
{
    /**
     * Gets the footer quick links.
     *
     * @return array
     */
    protected function getFooterQuickLinks()
    {
        return [
            [
                'href' => url('/terms-conditions'),
                'label' => 'Terms & Conditions',
                'view' => 'terms-conditions',
            ],
            [
                'href' => url('/privacy-policy'),
                'label' => 'Privacy Policy',
                'view' => 'privacy-policy',
            ],
            [
                'href' => url('/contact'),
                'label' => 'Contact Us',
                'view' => 'contact',
            ],
        ];
    }

    /**
     * Gets the footer CTA bar data.
     *
     * @param  string $currentPage
     *
     * @return array
     */
    protected function getFooterCTABar($currentPage)
    {
        // Disable register page.
        if ($currentPage === 'register') {
            return [];
        }

        // Alter specific pages
        $bar = collect(collect([])
        ->get($currentPage, []))->toArray();

        // Sets default values.
        return array_merge([
            'content' => 'Join today to see how Firm Exchange can help make your deal a success.',
            'btnLabel' => 'Get Started',
            'btnLink' => route('register'),
        ], $bar);
    }
}
