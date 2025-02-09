<?php

use Illuminate\Support\Facades\Route;  
//use iProtek\Core\Http\Controllers\Manage\FileUploadController; 
//use iProtek\Core\Http\Controllers\Manage\CmsController;
//use App\Http\Controllers\Manage\BillingSharedAccountDefaultBranchController;
use iProtek\Core\Http\Controllers\Manage\DeviceAccessController;

 
Route::prefix('/devices')->middleware('can:super-admin')->name('.device')->group(function(){
    
    //Route::post('/save', [ CmsController::class ,'save_cms'])->name('.save'); 
    //Route::post('/get-content', [ CmsController::class ,'get_cms'])->name('.get'); 
    /*
    */
    //LIST & GET
    Route::get('/list', [DeviceAccessController::class, 'list'])->name('.list');

    //GET
    Route::get('get', [DeviceAccessController::class, 'get'])->name('.get');

    //ADD
    Route::post('add', [DeviceAccessController::class, 'add'])->name('.add');

    //UPDATE
    Route::put('update', [DeviceAccessController::class, 'update'])->name('.update');

    //DELETE


});