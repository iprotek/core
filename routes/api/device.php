<?php

use Illuminate\Support\Facades\Route;  
//use iProtek\Core\Http\Controllers\Manage\FileUploadController; 
//use iProtek\Core\Http\Controllers\Manage\CmsController;
//use App\Http\Controllers\Manage\BillingSharedAccountDefaultBranchController;
use iProtek\Core\Http\Controllers\Manage\DeviceAccessController;
use iProtek\Core\Http\Controllers\Manage\DeviceAccessTriggerLogController;
use iProtek\Core\Http\Controllers\Manage\DeviceTemplateTriggerController;
use Illuminate\Http\Request; 

 
Route::prefix('/devices')->middleware('can:super-admin')->name('.device')->group(function(){
    
    //Route::post('/save', [ CmsController::class ,'save_cms'])->name('.save'); 
    //Route::post('/get-content', [ CmsController::class ,'get_cms'])->name('.get'); 
    /*
    */
    //LIST & GET
    Route::get('/list', [DeviceAccessController::class, 'list'])->name('.list');

    Route::get('list-selection', [DeviceAccessController::class, 'list_selection'])->name('.list-selection');

    //GET
    Route::get('get', [DeviceAccessController::class, 'get'])->name('.get');

    //ADD
    Route::post('add', [DeviceAccessController::class, 'add'])->name('.add');

    //UPDATE
    Route::post('save', [DeviceAccessController::class, 'save'])->name('.save');

    //DELETE
    Route::post('delete', [DeviceAccessController::class, 'remove'])->name('.remove');

    Route::get('logs', [DeviceAccessTriggerLogController::class, 'list'])->name('.list');

    Route::prefix('trigger')->name('.trigger')->group(function(){
        
        //LIST
        Route::get('list', [DeviceTemplateTriggerController::class, 'list'])->name('.list');

        //ADD
        Route::post('add', [DeviceTemplateTriggerController::class, 'add'])->name('.add');

        //REMOVE
        Route::delete('remove', [DeviceTemplateTriggerController::class, 'remove'])->name('.remove');

        //UPDATE
        Route::put('update', [DeviceTemplateTriggerController::class, 'update'])->name('.update');


    });

});