<?php

use Illuminate\Support\Facades\Route;
use iProtek\SmsSender\Http\Controllers\iProtekCoreController;

//Route::prefix('sms-sender')->name('sms-sender')->group(function(){
  //  Route::get('/', [SmsController::class, 'index'])->name('.index');
//});
Route::middleware(['web-visits'])->group(function(){

  Route::get('/home', [iProtek\Core\Http\Controllers\MainPageController::class, 'index'])->name('mainpage2');
  Route::get('/', [iProtek\Core\Http\Controllers\MainPageController::class, 'index'])->name('mainpage');

});

Route::get('/login', [ iProtek\Core\Http\Controllers\Manage\UserAdminPayAccountController::class, 'setup' ])->name('login');
Route::post('/login', [ iProtek\Core\Http\Controllers\Manage\UserAdminPayAccountController::class, 'login_pay_account' ])->name('pay-login');
Route::get('/logout', [ iProtek\Core\Http\Controllers\Auth\LoginController::class, 'logout' ])->name('.get-logout');
Route::post('/pay-forget-password', [ iProtek\Core\Http\Controllers\Manage\UserAdminPayAccountController::class, 'post_forgot_password' ])->name('.pay-forget-password');
Route::get('/pay-account',  [ iProtek\Core\Http\Controllers\Manage\DashboardController::class, 'pay_acccount' ])->name('.pay-account');


Route::prefix('languages')->name('languages.')->group(function(){
  Route::post('/', [iProtek\Core\Http\Controllers\LanguagesController::class, 'get_translation'])->name('get-translation');
  Route::get('/', [iProtek\Core\Http\Controllers\LanguagesController::class, 'get_languages'])->name('get-languages');
  Route::get('/get', [iProtek\Core\Http\Controllers\LanguagesController::class, 'get_current_language'])->name('get');
  Route::post('/set/{language}', [iProtek\Core\Http\Controllers\LanguagesController::class, 'set_current_language'])->name('set');
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