<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsPlacesArray implements Rule
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
     * @param  mixed  $places
     * @return bool
     */
    public function passes($attribute, $places)
    {
        if (!$places
            || !is_array($places)
            || count($places) > 6
            || count($places) <= 0
            || count($places) != count(array_unique($places))) {
            return false;
        }

        foreach ($places as $key=>$place) {
            if (!is_int($place) || $place < 0) {
                return false;
            }
        }

        return  true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Not valid data';
    }
}
