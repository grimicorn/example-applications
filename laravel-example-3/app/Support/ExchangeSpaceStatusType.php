<?php

namespace App\Support;

class ExchangeSpaceStatusType
{
    const INQUIRY = 1;
    const ACCEPTED = 2;
    const PENDING = 3;
    const REJECTED = 4;
    const COMPLETED = 5;
    const DELETED = 6;

    public static function getLabels()
    {
        return [
            self::INQUIRY => 'Business Inquiry',
            self::ACCEPTED => 'Exchange Space',
            self::PENDING => 'Pending',
            self::REJECTED => 'Rejected',
            self::COMPLETED => 'Completed',
            self::DELETED => 'Deleted',
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
