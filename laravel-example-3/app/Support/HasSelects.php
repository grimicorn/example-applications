<?php

namespace App\Support;

trait HasSelects
{
    /**
     * Sets the values for a select.
     *
     * @param array $options
     *
     * @return array
     */
    protected function setValuesForSelect($options)
    {
        return collect($options)->keyBy(function ($item) {
            return $item;
        });
    }

    /**
     * Converts options for select.
     * Array key = value and Array value = label.
     *
     * @param  array $options
     *
     * @return array
     */
    protected function convertForSelect($options, $setValues = false)
    {
        if ($setValues) {
            $options = $this->setValuesForSelect($options);
        }

        return collect($options)->map(function ($label, $value) {
            return compact('label', 'value');
        })->values();
    }
}
