<?php

use Illuminate\Support\Facades\Route; 
use Illuminate\Http\Request;
use iProtek\Core\Helpers\UISettingHelper;
 
Route::prefix('languages')->name('languages.')->group(function(){
    Route::post('/', [iProtek\Core\Http\Controllers\LanguagesController::class, 'get_translation'])->name('get-translation');
    Route::get('/', [iProtek\Core\Http\Controllers\LanguagesController::class, 'get_languages'])->name('get-languages');
    Route::get('/get', [iProtek\Core\Http\Controllers\LanguagesController::class, 'get_current_language'])->name('get');
    Route::post('/set/{language}', [iProtek\Core\Http\Controllers\LanguagesController::class, 'set_current_language'])->name('set');
  });