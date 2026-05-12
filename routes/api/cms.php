<?php

use Illuminate\Support\Facades\Route;  
use iProtek\Core\Http\Controllers\Manage\FileUploadController; 
use iProtek\Core\Http\Controllers\Manage\CmsController;
//use App\Http\Controllers\Manage\BillingSharedAccountDefaultBranchController;

 
Route::prefix('/cms')->name('.cms')->group(function(){
    
    Route::post('/save', [ CmsController::class ,'save_cms'])->name('.save')
                ->defaults("_description","Allow to write cms")
                ->defaults("_is_visible",true)
                ->defaults("_is_allow",true); 
    Route::post('/get-content', [ CmsController::class ,'get_cms'])->name('.get')
                ->defaults("_description","List of apps")
                ->defaults("_is_visible",false)
                ->defaults("_is_allow",true); 
    /*
    */
});