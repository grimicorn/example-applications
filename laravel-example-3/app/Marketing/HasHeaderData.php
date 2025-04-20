<?php

namespace App\Marketing;

use Illuminate\Support\Facades\Auth;

trait HasHeaderData
{
    /**
     * Gets the header navigation items.
     *
     * @return array
     */
    protected function getHeaderNavigation($currentPage)
    {
        return collect([
            [
                'href' => url('/how-it-works'),
                'label' => 'How it Works',
                'view' => '',
            ],
            [
                'href' => url('/business-brokers'),
                'label' => 'Who\'s it For',
                'view' => 'business-brokers',
            ],
            [
              'href' => url('/pricing'),
              'label' => 'Pricing',
              'view' => 'pricing',
            ],
            [
                'href' => $this->getBlogURL(),
                'label' => 'Blog',
                'view' => 'blog',
                'link_class' => 'js-speed-bump-ignore',
            ],
        ])
        ->map(function ($item) use ($currentPage) {
            $item['class'] = collect([
               $view = isset($item['view']) ? $item['view'] : '',
               $this->headerNavigationActiveClass($currentPage, $view),
            ])->filter()->implode(' ');

            return $item;
        })->toArray();
    }

    /**
     * Outputs the marketing active class
     *
     * @param  string $currentPage
     * @param  string $view
     */
    protected function headerNavigationActiveClass($currentPage, $view)
    {
        if ($view && (strpos($currentPage, $view) !== false)) {
            return 'active';
        }

        return '';
    }

    /**
     * Gets the blog URL.
     *
     * @return string
     */
    protected function getBlogURL()
    {
        return env('BLOG_URL', 'https://mdgfeb.staging.wpengine.com');
    }
}
