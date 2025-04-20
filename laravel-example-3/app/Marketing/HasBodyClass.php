<?php

namespace App\Marketing;

trait HasBodyClass
{
    protected function getBodyClasses()
    {
        return collect([
            'buy-a-business' => 'page-header-space',
            'how-it-works' => 'page-header-space',
            'privacy-policy' => 'page-header-space',
            'terms-conditions' => 'page-header-space',
            'contact' => 'page-header-space',
            'faq' => 'page-header-space',
            'business-brokers' => 'page-header-space',
            'login' => 'auth-page-wrap',
            'password-reset' => 'auth-page-wrap',
            'register' => config('app.disable_registration_form') ? 'auth-page-wrap page-no-header-space' : 'auth-page-wrap',
        ]);
    }

    protected function getBodyClass($currentPage)
    {
        $class = $this->getBodyClasses()->get($currentPage, '');

        return trim("{$currentPage} {$class}");
    }

}
