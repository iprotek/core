<?php

use Illuminate\Support\Facades\Route; 
use Illuminate\Http\Request;
use iProtek\Core\Helpers\GuestChat\GuestChatHelper;
use iProtek\Core\Http\Controllers\GuestChatController;


  /** GUEST CHAT */

  Route::prefix('/guest-chat')->name('guest-chat')->group(function(){
    Route::post('start-chat', [GuestChatController::class, 'start_chat'])->name('.start-chat');
  });