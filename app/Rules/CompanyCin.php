<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CompanyCin implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $fstchar = substr($value,0,1);
        $str_l = strlen($value);
        $type_of_entity = request()->input('type_of_entity');
        $listed = config('selectoptions.type_of_entity_listed');
        $unlisted = config('selectoptions.type_of_entity_unlisted');
        $foriegn = config('selectoptions.type_of_entity_foriegn');
        $llp = config('selectoptions.type_of_entity_llp');
        if(in_array($type_of_entity,$listed) && $fstchar != 'L'){
            $fail(" CIN/LLPIN should start with L for listed company");
        }elseif(in_array($type_of_entity,$unlisted) && $fstchar != 'U'){
            $fail(" CIN/LLPIN should start with U for unlisted company");
        }
        if(in_array($type_of_entity,$llp)){
            if($str_l != 8){
                $fail(" CIN/LLPIN of LLP company should 8 charecter.");
            }
            
        }elseif(in_array($type_of_entity,$foriegn)){
            if($str_l != 6){
                $fail("CIN/LLPIN of foriegn company should 6 charecter.");
            }
            
        }elseif($str_l != 21){
            $fail("CIN/LLPIN company should 21 charecter.");
        }

        
        
    }
}
