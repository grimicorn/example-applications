<?php

namespace App\Application;

use Laravel\Spark\Spark;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

trait HasSidebarMenuItems
{
    protected function getSidebarMenuItems()
    {
        $items = [
            'dashboard' => [
                'routeName' => 'dashboard',
                'href' => route('dashboard'),
                'label' => 'Dashboard',
                'icon_left' => 'dashboard-icon',
            ],

            'business-inquires' => [
                'routeName' => 'business-inquiry.index',
                'href' => route('business-inquiry.index'),
                'label' => 'Business Inquiries',
                'icon_left' => 'buyer-inquiry-icon',
                'icon_right' => $this->buyerInquiryIconRight(),
            ],

            'listings' => [
                'routeName' => 'listing.index',
                'href' => route('listing.index'),
                'label' => 'Businesses',
                'icon_left' => 'listing-icon',
                'submenu' => $this->getListingsSubmenu(),
            ],

            'exchange-spaces' => [
                'routeName' => 'exchange-spaces.index',
                'href' => route('exchange-spaces.index'),
                'label' => 'Exchange Spaces',
                'icon_left' => 'exchange-space-icon',
                'submenu' => $this->getExchangeSpaceSubmenu(),
            ],

            'profile' => [
                'routeName' => 'profile.edit',
                'href' => route('profile.edit'),
                'label' => 'Profile',
                'icon_left' => 'profile-icon',
                'submenu' => $this->getProfileSubmenu(),
            ],
        ];

        // Add admin menu.
        if (Auth::check() and Spark::developer(Auth::user()->email)) {
            $items['admin'] = [
                'routeName' => 'admin.user-table',
                'href' => route('admin.user-table'),
                'label' => 'Admin',
                'icon_left' => 'admin-icon',
                'submenu' => $this->getAdminSubmenu(),
            ];

            $items['styleguide'] = [
                'routeName' => 'styleguide.show.general',
                'href' => route('styleguide.show.general'),
                'label' => 'Styleguide',
                'icon_left' => 'styleguide-icon',
                'submenu' => $this->getStyleguideSubmenu(),
            ];
        }

        // Set submenu state.
        $items = array_map(function ($item) {
            if (isset($item['submenu'])) {
                $item['submenu'] = $this->setSidebarMenuActive($item['submenu']);
            }

            return $item;
        }, $items);

        // Set state.
        $items = $this->setSidebarMenuActive($items);
        $items = $this->setSidebarIsOpen($items);

        return $items;
    }

    /**
     * Gets the listings sub-menu items
     *
     * @return array
     */
    protected function getListingsSubmenu()
    {
        $routes = collect([
            'Business Details' => 'listing.details.edit',
            'Historical Financials' => 'listing.historical-financials.edit',
        ]);

        // Since an id will be required we should check if we are on a route that needs the submenu.
        if (!$routes->contains(Route::current()->getName())) {
            return [];
        }

        // Build up the submenu.
        $id = $this->getCurrentId();
        return $routes->map(function ($routeName, $label) use ($id) {
            return [
                'routeName' => $routeName,
                'href' => route($routeName, ['id' => $id]),
                'label' => $label,
            ];
        })->toArray();
    }

    protected function getExchangeSpaceSubmenu()
    {
        $routeName = Route::currentRouteName();

        // We do not need to display the links on the index.
        if ($routeName === 'exchange-spaces.index') {
            return [];
        }

        if ($routeName === 'exchange-spaces.show') {
            $id = $this->getCurrentId('id');
        } else {
            $id = $this->getCurrentId();
        }

        return [
            [
                'routeName' => 'exchange-spaces.show',
                'href' => route('exchange-spaces.show', ['id' => $id]),
                'label' => 'Home',
            ],

            [
                'routeName' => 'exchange-spaces.conversations.index',
                'href' => route('exchange-spaces.conversations.index', ['id' => $id]),
                'label' => 'Diligence Center',
                'isActive' => $this->lowerSubMenuActive([
                        'exchange-spaces.conversations.show',
                ]),
                'submenu' => [
                    [
                        'routeName' => 'exchange-spaces.conversations.show',
                        'href' => '#',//route('exchange-spaces.conversations.show', ['id' => $id]),
                        'label' => 'Diligence Center Conversation',
                    ],
                ],
            ],

            [
                'routeName' => 'exchange-spaces.historical-financials.show',
                'href' => route('exchange-spaces.historical-financials.show', ['id' => $id]),
                'label' => 'Historical Financials',
            ],

            [
                'routeName' => 'exchange-spaces.adjusted-financials-trends.show',
                'href' => route('exchange-spaces.adjusted-financials-trends.show', ['id' => $id]),
                'label' => 'Adjusted Financials & Trends',
            ],
        ];
    }

