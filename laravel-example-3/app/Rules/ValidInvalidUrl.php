<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidInvalidUrl implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Don't stop empty fields
        if (is_null($value) or $value === '') {
            return true;
        }

        // Validate strings that should be "valid" URLs.
        if (str_contains($value, ['mailto:', '//'])) {
            return filter_var($value, FILTER_VALIDATE_URL) !== false;
        }

        // // Validate telephone links
        if (str_contains($value, ['tel:'])) {
            preg_match('/[0-9-]+/u', $value, $matches);
            return !empty($matches);
        }

        // If it is a invalid valid URL then lets try to regex it
        $match = preg_match('/^(?:(ftp|http|https)?:\\/\\/)?(?:[\\w-]+\\.)+([a-z]|[A-Z]|[0-9]){2,6}$/um', $value, $matches);
        return !empty($matches);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The field is not a valid URL';
    }
}
