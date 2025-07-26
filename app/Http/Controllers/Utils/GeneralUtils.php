<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneralUtils extends Controller
{
    public static function get_compliance_year_option($selected_value, $year_of_incorporation){
        $strOption = '';
        for ($i=date('Y');$i>date('Y')-15;$i--) { 
            $selected = '';
            if($selected_value == $i){
                $selected = 'selected';
            }
            $strOption .= '<option value="'.$i.'" '.$selected.' >'.$i.'</option>';
            if($i == $year_of_incorporation){
                break;
            }
        }
        return $strOption;

    }
    public static function get_base_yr_option($selected_value,$year_of_incorporation){
        $strOption = '';
        for ($i=date('Y');$i>date('Y')-10;$i--) { 
            if($year_of_incorporation >= $i ){
                    break;
            }
            $selected = '';
            if($selected_value == $i){
                $selected = 'selected';
            }
            $strOption .= '<option value="'.$i.'" '.$selected.' >'.$i.'</option>';
        }
        return $strOption;

    }
    
    public static function get_percentage_option($selected_value){
        $strOption = '';
        for ($i=0; $i <= 100 ; $i++) { 
            $selected = '';
            if($selected_value == $i){
                $selected = 'selected';
            }
            $strOption .= '<option value="'.$i.'" '.$selected.' >'.$i.'</option>';
        }
        return $strOption;

    }

    public static function get_class_option($selected_value){
        $strOption = '';
        for ($i=1; $i <= 45 ; $i++) { 
            $selected = '';
            if($selected_value == $i){
                $selected = 'selected';
            }
            $strOption .= '<option value="'.$i.'" '.$selected.' >'.$i.'</option>';
        }
        return $strOption;

    }
    public static function get_year_of_incor_option($selected_value){
        $curentYr = date('Y');
        $strOption = '';
        for ($i=$curentYr; $i >= 1900 ; $i--) { 
            $selected = '';
            if($selected_value == $i){
                $selected = 'selected';
            }
            $strOption .= '<option value="'.$i.'" '.$selected.' >'.$i.'</option>';
        }
        return $strOption;

    }
    public static function get_type_of_entity($selected_value){
        $optionArr = config('selectoptions.type_of_entity');
        $strOption = '';
        foreach($optionArr as $eachVal){
            $selected = '';
            if($selected_value == $eachVal['NAME']){
                $selected = 'selected';
            }
            $strOption .= '<option value="'.$eachVal['NAME'].'" '.$selected.' data-suffix="'.$eachVal['SUFFIX'].'" >'.$eachVal['NAME'].'</option>';
        }
        
        return $strOption;
    }


    public static function get_select_option($configkey,$selected_value,$haveGst= '',$authorised_capital_unit_option=''){
        $optionArr = config('selectoptions.'.$configkey);

        if($haveGst!=''){
            if($haveGst =='Yes'){
                unset($optionArr[2]);
            }
            if($haveGst =='No'){
                unset($optionArr[0]);
                unset($optionArr[1]);
            }
        }
        if($authorised_capital_unit_option != ''){
           $getIndex= array_search($authorised_capital_unit_option,$optionArr);
           foreach($optionArr as $key=> $eachVal){
            // if($getIndex !=$key ){
            //     unset($optionArr[$key]);
            // }
           }
        $selected_value = $authorised_capital_unit_option;

           

        }
        $strOption = '';
        foreach($optionArr as $eachVal){
            $selected = '';
            if($selected_value == $eachVal){
                $selected = 'selected';
            }
            $strOption .= '<option value="'.$eachVal.'" '.$selected.' >'.$eachVal.'</option>';
        }
        
        return $strOption;
    }
    public static function calculate_actual_ask_price($ask_price, $ask_price_unit)
    {
        $amount = "";
        if($ask_price > 0 && !empty($ask_price_unit))
        {
            switch ($ask_price_unit) {
                case 'Rupees':
                    $amount = $ask_price;
                    break;
                case 'Thousand':
                    $amount = $ask_price*1000;
                    break;
                case 'Lac':
                    $amount = $ask_price*100000;
                    break;
                case 'Crore':
                    $amount = $ask_price*10000000;
                    break;
              default:
              $amount = $ask_price;
            } 
        }else{
            $amount = null;
        }
        
        return $amount;
    }

}
