<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GstFormat implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $have_gst = request()->input('have_gst');
        if($have_gst == 'Yes'){
       //     $gst_regex = "^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$"; 
            if(empty($value)){
                $fail("Please enter GST no.");

            }else{
                $gst_regex ="/^([0-3][0-9])[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}[Z]{1}[0-9A-Z]{1}$/";
                if (!preg_match($gst_regex ,$value)) {
                    $fail("GST Format is invalid");
                }

            }
            

        }
        
    }
}
