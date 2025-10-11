<?php


    //COMPANY DETAILS
    Route::prefix('/company-details')->name('.company-details')->group(function(){
        Route::get('/', [ iProtek\Core\Http\Controllers\Manage\CompanyDetailsController::class ,'index'])->name('.index');
        Route::get('/get-list', [ iProtek\Core\Http\Controllers\Manage\CompanyDetailsController::class ,'list'])->name('.get-list');
        Route::get('/get-list/{id}', [ iProtek\Core\Http\Controllers\Manage\CompanyDetailsController::class ,'list'])->name('.get-one');
        Route::post('/update-profile', [ iProtek\Core\Http\Controllers\Manage\CompanyDetailsController::class ,'update_profile'])
          ->middleware(['can:superadmin'])->name('.update-profile');
        Route::get('/get-profile', [ iProtek\Core\Http\Controllers\Manage\CompanyDetailsController::class ,'get_profile'])->name('.get-profile');
        Route::post('/update-background-setting', [ iProtek\Core\Http\Controllers\Manage\CompanyDetailsController::class ,'update_background_setting'])
          ->middleware(['can:superadmin'])->name('.update-background-setting');
        Route::get('/get-background-setting', [ iProtek\Core\Http\Controllers\Manage\CompanyDetailsController::class ,'get_background_setting'])->name('.get-background-setting');
        
        Route::post('/update-logo-setting', [ iProtek\Core\Http\Controllers\Manage\CompanyDetailsController::class ,'update_logo_setting'])
          ->middleware(['can:superadmin'])->name('.update-logo-setting');
        Route::post('/update-logo-link', [ iProtek\Core\Http\Controllers\Manage\CompanyDetailsController::class ,'update_logo_link'])
          ->middleware(['can:superadmin'])->name('.update-logo-link');
  
        Route::post('/update-terms-and-conditions', [ iProtek\Core\Http\Controllers\Manage\CompanyDetailsController::class ,'update_terms_and_conditions'])
          ->middleware(['can:superadmin'])->name('.update-terms-and-conditions');
        Route::get('/get-terms-and-conditions', [ iProtek\Core\Http\Controllers\Manage\CompanyDetailsController::class ,'get_terms_and_conditions'])->name('.get-terms-and-conditions');
        
        Route::post('/update-about-us', [ iProtek\Core\Http\Controllers\Manage\CompanyDetailsController::class ,'update_contact_us'])
          ->middleware(['can:superadmin'])->name('.update-about-us');
        Route::get('/get-about-us', [ iProtek\Core\Http\Controllers\Manage\CompanyDetailsController::class ,'get_contact_us'])->name('.get-about-us');


        Route::post('/update-theme', [ iProtek\Core\Http\Controllers\Manage\CompanyDetailsController::class ,'update_theme'])->name('.update-theme');
        //->middleware(['can:superadmin'])
        Route::get('/get-theme', [ iProtek\Core\Http\Controllers\Manage\CompanyDetailsController::class ,'get_theme'])->name('.get-theme');
        Route::post('/reset-theme', [ iProtek\Core\Http\Controllers\Manage\CompanyDetailsController::class ,'reset_theme'])->name('.reset-theme');
        //->middleware(['can:superadmin'])

      });  