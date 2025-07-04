<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoEmailNoMobile implements ValidationRule
{
    

    
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $emailPattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';
        if (preg_match($emailPattern, $value, $matches)) {
            $fail($attribute." should not cantain email address.");
        }
        $mobilePattern = '/\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}/';
        if (preg_match_all($mobilePattern, $value, $matches)) {
            $fail($attribute." should not cantain mobile number.");
        }
    /*
        $count = preg_match_all('/\d/', $value);
        if ($count > 0) {
            $fail($attribute." should not cantain digit.");
        }
    */  
    }
}
