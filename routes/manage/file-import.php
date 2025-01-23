<?php


use iProtek\Core\Http\Controllers\Manage\FileImportBatchController;
use iProtek\Core\Http\Controllers\Manage\FileImportDataController;
use Illuminate\Http\Request;

Route::prefix('/file-imports')->name('.file-import')->group(function(){
    
    Route::prefix('batch')->name('.batch')->group(function(){
        Route::get('list', [FileImportBatchController::class, 'list'])->name('.list');
        Route::post('add', [FileImportBatchController::class, 'add'])->name('.add');
        /*
        Route::get('get', [FileImportBatchController::class, function(Request $request){
            //return "Hello";
            return \iProtek\Core\Helpers\FileImportHelper::startProcessing();
        }])->name('.get');
        */

        Route::prefix('action')->name('.action')->group(function(){
            Route::post('retry', [FileImportBatchController::class, 'action_retry'])->name('.retry');
            Route::post('start', [FileImportBatchController::class, 'action_start'])->name('.start');
            Route::post('stop', [FileImportBatchController::class, 'action_stop'])->name('.stop');
        });

    });

    Route::prefix('data')->name('.data')->group(function(){
        Route::get('/list', [FileImportDataController::class, 'list'])->name('.list');
    });

  });