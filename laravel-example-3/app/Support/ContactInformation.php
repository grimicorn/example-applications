<?php

namespace App\Support;

class ContactInformation
{
    /**
     * Data specific for the Laravel SparkServiceProvider
     *
     * @return array
     */
    public static function getSparkData()
    {
        $address = Self::getAddress();
        $street = $address->only(['line_1', 'line_2'])->filter()->implode(' ');
        $city = $address->get('city', '');
        $location = "{$city}, " . $address->only(['state', 'zipcode'])->filter()->implode(' ');
        $phone = Self::getPhoneNumber();
        $vendor = Self::getName();

        return compact('vendor', 'street', 'location', 'phone');
    }

    /**
     * Get the main address.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAddress()
    {
        return collect([
           'line_1' => '1800 Camden Road',
           'line_2' => 'Suite 107-141',
           'city' => 'Charlotte',
           'zipcode' => '28203',
           'state' => 'NC',
        ]);
    }

    /**
     * Get the main phone number.
     *
     * @return string
     */
    public static function getPhoneNumber()
    {
        return '';
    }

    /**
     * Gets the business name.
     *
     * @return string
     */
    public static function getName()
    {
        return 'The Firm Exchange LLC';
    }

    /**
     * Gets
     *
     * @param  string $address
     *
     * @return string
     */
    public static function getEmail($address = 'info')
    {
        return "{$address}@firmexchange.com";
    }
}
