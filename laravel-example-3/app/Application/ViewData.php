<?php

namespace App\Application;

use App\Support\HasStates;
use App\Support\HasSelects;
use Illuminate\Support\Facades\Auth;
use App\Support\HasUserPrimaryRoles;
use App\Application\HasNavbarMenuItems;
use App\Application\HasSidebarMenuItems;

class ViewData
{
    use HasSidebarMenuItems, HasStates, HasSelects, HasUserPrimaryRoles, HasNavbarMenuItems;

    public function get()
    {
        return collect([
           'sidebarMenuItems' => $this->getSidebarMenuItems(),
           'navbarMenuItems' => $this->getNavbarMenuItems(),
           'states' => $states = $this->getStates(),
            'primaryRolesForSelect' => $this->convertForSelect(
                $this->getUserPrimaryRoles(),
                $setValues = true
            ),
        ]);
    }
}
