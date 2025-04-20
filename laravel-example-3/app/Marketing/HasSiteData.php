<?php

namespace App\Marketing;

use App\Support\HasStates;
use App\Support\HasSelects;
use App\Marketing\HasHomeData;
use App\Marketing\HasFooterData;
use App\Marketing\HasHeaderData;
use App\Support\ContactInformation;

trait HasSiteData
{
    use HasHeaderData;
    use HasFooterData;
    use HasBodyClass;
    use HasHomeData;
    use HasStates;
    use HasSelects;

    /**
     * Gets the current page title.
     *
     * @param  string $currentPage
     *
     * @return string
     */
    protected function getPageTitle($currentPage)
    {
        $default = ucwords(str_replace('-', ' ', $currentPage));
        return collect([
            'business-brokers' => 'Firm Exchange is Perfect for Business Brokers',
            'buy-a-business' => 'Buy a Business on Firm Exchange',
            'contact' => 'Contact',
            'faq' => 'FAQ',
            'how-it-works' => 'How Firm Exchange Helps You Buy or Sell a Business',
            'privacy-policy' => 'Privacy Policy : Your privacy Rights',
            'sell-my-business' => 'Sell My Business on Firm Exchange',
            'terms-conditions' => 'The Firm Exchange Terms of Use',
            'tools-resources' => 'Tools & Resources: User Dashboard, Listing Completion Rating, and More',
            'residency' => 'Country of Residence',
        ])->get($currentPage, $default);
    }

    /**
     * Gets if the page header is disabled.
     *
     * @param  string $currentPage
     *
     * @return boolean
     */
    protected function getPageHeaderDisabled($currentPage)
    {
        return (bool) collect([
            'home' => true,
            'login' => true,
            'password-reset' => true,
            'register' => true,
            'login-token' => true,
            'login-emergency-token' => true,
        ])->get($currentPage, false);
    }

    /**
     * Gets the social menu.
     *
     * @return array
     */
    protected function getSocialMenu()
    {
        return [
            [
                'href' => 'https://twitter.com/firmexchange',
                'label' => 'Twitter',
                'icon_class' => 'fa-twitter',
                'class' => 'social-menu-twitter',
            ],

            [
                'href' => 'https://www.facebook.com/firmexchange',
                'label' => 'Facebook',
                'icon_class' => 'fa-facebook',
                'class' => 'social-menu-facebook',
            ],

            [
                'href' => 'https://www.linkedin.com/company/firmexchange',
                'label' => 'LinkedIn',
                'icon_class' => 'fa-linkedin',
                'class' => 'social-menu-linkedin',
            ],

            // [
            //     'href' => '#google-plus',
            //     'label' => 'Google+',
            //     'icon_class' => 'fa-google-plus',
            //     'class' => 'social-menu-google-plus',
            // ],
        ];
    }

    /**
     * Gets the site data.
     *
     * @param  string $view
     * @param  string $title
     * @param  array  $data
     *
     * @return array
     */
    protected function getSiteData($view, $title = null, $data = [])
    {
        $currentPage = str_replace('marketing.', '', $view);

        return collect([
            'currentPage' => $currentPage,
            'pageTitle' => is_null($title) ? $this->getPageTitle($currentPage) : $title,
            'headerNavigation' => $this->getHeaderNavigation($currentPage),
            'footerCTABar' => $this->getFooterCTABar($currentPage),
            'footerQuickLinks' => $this->getFooterQuickLinks(),
            'socialMenu' => $this->getSocialMenu(),
            'bodyClass' => $this->getBodyClass($currentPage),
            'address' => ContactInformation::getAddress()->toArray(),
            'phone' => ContactInformation::getPhoneNumber(),
            'email' => [
                'info' => ContactInformation::getEmail('info'),
                'support' => ContactInformation::getEmail('support'),
            ],
            'pageHeaderDisabled' => $this->getPageHeaderDisabled($currentPage),
        ])->merge($data)->toArray();
    }
}
