<?php

namespace App\Support\User;

use Illuminate\Support\Collection;

class BillingTransactionType
{
    const MONTHLY_SUBSCRIPTION = 1;
    const PER_LISTING = 2;
    const ACCOUNT_CREDIT = 3;
    const ACCOUNT_DEBIT = 4;
    const MONTHLY_SUBSCRIPTION_SMALL = 5;

    /**
     * Gets the enumerated labels.
     *
     * @return array
     */
    public static function getLabels()
    {
        return [
            self::MONTHLY_SUBSCRIPTION => 'Monthly Subscription - Unlimited',
            self::MONTHLY_SUBSCRIPTION_SMALL => 'Monthly Subscription - Small',
            self::PER_LISTING => 'Posted Business',
            self::ACCOUNT_CREDIT => 'Account Credit',
            self::ACCOUNT_DEBIT => 'Account Debit',
        ];
    }

    /**
     * Gets the enumerated plan ids.
     *
     * @return array
     */
    public static function getPlanIds()
    {
        return [
            self::MONTHLY_SUBSCRIPTION => config('services.stripe.monthly_plan_id'),
            self::MONTHLY_SUBSCRIPTION_SMALL => config('services.stripe.monthly_plan_id_small'),
            self::PER_LISTING => config('services.stripe.per_listing_plan_id'),
        ];
    }

    /**
     * Gets the enumerated values.
     *
     * @return array
     */
    public static function getValues()
    {
        return array_keys(self::getLabels());
    }

    /**
     * Get an enumerated label.
     *
     * @return string
     */
    public static function getLabel($value)
    {
        $labels = self::getLabels();

        return isset($labels[ $value ]) ? $labels[ $value ] : null;
    }

    /**
     * Get an enumerated plan id.
     *
     * @return string
     */
    public static function getPlanId($value)
    {
        $ids = self::getPlanIds();

        return isset($ids[ $value ]) ? $ids[ $value ] : null;
    }

    /**
     * Gets the class constants
     *
     * @return Collection
     */
    public static function getConstants(): Collection
    {
        return collect((new \ReflectionClass(__CLASS__))->getConstants());
    }
}
