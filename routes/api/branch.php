<?php


use Illuminate\Support\Facades\Route;  
use iProtek\Core\Http\Controllers\Manage\FileUploadController; 
use iProtek\Xrac\Http\Controllers\XbranchController;
//use iProtek\Core\Http\Controllers\Manage\CmsController;
//use App\Http\Controllers\Manage\BillingSharedAccountDefaultBranchController;

 
Route::prefix('/branches')->name('.branches')->group(function(){
    
    //Route::post('/save', [ CmsController::class ,'save_cms'])->name('.save'); 
    //Route::post('/get-content', [ CmsController::class ,'get_cms'])->name('.get'); 
    Route::get('list', [XbranchController::class, 'branch_list'])->name('.list');


});