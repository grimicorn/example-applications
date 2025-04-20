<?php

namespace App\Support;

abstract class ExchangeSpaceDealType
{
    const PRE_NDA = 1;
    const SIGNED_NDA = 2;
    const LOI_SIGNED = 3;
    const COMPLETE = 4;

    public static function getLabels()
    {
        return [
            self::PRE_NDA => 'Getting Started',
            self::SIGNED_NDA => 'Due Diligence',
            self::LOI_SIGNED => 'Offer Made',
            self::COMPLETE => 'Under Contract'
        ];
    }

    public static function getValues()
    {
        return array_keys(self::getLabels());
    }

    public static function getLabel($value)
    {
        $values = self::getLabels();

        return isset($values[ $value ]) ? $values[ $value ] : null;
    }
}
