<?php

namespace App\Domain\Supports;

use App\Enums\Placement;
use Illuminate\Support\Str;

class JobPlacement
{
    public function convertFromImportValue(string $value)
    {
        // Foil needs to be first due to it sometimes being CSTM-FOIL which could
        // match custom, FT -FOIL which would match front and possibly others
        if ($this->isFoil($value)) {
            return Placement::FOIL;
        }

        if ($this->isCustom($value)) {
            return Placement::CUSTOM;
        }

        if ($this->isFront($value)) {
            return Placement::FRONT;
        }

        if ($this->isBack($value)) {
            return Placement::BACK;
        }

        if ($this->isLeftChest($value)) {
            return Placement::LEFT_CHEST;
        }

        if ($this->isRightChest($value)) {
            return Placement::RIGHT_CHEST;
        }

        if ($this->isLeftThigh($value)) {
            return Placement::LEFT_THIGH;
        }

        if ($this->isRightThigh($value)) {
            return Placement::RIGHT_THIGH;
        }

        if ($this->isLeftSleeve($value)) {
            return Placement::LEFT_SLEEVE;
        }

        if ($this->isRightSleeve($value)) {
            return Placement::RIGHT_SLEEVE;
        }


        if ($this->isLeftShortSleeve($value)) {
            return Placement::LEFT_SHORT_SLEEVE;
        }

        if ($this->isRightShortSleeve($value)) {
            return Placement::RIGHT_SHORT_SLEEVE;
        }

        if ($this->isLeftLongSleeve($value)) {
            return Placement::LEFT_LONG_SLEEVE;
        }

        if ($this->isRightLongSleeve($value)) {
            return Placement::RIGHT_LONG_SLEEVE;
        }

        return Placement::UNKNOWN;
    }

    protected function isFront($value)
    {
        $value = $this->standardizeValue($value);
        $value = trim(substr($value, 0, 3));

        return in_array($value, [
            'FT',
            'FT1',
        ]);
    }

    protected function isBack($value)
    {
        $value = $this->standardizeValue($value);
        $value = trim(substr($value, 0, 3));

        return in_array($value, [
            'BK',
            'BK1',
        ]);
    }

    protected function isLeftChest($value)
    {
        $value = $this->standardizeValue($value);
        $value = trim(substr($value, 0, 3));

        return in_array($value, [
            'LC',
        ]);
    }

    protected function isRightChest($value)
    {
        $value = $this->standardizeValue($value);
        $value = trim(substr($value, 0, 3));

        return in_array($value, [
            'RC',
        ]);
    }

    protected function isLeftThigh($value)
    {
        $value = $this->standardizeValue($value);
        $value = trim(substr($value, 0, 3));

        return in_array($value, [
            'LT',
        ]);
    }

    protected function isRightThigh($value)
    {
        $value = $this->standardizeValue($value);
        $value = trim(substr($value, 0, 3));

        return in_array($value, [
            'RT',
        ]);
    }

    protected function isLeftSleeve($value)
    {
        $value = $this->standardizeValue($value);
        $value = trim(substr($value, 0, 3));

        return in_array($value, [
            'LS',
        ]);
    }

    protected function isRightSleeve($value)
    {
        $value = $this->standardizeValue($value);
        $value = trim(substr($value, 0, 3));

        return in_array($value, [
            'RS',
        ]);
    }

    protected function isLeftShortSleeve($value)
    {
        $value = $this->standardizeValue($value);
        $value = trim(substr($value, 0, 3));

        return in_array($value, [
            'LSS',
        ]);
    }

    protected function isRightShortSleeve($value)
    {
        $value = $this->standardizeValue($value);
        $value = trim(substr($value, 0, 3));

        return in_array($value, [
            'RSS',
        ]);
    }

    protected function isLeftLongSleeve($value)
    {
        $value = $this->standardizeValue($value);
        $value = trim(substr($value, 0, 3));

        return in_array($value, [
            'LLS',
        ]);
    }

    protected function isRightLongSleeve($value)
    {
        $value = $this->standardizeValue($value);
        $value = trim(substr($value, 0, 3));

        return in_array($value, [
            'RLS',
        ]);
    }

    protected function isFoil($value)
    {
        $value = $this->standardizeValue($value);

        return Str::contains($value, 'FOIL');
    }

    protected function isCustom($value)
    {
        $value = $this->standardizeValue($value);
        $value = trim(substr($value, 0, 4));
        return in_array($value, [
            'CSTM',
        ]);
    }

    protected function standardizeValue($value)
    {
        $value = trim($value);
        $value = trim($value, '-');
        $value = strtoupper($value);

        return $value;
    }
}
