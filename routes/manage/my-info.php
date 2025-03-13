<?php

use Illuminate\Support\Facades\Route; 
use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\Manage\MyDetailController;

    //COMPANY DETAILS
    Route::prefix('/my-details')->name('.my-details')->group(function(){
        Route::get('/',[MyDetailController::class, 'index'])->name('.index');
        Route::get('/info',[MyDetailController::class, 'account_name_info'])->name('.account-name-info');
        Route::put('/info',[MyDetailController::class, 'update_account_name_info'])->name('.update-account-name-info');
    });  