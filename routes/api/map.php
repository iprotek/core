<?php

use Illuminate\Support\Facades\Route;  
use iProtek\Core\Http\Controllers\Manage\GoogleMapController;
 
Route::prefix('/map')->name('.map')->group(function(){
            
    Route::get('/settings', [ GoogleMapController::class ,'get_map_settings'])->name('.get-map-settings');
    Route::post('/settings',  [ GoogleMapController::class ,'set_map_settings'])->name('.set-map-settings');

});