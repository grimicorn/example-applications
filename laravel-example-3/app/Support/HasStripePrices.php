<?php

namespace App\Support;

trait HasStripePrices
{
    protected function convertStripePrice($price)
    {
        $price = preg_replace('/[^0-9]/', '', $price);

        return floatval(abs($price / 100));
    }

    protected function convertPriceForStripe($price)
    {
        return floatval(abs($price * 100));
    }
}
