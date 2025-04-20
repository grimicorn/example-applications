<?php
/**
 * Created by PhpStorm.
 * User: djacobsmeyer
 * Date: 11/14/17
 * Time: 12:30 PM
 */

namespace App\Support;


abstract class AssetIncludedOptionType
{
    const N_A = 1;
    const INCLUDED = 2;
    const NOT_INCLUDED = 3;

    public static function getLabels()
    {
       return [
           self::N_A => 'N/A',
           self::INCLUDED => 'Included',
           self::NOT_INCLUDED => 'Not Included',
       ];
    }

    public static function getValue()
    {
        return array_keys(self::getLabels());
    }

    public static function getLabel($value)
    {
        $values = self::getLabels();

        return isset($values[ $value ]) ? $values[ $value ] : null;
    }
}