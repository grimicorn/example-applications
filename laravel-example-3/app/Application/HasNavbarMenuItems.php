<?php

namespace App\Application;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

trait HasNavbarMenuItems
{
    protected function getNavbarMenuItems()
    {
        $items = [
            [
                'label' => 'Logout',
                'href' => route('logout'),
                'routeName' => 'logout',
                'iconClass' => 'fa fa-sign-out'
            ],
        ];

        // Set state.
        $items = $this->setNavbarMenuActive($items);

        return $items;
    }

    /**
     * Sets the sidebar menu active state
     *
     * @param array $items
     *
     * @return array
     */
    protected function setNavbarMenuActive($items)
    {
        return array_map(function ($item) {
            $item['isActive'] = $this->sidebarMenuIsActive($item);

            return $item;
        }, $items);
    }

    /**
     * Navbar menu is active.
     *
     * @param  array $item
     *
     * @return boolean
     */
    protected function navbarMenuIsActive($item)
    {
        return $item['routeName'] === Route::getFacadeRoot()->current()->getName();
    }
}
