<?php
use iProtek\Core\Http\Controllers\Manage\FileUploadController;

Route::prefix('/file-uploads')->name('.file-uploads')->group(function(){
    Route::post('/add', [ FileUploadController::class ,'add'])->name('.add');
    Route::get('/get-list', [ FileUploadController::class ,'list'])->name('.get-list');
    Route::get('/get-list/{id}', [ FileUploadController::class ,'list'])->name('.get-one');
    Route::get('/image-preview/{id}', [ FileUploadController::class ,'image_preview'])->name('.image-preview');
    Route::get('/load-file/{id}', [ FileUploadController::class ,'load_file'])->name('.load-file');
    Route::post('/set-default/{id}', [ FileUploadController::class ,'set_default'])->name('.set-default');
    Route::delete('/remove/{id}', [ FileUploadController::class ,'remove'])->name('.remove');
  });