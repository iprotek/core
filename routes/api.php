<?php

use Illuminate\Support\Facades\Route; 
use iProtek\Core\Http\Controllers\Manage\FileUploadController; 
use iProtek\Core\Http\Controllers\AppVariableController;
use iProtek\Core\Http\Controllers\SystemDataController;
use iProtek\Core\Http\Controllers\UserAdminPayAccountController;

Route::prefix('api')->middleware(['api'])->group(function(){
    
    //Company Details
    include(__DIR__.'/api/company-details.php');
    
    Route::middleware(['throttle:10,5','pay_app_check'])->post('auth/login',[UserAdminPayAccountController::class,'app_user_auth'])->name('.auth.login')
        ->defaults("_description","Getting user authentication for the app.")
        ->defaults("_is_visible",false)
        ->defaults("_is_allow",true);

    Route::middleware(['pay_app_check'])->get('check-app-compatibility', [\iProtek\Core\Http\Controllers\Controller::class, 'check_app_compatibility'])->name('api.check-app-compatibility');

    Route::get('app-info', [\App\Http\Controllers\AppInfoController::class, 'app_info'])->name('api.app-info');

    Route::get('app-list', [AppVariableController::class, 'api_applist'])->name('api.app-list');
    Route::post('raw-app-list', [AppVariableController::class, 'raw_api_applist'])->name('api.raw-app-list');
    

    Route::prefix('group/{group_id}')->middleware(['pay.api', 'policy.control'])->name('api')->group(function(){
        
        //FILE UPLOADS
        include(__DIR__.'/api/file-upload.php');

        //FILE UPLOADS
        include(__DIR__.'/api/meta-data.php');
        
        //FILE UPLOADS
        include(__DIR__.'/api/cms.php');

        //Branch
        include(__DIR__.'/api/branch.php');
        
        //Map
        include(__DIR__.'/api/map.php');
        
        //Settings
        include(__DIR__.'/api/settings.php');
        
        //Common
        include(__DIR__.'/api/common.php');

        Route::post('batch-request',[SystemDataController::class,'batch_request'])->name('.batch-request')
                ->defaults("_description","Allowing batch requesting.")
                ->defaults("_is_visible",false)
                ->defaults("_is_allow",true);

    }); 
 

    
});
