<?php

use Illuminate\Support\Facades\Route;  
use iProtek\Core\Http\Controllers\Manage\FileUploadController; 
use iProtek\Core\Http\Controllers\Manage\CmsController;
//use App\Http\Controllers\Manage\BillingSharedAccountDefaultBranchController;

 
Route::prefix('/cms')->name('.cms')->group(function(){
    
    Route::post('/save', [ CmsController::class ,'save_cms'])->name('.save'); 
    Route::post('/get', [ CmsController::class ,'get_cms'])->name('.get'); 
    /*
    */
});