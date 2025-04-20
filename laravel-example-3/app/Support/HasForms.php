<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

trait HasForms
{
    /**
     * Flips check box options.
     * This will allow the actual value to be saved instead of on.
     *
     * @param  array $options
     *
     * @return array
     */
    protected function flipCheckboxOptions($options)
    {
        return collect($options)->map(function ($item, $key) {
            return $key;
        })->values()->toArray();
    }

    /**
     * Filters out empty array fields.
     *
     * @param  array $fields
     *
     * @return array
     */
    protected function filterSubmittedArray($key, $fields)
    {
        $array = isset($fields[ $key ]) ? $fields[ $key ] : [];
        if (!empty($array)) {
            $fields[ $key ] = collect($array)->filter(function ($item) {
                return !collect($item)->filter()->isEmpty();
            })
            ->toArray();
        }

        return $fields;
    }

    /**
     * Converts from on to true
     *
     * @param  array $fields
     * @param array $keys
     *
     * @return array
     */
    protected function convertOnToBoolean($fields, $keys)
    {
        $fields = collect($fields);
        foreach ($keys as $key) {
            $field = 'on' === strtolower($fields->get($key, 'off'));
            $fields->put($key, $field);
        }

        return $fields->toArray();
    }

    /**
     * Converts tags to arrays
     *
     * @param  array $fields
     * @param  array $keys
     *
     * @return array
     */
    protected function convertTagsToArray($fields, $keys)
    {
        return collect($fields)->map(function ($value, $key) use ($keys) {
            if (in_array($key, $keys)) {
                $value = is_string($value) ? explode(',', $value) : $value;
                $value = is_array($value) ? $value : [];
                $value = array_filter(array_map('trim', $value));
                $value = array_values($value);
            }

            return $value;
        })->toArray();
    }
}
