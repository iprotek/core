<?php

use Illuminate\Support\Facades\Route;  
use iProtek\Core\Http\Controllers\AppVariableController;

Route::prefix('/settings')->name('.settings')->group(function(){

    Route::get('/get-field-setter', [ AppVariableController::class ,'get_field_settings'])->name('.get-field-settings')
                ->defaults("_description","Set field setting")
                ->defaults("_is_visible",true)
                ->defaults("_is_allow",true);

    Route::post('/set-field-setter',  [ AppVariableController::class ,'set_field_settings'])->name('.set-field-settings')
                ->defaults("_description","List of branch")
                ->defaults("_is_visible",true)
                ->defaults("_is_allow",true);

});