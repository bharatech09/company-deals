<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxDigits implements ValidationRule
{
    protected $num_of_digit;
    /**
     * Create a new rule instance.
     *
     * @param int $num_of_digit
     */

    public function __construct($num_of_digit)
    {
        $this->num_of_digit = $num_of_digit;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $count = preg_match_all('/\d/', $value);
        if ($count > $this->num_of_digit) {
            $fail($attribute." should not cantain digit more then ".$this->num_of_digit);
        }
        
    }
}
