<?php

namespace Laravel\Spark;

use Illuminate\Support\Str;
use Illuminate\Notifications\RoutesNotifications;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Billable, HasApiTokens, RoutesNotifications;

    /**
     * Make the team user visible for the current user.
     *
     * @return $this
     */
    public function shouldHaveSelfVisibility()
    {
        return $this->makeVisible([
            'uses_two_factor_auth',
            'country_code',
            'phone',
            'card_brand',
            'card_last_four',
            'card_country',
            'billing_address',
            'billing_address_line_2',
            'billing_city',
            'billing_state',
            'billing_zip',
            'billing_country',
            'extra_billing_information'
        ]);
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        if (! in_array('tax_rate', $this->hidden)) {
            $array['tax_rate'] = $this->taxPercentage();
        }

        return $array;
    }
}
