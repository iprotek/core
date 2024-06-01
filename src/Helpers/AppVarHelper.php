<?php

namespace iProtek\Core\Helpers;

//use Illuminate\Http\Request;
use DB;
use iProtek\Core\Models\AppVariable;


class AppVarHelper
{

    public static function get($var_name, $default = null)
    {   
        if(is_array($var_name)){
            $var_name =  array_map('trim', array_values($var_name) );
            if(count($var_name) <= 0){
                return null;
            }
            $appvars = AppVariable::whereIn('name', $var_name)->get();
            $result = [];
            foreach($var_name as $var){
                if(!$var){
                    continue;
                }
                $var = trim( strtolower($var) );
                $result[$var] = null;
                foreach($appvars as $appvar){
                    $var_name = trim( strtolower($appvar->name) );
                    if(!$var_name)
                        continue;
                    if( $var == $var_name){
                        $result[$var] = $appvar->value;
                        break;
                    }
                }
            }
            return $result;
        }
        $var_name = trim( strtolower( $var_name) );
        $appvar = AppVariable::where('name', $var_name)->first();
        if(!$appvar){
            return $default;
        }
        else if(!$appvar->value){
            return $default;
        }
        return $appvar->value;
    } 

    public static function set($var_name, $new_value = null)
    {
        if(!auth()->check()){
            return;
        }
        if(!$var_name){
            return;
        }

        
        if(is_array($var_name)){
            if(count($var_name) <= 0){
                return null;
            }
            $var_names = array_keys($var_name) ;
            $temp_names = array_map('trim', array_keys($var_name) );
            $appvars = AppVariable::whereIn('name', $temp_names)->get();
            foreach($var_names as $var){
                if(!$var){
                    continue;
                }
                $exists = false;
                $orignalVarName = $var;
                $var = trim(strtolower($var));
                foreach($appvars as $appvar){
                    $varname = trim(strtolower($appvar->name));
                    if(!$varname){
                        continue;
                    }
                    if($varname == $var){
                        $exists = true;
                        //DELETE IF VALUES IS NOT SAME AND CREATE NEW
                        $appvar->value = $var_name[$orignalVarName];
                        if($appvar->isDirty()){
                            //$appvar->delete();                            
                            AppVariable::where('name', $var)->delete();
                            $appvar = AppVariable::create([
                                "name"=>$var,
                                "value"=>$var_name[$orignalVarName],
                                "updated_by"=>auth()->user()->id
                            ]);
                        }
                        break;
                    }
                }
                //Add new Variable
                if(!$exists){                                           
                    $appvar = AppVariable::create([
                        "name"=>$var,
                        "value"=>$var_name[$orignalVarName],
                        "updated_by"=>auth()->user()->id
                    ]);
                }
            }
            return null;
        }

        $var_name = trim( strtolower( $var_name) );
        //GET THE VARIABLE
        $appvar = AppVariable::where('name', $var_name)->first();
        //IF EXISTS COMPARE VALUES
        if($appvar){
            //IF Same values.. don't touch.. delete old and create new
            $appvar->value = $new_value;
            if($appvar->isDirty()){
                //$appvar->delete();
                AppVariable::where('name', $var_name)->delete();
                $appvar = AppVariable::create([
                    "name"=>$var_name,
                    "value"=>$new_value,
                    "updated_by"=>auth()->user()->id
                ]);
            }
        }
        //IF NOT CREATE
        else{
            $appvar = AppVariable::create([
                "name"=>$var_name,
                "value"=>$new_value,
                "updated_by"=>auth()->user()->id
            ]);
        }
        return $appvar->value;
    }
 

}