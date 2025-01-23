<?php


use iProtek\Core\Http\Controllers\Manage\FileImportBatchController;
use iProtek\Core\Http\Controllers\Manage\FileImportDataController;
use Illuminate\Http\Request;

Route::prefix('/file-imports')->name('.file-import')->group(function(){
    
    Route::prefix('batch')->name('.batch')->group(function(){
        Route::get('list', [FileImportBatchController::class, 'list'])->name('.list');
        Route::post('add', [FileImportBatchController::class, 'add'])->name('.add');
        Route::get('get', [FileImportBatchController::class, function(Request $request){
            //return "Hello";
            return \iProtek\Core\Helpers\FileImportHelper::startProcessing();
        }])->name('.add');
    });

    Route::prefix('data')->name('.data')->group(function(){

    });

  });