<?php

namespace Tests\Support;

trait HasForms
{
/**
     * [setCheckboxesOn description]
     *
     * @param Illuminate\Support\Collection $data
     * @param arrat $keys
     */
    protected function setCheckboxesOn($data, $keys)
    {
        $data = collect($data->toArray());

        foreach ($keys as $key) {
            $value = $data->get($key, false);
            $on = $this->setCheckboxOn($value);
            $data->put($key, $on);
        }

        return $data;
    }

    /**
     * Sets the a checkbox value to on/off
     *
     * @param boolean $isOn
     *
     * @return boolean | null
     */
    protected function setCheckboxOn($isOn)
    {
        return (bool) $isOn ? 'on' : null;
    }

    /**
     * Converts a set of options to behave like checkboxes.
     * Flips the array to use the values as keys then adds 'on'
     * as the value like a checkbox would.
     *
     * @param  array $data
     *
     * @return array
     */
    protected function covertToCheckboxOptions($data)
    {
        return collect($data)->flip()->map(function ($item) {
            return 'on';
        })->toArray();
    }

    /**
     * Converts tags to a string
     *
     * @param  array $fields
     * @param  array $keys
     *
     * @return array
     */
    protected function convertTagsToString($fields, $keys)
    {
        return collect($fields)->map(function ($value, $key) use ($keys) {
            if (in_array($key, $keys)) {
                $value = is_array($value) ? implode(',', $value) : $value;
            }

            return $value;
        })->toArray();
    }
}
