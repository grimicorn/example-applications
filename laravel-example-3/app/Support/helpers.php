<?php

if (!function_exists('r_collect')) {
    /**
     * Recursively collects a multi-dimensional array.
     *
     * @param  array $array
     *
     * @return Illuminate\Support\Collection
     */
    function r_collect($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = r_collect($value);
                $array[ $key ] = $value;
            }
        }

        return collect($array);
    }
}


function price_range_format($low, $high)
{
    if (!$low and !$high) {
        return '';
    }

    if (!$high) {
        $low = number_format(floatval($low));

        return "\${$low} - No Maximum";
    } else {
        $low = number_format(floatval($low));
        $high = number_format(floatval($high));

        return "\${$low} - \${$high}";
    }
}

function comma_separated($array)
{
    $newList = null;
    foreach ($array as $item) {
        $newList = $newList.$item.", ";
    }
    return chop($newList, ", ");
}

function deal_stage_is_lt($deal, $space)
{
    return intval($deal) < intval($space->deal);
}

function deal_stage_is_lte($deal, $space)
{
    return intval($deal) <= intval($space->deal);
}

/**
 * Gets the progress pill classes.
 *
 * @param  int $index
 * @param  App\ExchangeSpace $space
 *
 * @return string
 */
function progess_pill_classes($index, $space)
{
    $lte = deal_stage_is_lte($index, $space);

    return collect([
        $lte ? 'bg-color5' : '',
        'progress-pill',
        'inline-block',
        'ba1-color5',
        ($index === 1) ? 'radius-left-10' : '',
        ($index === 4) ? 'radius-right-10' : '',
    ])->filter()->implode(' ');
}

function price($price, $hideSymbol = false, $decimals = 0)
{
    if (!isset($price)) {
        return '-';
    }

    $output = $hideSymbol ? money_format('%!(#10.0n', floatval($price)) : '$'.money_format('%!(#10.0n', floatval($price));

    return $output;
}

function stringTrim($string, $last_char, $add_elipsis = false)
{
    $new_string = substr($string, 0, $last_char);
    return $add_elipsis ? $new_string."..." : $new_string;
}

function listing_na_included_price($included, $estimated)
{
    if (!isset($included) && !isset($estimated)) {
        return '-';
    }

    $included = intval($included);
    $na = \App\Support\AssetIncludedOptionType::N_A;
    $notIncluded = \App\Support\AssetIncludedOptionType::NOT_INCLUDED;

    if ($included === $na) {
        return 'N/A';
    }

    $price = '$' . number_format(floatval($estimated));
    if ($included === $notIncluded) {
        $price = "{$price}*";
    }

    return $price;
}

function listing_na_included_description($isIncluded, $description)
{
    $na = \App\Support\AssetIncludedOptionType::N_A;
    $included = \App\Support\AssetIncludedOptionType::INCLUDED;
    $notIncluded = \App\Support\AssetIncludedOptionType::NOT_INCLUDED;

    if ($isIncluded == $included || $isIncluded == $notIncluded) {
        return $description;
    } elseif ($isIncluded == $na) {
        return 'N/A';
    } elseif ($isIncluded == null) {
        return '';
    }
}

/**
 * Formats null/true/false value
 *
 * @param boolean|null $prop
 * @return string
 */
function nullBooleanFormat($prop)
{
    if (!isset($prop)) {
        return '';
    }

    return $prop ? 'Yes' : 'No';
}

/**
 * Adds a URL protocol if one does not already exist.
 *
 * @param string $url
 * @param string $protocol
 * @return string
 */
function add_url_protocol($url, $protocol = 'http')
{
    if (str_contains($url, ['mailto:', 'tel:'])) {
        return $url;
    }

    $scheme = parse_url($url, PHP_URL_SCHEME);
    if (empty($scheme)) {
        $url = 'http://' . trim(ltrim($url, '/'));
    }

    return $url;
}

/**
 * Executes print_r and dies only when the request expects JSON
 *
 * @param mixed ...$values
 * @return void
 */
function print_r_json(...$values)
{
    if (request()->expectsJson()) {
        foreach ($values as $value) {
            print_r($value);
        }
        die();
    }
}
