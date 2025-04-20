<?php

namespace App\Support\ExchangeSpace;

class MemberRole
{
    const SELLER = 1;
    const BUYER = 2;
    const BUYER_ADVISOR = 3;
    const SELLER_ADVISOR = 4;
    const ADMINISTRATOR = 5;

    /**
     * Gets the enumerated labels.
     *
     * @return array
     */
    public static function getLabels()
    {
        return [
            self::SELLER => 'Seller',
            self::BUYER => 'Buyer',
            self::BUYER_ADVISOR => 'Buyer Advisor',
            self::SELLER_ADVISOR => 'Seller Advisor',
            self::ADMINISTRATOR => 'Administrator',
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
        $values = self::getLabels();

        return isset($values[ $value ]) ? $values[ $value ] : null;
    }
}
