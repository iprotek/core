<?php

use Illuminate\Support\Facades\Route;  
use iProtek\Core\Http\Controllers\Manage\FileUploadController; 
//use App\Http\Controllers\Manage\BillingSharedAccountDefaultBranchController;

 
Route::prefix('/file-upload')->name('.file-upload')->group(function(){
            
    Route::post('/add', [ FileUploadController::class ,'api_add'])->name('.add')
                ->defaults("_description","Add upload file")
                ->defaults("_is_visible",true)
                ->defaults("_is_allow",false);

    Route::get('/get-list', [ FileUploadController::class ,'list'])->name('.get-list')
                ->defaults("_description","List of apps")
                ->defaults("_is_visible",true)
                ->defaults("_is_allow",false);

    Route::get('/get-list/{id}', [ FileUploadController::class ,'list'])->name('.get-one')
                ->defaults("_description","Get the upload file")
                ->defaults("_is_visible",true)
                ->defaults("_is_allow",false);

    Route::get('/image-preview/{id}', [ FileUploadController::class ,'image_preview'])->name('.image-preview')
                ->defaults("_description","Preview image")
                ->defaults("_is_visible",false)
                ->defaults("_is_allow",true);

    Route::get('/load-file/{id}', [ FileUploadController::class ,'load_file'])->name('.load-file')
                ->defaults("_description","Load file by id")
                ->defaults("_is_visible",false)
                ->defaults("_is_allow",true);

    Route::post('/set-default/{id}', [ FileUploadController::class ,'set_default'])->name('.set-default')
                ->defaults("_description","Set default of upload file")
                ->defaults("_is_visible",true)
                ->defaults("_is_allow",true);

    Route::delete('/remove/{id}', [ FileUploadController::class ,'remove'])->name('.remove')
                ->defaults("_description","Remove file upload")
                ->defaults("_is_visible",true)
                ->defaults("_is_allow",true);

});