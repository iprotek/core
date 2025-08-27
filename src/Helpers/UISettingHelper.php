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


    public static function get_theme(){
        
        $user_id = auth()->user()->id;
        
        $data = AppVarHelper::get([
            "theme_dashboard_nav_color_$user_id",
            "theme_dashboard_sidebar_color_$user_id",
            "theme_dashboard_card_color_$user_id",
            "theme_front_nav_color_$user_id",
            "theme_front_card_color_$user_id"
        ]);

        if(!$data["theme_dashboard_nav_color_$user_id"]){
            $data["theme_dashboard_nav_color_$user_id"] = config('iprotek.navbar_color'); // default color
        }
        
        if(!$data["theme_dashboard_sidebar_color_$user_id"]){
            $data["theme_dashboard_sidebar_color_$user_id"] = config('iprotek.sidebar_color'); // default color
        }

        return [
            "theme_dashboard_nav_color" => $data["theme_dashboard_nav_color_$user_id"],
            "theme_dashboard_sidebar_color" => $data["theme_dashboard_sidebar_color_$user_id"],
            "theme_dashboard_card_color" => $data["theme_dashboard_card_color_$user_id"],
            "theme_front_nav_color" => $data["theme_front_nav_color_$user_id"],
            "theme_front_card_color" => $data["theme_front_card_color_$user_id"]
        ];
    }

    public static function reset_theme(){
        
        $user_id = auth()->user()->id;

        AppVarHelper::set([
            "theme_dashboard_nav_color_$user_id" => config('iprotek.navbar_color'),
            "theme_dashboard_sidebar_color_$user_id" => config('iprotek.sidebar_color'),
            "theme_dashboard_card_color_$user_id" => "",
            "theme_front_nav_color_$user_id" => "",
            "theme_front_card_color_$user_id" => ""
        ]);
    }



}