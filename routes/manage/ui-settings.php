<?php

use Illuminate\Support\Facades\Route; 
use Illuminate\Http\Request;
use iProtek\Core\Helpers\UISettingHelper;

  /** UI SETTINGS */
  Route::get('/ui-settings/{template}/{setting_name}', function(Request $request, $template, $setting_name){
    return UISettingHelper::get($setting_name, $template);
  } )->name('ui-settings-clear');
  Route::get('/ui-settings/{template}/{setting_name}/{value}', function(Request $request, $template, $setting_name, $value){
    return UISettingHelper::set($setting_name, $value , $template);
  })->name('ui-settings');
  Route::get('/ui-settings-clear/{template}/{setting_name}', function(Request $request, $template, $setting_name){
    return UISettingHelper::set($setting_name, "", $template );
  })->name('ui-settings');