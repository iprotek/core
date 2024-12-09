<?php

namespace iProtek\Core\Helpers;

//use Illuminate\Http\Request;
use DB;
use iProtek\Core\Models\AppVariable;
use Illuminate\Support\Facades\Session;


class UISettingHelper
{

    public static function get($setting_name, $template = 'adminlte'){
        $user = auth('admin')->user();
        $user_id = 0;
        if($user) $user_id = $user->id;
        
        $session_name = "ui_setting_".$setting_name."_".$template."_".$user_id;

        if(!Session::has($session_name)){
            return "";
        }
        return Session::get($session_name);

    }


    public static function set( $setting_name, $value, $template = 'adminlte'){
        
        $user = auth('admin')->user();
        $user_id = 0;
        if($user) $user_id = $user->id;

        $settings_array = [
            "sidebar_collapse"
        ];

        if(!in_array($setting_name, $settings_array))
            return 0;



        if( $setting_name == "sidebar_collapse" &&  $template == 'adminlte' && $value ){
            Session::put('ui_setting_sidebar_collapse_adminlte_'.$user_id, $value );
        }else if( $setting_name == "sidebar_collapse" && $template == 'adminlte' ){
            Session::put('ui_setting_sidebar_collapse_adminlte_'.$user_id, "");
        }

        return 1;
    }



}