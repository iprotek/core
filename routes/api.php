<?php

use Illuminate\Support\Facades\Route; 
use iProtek\Core\Http\Controllers\Manage\FileUploadController; 
use iProtek\Core\Http\Controllers\AppVariableController;
 
Route::prefix('api')->middleware(['api'])->group(function(){

    Route::middleware(['pay_app_check'])->get('check-app-compatibility', [\iProtek\Core\Http\Controllers\Controller::class, 'check_app_compatibility'])->name('api.check-app-compatibility');

    Route::get('app-info', [\App\Http\Controllers\AppInfoController::class, 'app_info'])->name('api.app-info');

    Route::get('app-list', [AppVariableController::class, 'api_applist'])->name('api.app-list');
    Route::post('raw-app-list', [AppVariableController::class, 'raw_api_applist'])->name('api.raw-app-list');

    Route::prefix('group/{group_id}')->middleware(['pay.api'])->name('api')->group(function(){
        
        //FILE UPLOADS
        include(__DIR__.'/api/file-upload.php');

        //FILE UPLOADS
        include(__DIR__.'/api/meta-data.php');
        
        //FILE UPLOADS
        include(__DIR__.'/api/cms.php');

        //Branch
        include(__DIR__.'/api/branch.php');

    });

    
});
