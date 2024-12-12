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

    Route::prefix('verification-code')->name('.verification-code')->group(function(){
      
      //Request verification code
      Route::post('request', function(Request $request){
        return GuestChatHelper::send_verification_code();
      })->name('.request');

      //Submit verification code
      Route::post('submit-code/{code}', function(Request $request){
        return GuestChatHelper::submit_code($request->code);
      })->name('.submit-code');


      //GET Request Coundown
      Route::get('get-countdown', function(Request $request){
        return [ 
          "status"=>1, 
          "message"=>"Get countdown.",
          "email"=> GuestChatHelper::get_session_data('guest_chat_email'),
          "seconds"=> GuestChatHelper::get_seconds_attempts(),
          "verify_attempts"=> GuestChatHelper::get_verify_attempts(),
          "verify_send_count"=> GuestChatHelper::send_verify_code_count(),
          "guest_chat_verify_attempts" => GuestChatHelper::get_session_data('guest_chat_verify_attempts') ?: 0,
          "guest_chat_id"=>GuestChatHelper::get_session_data('guest_chat_id')
        ];// GuestChatHelper::send_verification_code();
      })->name('.get-countdown');



    });

  });