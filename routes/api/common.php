<?php

use Illuminate\Support\Facades\Route;  
use iProtek\Core\Http\Controllers\Manage\TaggingController;
 
Route::prefix('/common')->name('.map')->group(function(){
            
    //Route::get('/settings', [ GoogleMapController::class ,'get_map_settings'])->name('.get-map-settings');
    //Route::post('/settings',  [ GoogleMapController::class ,'set_map_settings'])->name('.set-map-settings');
    Route::prefix('tagging')->group(function(){
        Route::post('/set',[ TaggingController::class ,'set_tag'])->name('.set-tag')
                ->defaults("_description","Set tag")
                ->defaults("_is_visible",false)
                ->defaults("_is_allow",true);
        Route::post('/get',[ TaggingController::class ,'get_tag'])->name('.get-tag')
                ->defaults("_description","Get tag")
                ->defaults("_is_visible",false)
                ->defaults("_is_allow",true);
    });

});