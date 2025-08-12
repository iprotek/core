<?php

namespace iProtek\Core\Http\Controllers\Manage;

use iProtek\Core\Http\Controllers\_Common\_CommonController;
use Illuminate\Http\Request;
use iProtek\Core\Helpers\PayModelHelper;
use iProtek\Core\Helpers\AppVarHelper;

class GoogleMapController extends _CommonController
{
    
    //
    public $guard = 'admin';
    function isValidVariableName($name)
    {
        // Check basic variable naming rules
        return preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $name);
    }

    public function get_map_settings(Request $request){

        $name = "";
        $target_id = $request->target_id;
        $target_name = $request->target_name;

        //check if name is Invalid 
        if( !($target_id && $target_name ) ){
            return ["status"=>0, "message"=>"Failed."];
        }

        $name = "google_map_view_setting_".$target_name.'_'.$target_id;

        if( !$this->isValidVariableName($name) ){
            return ["status"=>0, "message"=>"Failed."];
        }

        return [
            "status"=>1, 
            "message" => "Successfully get.",
            "data" => AppVarHelper::get($name)
        ];
    }

    public function set_map_settings(Request $request){

        $name = "";
        $target_id = $request->target_id;
        $target_name = $request->target_name;

        //check if name is Invalid 
        if( !($target_id && $target_name ) ){
            return ["status"=>0, "message"=>"Failed(1)."];
        }

        $name = "google_map_view_setting_".$target_name.'_'.$target_id;

        if( !$this->isValidVariableName($name) ){
            return ["status"=>0, "message"=>"Failed(2).".$name];
        }
        
        AppVarHelper::set([
            $name=> json_encode([
                "latitude"=>$request->latitude,
                "longitude"=>$request->longitude,
                "zoom"=>$request->zoom
            ]) 
        ]);


        return [ "status"=>1, "message"=>"Successfully Set" ];

    }
    
}
