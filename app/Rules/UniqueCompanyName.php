<?php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Company;

class UniqueCompanyName implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $currentId = request()->input('id');
        $name = $value;
        $exists = null;
        if($currentId > 0){
            $exists = Company::whereRaw('LOWER(name) = ?', [strtolower($name)])->where('id', '!=', $currentId)->exists();
        }else{
            
            $exists = Company::whereRaw('LOWER(name) = ?', [strtolower($name)])->exists();
        }
       /// var_dump($exists);
        
        if ($exists) {
            $fail("Name not available. Choose another.");  
        }
    }
}