    /**
     * Gets the styleguide sub-menu items
     *
     * @return array
     */
    protected function getStyleguideSubmenu()
    {
        return [
            [
                'routeName' => 'styleguide.show.general',
                'href' => route('styleguide.show.general'),
                'label' => 'General',
            ],

            [
                'routeName' => 'styleguide.show.inputs',
                'href' => route('styleguide.show.inputs'),
                'label' => 'Inputs',
            ],

            [
                'routeName' => 'styleguide.show.alerts',
                'href' => route('styleguide.show.alerts'),
                'label' => 'Alerts',
            ],

            [
                'routeName' => 'styleguide.show.notifications',
                'href' => route('styleguide.show.notifications'),
                'label' => 'Notifications',
            ],

            [
                'routeName' => 'styleguide.show.notification',
                'href' => route('styleguide.show.notification'),
                'label' => 'Notification',
            ],
        ];
    }

    /**
     * Gets admin sub menu.
     *
     * @return array
     */
    protected function getAdminSubmenu()
    {
        return [
            [
                'routeName' => 'admin.user-table',
                'href' => route('admin.user-table'),
                'label' => 'User Table',
            ],
            [
                'routeName' => 'lcs-custom-penalty.index',
                'href' => route('lcs-custom-penalty.index'),
                'label' => 'LCS Custom Penalty',
                'isActive' => $this->lowerSubMenuActive([
                        'lcs-custom-penalty.edit',
                ]),
                'submenu' => [
                    'routeName' => 'lcs-custom-penalty.edit',
                    'href' => '#',
                    'label' => 'LCS Custom Penalty',
                ],
            ],
            [
                'routeName' => 'admin.admin-settings',
                'href' => route('admin-settings.index'),
                'label' => 'Admin Settings',
            ]
        ];
    }

    /**
     * Gets the profile sub-menu.
     *
     * @return array
     */
    protected function getProfileSubmenu()
    {
        return [
            [
                'routeName' => 'profile.edit',
                'href' => route('profile.edit'),
                'label' => 'Edit',
                'icon_left' => 'fa-pencil-square-o',
            ],

            [
                'routeName' => 'profile.settings.edit',
                'href' => route('profile.settings.edit'),
                'label' => 'Settings',
                'icon_left' => 'fa-cog',
            ],

            [
                'routeName' => 'profile.notifications.edit',
                'href' => route('profile.notifications.edit'),
                'label' => 'Notifications',
                'icon_left' => 'fa-comments-o',
            ],

            [
                'routeName' => 'profile.payments.edit',
                'href' => route('profile.payments.edit'),
                'label' => 'Payments',
                'icon_left' => 'fa-usd',
            ],
        ];
    }

    /**
     * Sets the sidebar open state.
     * IMPORTANT: Depends on the sub-menu items having there active state already set.
     *
     * @param array $items
     */
    protected function setSidebarIsOpen($items)
    {
        return array_map(function ($item) {
            $item['isOpen'] = $this->sidebarMenuIsOpen($item);

            return $item;
        }, $items);
    }

    /**
     * Sets the sidebar menu open.
     *
     * @param  array $item
     *
     * @return boolean
     */
    protected function sidebarMenuIsOpen($item)
    {
        // No need to open non-existent sub-menus.
        if (!isset($item['submenu']) || empty($item['submenu'])) {
            return false;
        }

        $activeItems = array_filter($item['submenu'], function ($item) {
            return isset($item['isActive']) ? (bool) $item['isActive'] : false;
        });

        return !empty($activeItems);
    }

    /**
     * Sets the sidebar menu active state
     *
     * @param array $items
     *
     * @return array
     */
    protected function setSidebarMenuActive($items)
    {
        return array_map(function ($item) {
            if (!isset($item['isActive'])) {
                $item['isActive'] = $this->sidebarMenuIsActive($item);
            }


            return $item;
        }, $items);
    }

    /**
     * Sidebar menu is active.
     *
     * @param  array $item
     *
     * @return boolean
     */
    protected function sidebarMenuIsActive($item)
    {
        return $this->routeNameIsActive($item['routeName']);
    }

    protected function routeNameIsActive($name)
    {
        return $name === Route::getFacadeRoot()->current()->getName();
    }

    protected function lowerSubMenuActive($routeNames)
    {
        $routeNames = is_array($routeNames) ? collect($routeNames) : $routeNames;

        // If no route names are active then we want to return null so it
        // will allow the parent to check if it is active. If not parent menu
        // items will always be false.
        return $routeNames->filter(function ($name) {
            return $this->routeNameIsActive($name);
        })->isEmpty() ? null : true;
    }

    /**
     * Gets the id from the current route.
     *
     * @return int|null
     */
    protected function getCurrentId($key = 'id')
    {
        $parameters = Route::current()->parameters();
        return isset($parameters[ $key ]) ? $parameters[ $key ] : null;
    }

    protected function buyerInquiryIconRight()
    {
        $hasNotifications = optional(auth()->user())->has_buyer_inquiry_notifications;
        return $hasNotifications ? 'notification-icon' : '';
    }
}
