<?php

namespace iProtek\Core\Helpers;

//use Illuminate\Http\Request;
use DB;
use iProtek\Core\Models\AppVariable;


class AppVarHelper
{

    public static function get($var_name, $default = null, $target_id = null)
    {   
        if(is_array($var_name)){
            $var_name =  array_map('trim', array_values($var_name) );
            if(count($var_name) <= 0){
                return null;
            }

            $pre_appvars = AppVariable::whereIn('name', $var_name);
            if($target_id === null){
                $pre_appvars->whereRaw(' target_id IS NULL ');
            }
            else{
                $pre_appvars->where('target_id', $target_id);
            }
            $appvars =  $pre_appvars->get();

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
        
        $pre_appvar = AppVariable::where('name', $var_name);
        if($target_id === null){
            $pre_appvar->whereRaw(' target_id IS NULL ');
        }
        else{
            $pre_appvar->where('target_id', $target_id);
        }
        $appvar = $pre_appvar->first();

        if(!$appvar){
            return $default;
        }
        else if(!$appvar->value){
            return $default;
        }
        return $appvar->value;
    } 

    public static function set( $var_name, $new_value = null, $target_id = null, $is_update=false )
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
            
            $pre_appvars = AppVariable::whereIn('name', $temp_names);
            if($target_id === null){
                $pre_appvars->whereRaw(' target_id IS NULL ');
            }
            else{
                $pre_appvars->where('target_id', $target_id);
            }

            $appvars = $pre_appvars->get();

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
                            if($is_update === true){
                                $appvar->save();
                            }
                            else{
                                //$appvar->delete();                            
                                $toDel = AppVariable::where('name', $var);
                                if($target_id == null)
                                    $toDel->whereRaw( ' target_id IS NULL ' );
                                else 
                                    $toDel->where('target_id', $target_id); 
                                $toDel->delete();

                                $appvar = AppVariable::create([
                                    "name"=>$var,
                                    "value"=>$var_name[$orignalVarName],
                                    "updated_by"=>auth()->user()->id,
                                    "target_id"=>$target_id
                                ]);
                            }
                        }
                        break;
                    }
                }
                //Add new Variable
                if(!$exists){                                           
                    $appvar = AppVariable::create([
                        "name"=>$var,
                        "value"=>$var_name[$orignalVarName],
                        "updated_by"=>auth()->user()->id,
                        "target_id"=>$target_id
                    ]);
                }
            }
            return $new_value;
        }

        $var_name = trim( strtolower( $var_name) );

        //GET THE VARIABLE
        $pre_appvar = AppVariable::where('name', $var_name);
        if($target_id === null){
            $pre_appvar->whereRaw(' target_id IS NULL ');
        }
        else{
            $pre_appvar->where('target_id', $target_id);
        }
        $appvar = $pre_appvar->first();


        //IF EXISTS COMPARE VALUES
        if($appvar){
            //IF Same values.. don't touch.. delete old and create new
            $appvar->value = $new_value;
            if($appvar->isDirty()){
                if($is_update === true){
                    $appvar->save();
                }
                else{
                    $toDel = AppVariable::where('name', $var_name);
                    if($target_id == null)
                        $toDel->whereRaw( ' target_id IS NULL ' );
                    else 
                        $toDel->where('target_id', $target_id); 
                    $toDel->delete();

                    $appvar = AppVariable::create([
                        "name"=>$var_name,
                        "value"=>$new_value,
                        "updated_by"=>auth()->user()->id,
                        "target_id"=>$target_id
                    ]);
                }
            }
        }
        //IF NOT CREATE
        else{
            $appvar = AppVariable::create([
                "name"=>$var_name,
                "value"=>$new_value,
                "updated_by"=>auth()->user()->id,
                "target_id"=>$target_id
            ]);
        }
        return $appvar->value;
    }
 

}