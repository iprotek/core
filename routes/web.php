<?php

use Illuminate\Support\Facades\Route; 

//Route::prefix('sms-sender')->name('sms-sender')->group(function(){
  //  Route::get('/', [SmsController::class, 'index'])->name('.index');
//});
Route::middleware('web')->group(function(){

  Route::middleware(['web-visits'])->group(function(){

    Route::get('/home', [iProtek\Core\Http\Controllers\MainPageController::class, 'index'])->name('mainpage2');
    Route::get('/', [iProtek\Core\Http\Controllers\MainPageController::class, 'index'])->name('mainpage');

  }); 
  
  Route::middleware('throttle:10,5')->get('/login', [ iProtek\Core\Http\Controllers\Manage\UserAdminPayAccountController::class, 'setup' ])->name('login');
  Route::middleware('throttle:5,5')->post('/login', [ iProtek\Core\Http\Controllers\Manage\UserAdminPayAccountController::class, 'login_pay_account' ])->name('pay-login');
  
  Route::get('/logout', [ iProtek\Core\Http\Controllers\Auth\LoginController::class, 'logout' ])->name('.get-logout');
  Route::post('/pay-forget-password', [ iProtek\Core\Http\Controllers\Manage\UserAdminPayAccountController::class, 'post_forgot_password' ])->name('.pay-forget-password');
  Route::get('/pay-account',  [ iProtek\Core\Http\Controllers\Manage\DashboardController::class, 'pay_acccount' ])->name('.pay-account');


  Route::prefix('languages')->name('languages.')->group(function(){
    Route::post('/', [iProtek\Core\Http\Controllers\LanguagesController::class, 'get_translation'])->name('get-translation');
    Route::get('/', [iProtek\Core\Http\Controllers\LanguagesController::class, 'get_languages'])->name('get-languages');
    Route::get('/get', [iProtek\Core\Http\Controllers\LanguagesController::class, 'get_current_language'])->name('get');
    Route::post('/set/{language}', [iProtek\Core\Http\Controllers\LanguagesController::class, 'set_current_language'])->name('set');
  });

    
  Route::prefix('manage')->name('manage')->group(function(){
    
    Route::middleware(['auth_web_pay_checker', 'pay.account'])->group(function(){
      //FILE UPLOADS
      
      Route::prefix('/file-uploads')->name('.file-uploads')->group(function(){
        Route::post('/add', [ iProtek\Core\Http\Controllers\Manage\FileUploadController::class ,'add'])->name('.add');
        Route::get('/get-list', [ iProtek\Core\Http\Controllers\Manage\FileUploadController::class ,'list'])->name('.get-list');
        Route::get('/get-list/{id}', [ iProtek\Core\Http\Controllers\Manage\FileUploadController::class ,'list'])->name('.get-one');
        Route::get('/image-preview/{id}', [ iProtek\Core\Http\Controllers\Manage\FileUploadController::class ,'image_preview'])->name('.image-preview');
        Route::get('/load-file/{id}', [ iProtek\Core\Http\Controllers\Manage\FileUploadController::class ,'load_file'])->name('.load-file');
        Route::post('/set-default/{id}', [ iProtek\Core\Http\Controllers\Manage\FileUploadController::class ,'set_default'])->name('.set-default');
        Route::delete('/remove/{id}', [ iProtek\Core\Http\Controllers\Manage\FileUploadController::class ,'remove'])->name('.remove');
      });
      
      
    });

  });

  Route::prefix('v2/Data')->name('v2.Data')->group(function(){

    //Data/Sample/1/20/0
    Route::get('{link}/{pageNo}/{itemsNo}/{jsonSearch}',[iProtek\Core\Http\Controllers\SystemDataController::class,'DataGet'] )->name('.DataGet');
    
    //Data/Sample?pageNo=1&itemsNo=20&jsonSearch=0
    Route::get('{link}', [iProtek\Core\Http\Controllers\SystemDataController::class,'DataGet_QS'] )->name('.DataGet_QS');
    
    Route::middleware(['admin'])->group(function(){
      //Data/Sample
      //ADD
      Route::post('{link}', [iProtek\Core\Http\Controllers\SystemDataController::class,'DataPost'])->name('.DataPost');
      
      //UPDATE 
      Route::put('{link}', [iProtek\Core\Http\Controllers\SystemDataController::class,'DataPut'])->name('.DataPut');

    });

  });

});

include(__DIR__.'/api.php');