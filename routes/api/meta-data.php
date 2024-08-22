<?php

use Illuminate\Support\Facades\Route;  
use iProtek\Data\Http\Controllers\ContentMetaDataController;
use iProtek\Data\Http\Controllers\Manage\FileUploadController; 
//use App\Http\Controllers\Manage\BillingSharedAccountDefaultBranchController;

 
Route::prefix('/meta-data')->name('.meta-data')->group(function(){
            
    //Route::post('/add', [ FileUploadController::class ,'api_add'])->name('.add');
    //Route::get('/get-list', [ FileUploadController::class ,'list'])->name('.get-list');
    //Route::get('/get-list/{id}', [ FileUploadController::class ,'list'])->name('.get-one');
    //Route::get('/image-preview/{id}', [ FileUploadController::class ,'image_preview'])->name('.image-preview');
    //Route::get('/load-file/{id}', [ FileUploadController::class ,'load_file'])->name('.load-file');
    //Route::post('/set-default/{id}', [ FileUploadController::class ,'set_default'])->name('.set-default');
   //Route::delete('/remove/{id}', [ FileUploadController::class ,'remove'])->name('.remove');

});