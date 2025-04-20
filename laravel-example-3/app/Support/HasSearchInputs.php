<?php
namespace App\Support;

trait HasSearchInputs
{
    protected function setSearchInputs($inputs, $key)
    {
        if (is_array($inputs)) {
            $inputs = collect($inputs);
        }

        // Clean up the inputs.
        $inputs = $inputs->except([
            '_token',
        ])->map(function ($value) {
            return is_null($value) ? '' : $value;
        })->filter();

        if ($inputs->isEmpty()) {
            $inputs = collect(session()->get($key, []));
        } elseif (count($inputs) === 1 && $inputs->has('page')) {
            $page = $inputs->get('page');
            $inputs = collect(session()->get($key, []));
            $inputs->put('page', $page);
        } else {
            // Store the current search for later
            session()->put($key, $inputs->except([
                'page',
            ])->toArray());
        }

        // Flash the inputs
        session()->flashInput($inputs->toArray());

        return $inputs;
    }
}
