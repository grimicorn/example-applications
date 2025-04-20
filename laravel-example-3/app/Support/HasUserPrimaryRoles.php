<?php

namespace App\Support;

trait HasUserPrimaryRoles
{
    public function getUserPrimaryRoles()
    {
        return [
            'Broker',
            'Buyer',
            'Seller',
            'Other',
        ];
    }
}
