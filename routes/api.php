<?php

use Illuminate\Support\Facades\Route; 
use iProtek\Core\Http\Controllers\Manage\FileUploadController; 
use iProtek\Core\Http\Controllers\AppVariableController; 

//Route::prefix('sms-sender')->name('sms-sender')->group(function(){
  //  Route::get('/', [SmsController::class, 'index'])->name('.index');
//});
Route::middleware('api')->group(function(){

    Route::get('/app-list', [AppVariableController::class, 'api_applist'])->name('.app-list');

    Route::prefix('api/group/{group_id}')->middleware(['pay.api'])->name('api')->group(function(){

        Route::prefix('/file-upload')->name('.file-upload')->group(function(){
        
            Route::post('/add', [ FileUploadController::class ,'api_add'])->name('.add');
            Route::get('/get-list', [ FileUploadController::class ,'list'])->name('.get-list');
            Route::get('/get-list/{id}', [ FileUploadController::class ,'list'])->name('.get-one');
            Route::get('/image-preview/{id}', [ FileUploadController::class ,'image_preview'])->name('.image-preview');
            Route::get('/load-file/{id}', [ FileUploadController::class ,'load_file'])->name('.load-file');
            Route::post('/set-default/{id}', [ FileUploadController::class ,'set_default'])->name('.set-default');
            Route::delete('/remove/{id}', [ FileUploadController::class ,'remove'])->name('.remove');
        
        });

    });

});