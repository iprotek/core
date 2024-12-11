<?php

use Illuminate\Support\Facades\Route; 
use Illuminate\Http\Request;
use iProtek\Core\Helpers\GuestChat\GuestChatHelper;
use iProtek\Core\Http\Controllers\GuestChatController;


  /** GUEST CHAT */

  Route::prefix('/guest-chat')->name('guest-chat')->group(function(){

    Route::post('start-chat', [GuestChatController::class, 'start_chat'])->name('.start-chat');
    
    Route::get('chat-info', function(Request $request){
      return GuestChatHelper::get_chat_info();
    })->name('.chat-info');

    Route::post('clear-chat-info', function(Request $request){
      return GuestChatHelper::close_guest_chat();
    })->name('.clear-chat-info');

  });