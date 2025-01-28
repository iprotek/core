<?php
namespace iProtek\Core\Enums;

use DB;  

class _CommonEnum
{ 

    public static function getValue($const){
        if(!$const){
            return "";
        }

        $const = trim(strtoupper($const)); 

        try{
            return constant( static::class . "::$const");
        }catch(\Exception $ex){
        }catch(\Error $er){

        }
        return "";
    }

}