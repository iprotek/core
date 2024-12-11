<?php
namespace iProtek\Core\Helpers\GuestChat;

use DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use iProtek\SmsSender\Helpers\PayMessageHttp;

class GuestChatHelper
{  

    public static function set_start_chat($name, $email, $contact_no){

        //SET SESSION

        
        //REGISTER TO MESSAGING API AND GET ID
        //iprotek.pay_message_url
        $response = PayMessageHttp::post_client("api/guest-chat/start-chat", ["name"=>$name, "email"=>$email, "contact_no"=>$contact_no], true);
        $response_code = $response->getStatusCode(); 
        $data = $response->getBody();
        if(is_object($data) || is_string($data)){
            $data = json_decode( $data, TRUE );
        } 
        if( !(200 <= $response_code && $response_code <=204)){
            return response()->json(  $data, $response_code);
        }

        //GET THE ID
         $guest_info = $data["guest_info"];

         

        //SESSION VARIABLES
        //message_api_guest_chat_id
        //guest_chat_name
        //guest_chat_email
        //guest_contact_no
        //guest_is_verified
        static::set_guest_session($guest_info['id'], $name, $email, $contact_no, $guest_info['session_id']); 
         
        return ["status"=>1, "message"=>"Preparing Support Chat."];
        
    }


    public static function set_guest_session($chat_id, $name, $email, $contact_no, $session_id){
        Session::put('guest_chat_id', $chat_id );
        Session::put('guest_chat_name', $name );
        Session::put('guest_chat_email', $email );
        Session::put('guest_chat_contact_no', $contact_no );
        Session::put('guest_chat_session_id', $session_id );
        Session::put('guest_chat_verified', 0 ); 
    }

    public static function set_verify_guest(){
        Session::put('guest_chat_verified', 1 );  
    }

    public static function close_guest_chat(){
        Session::put('guest_chat_id', '' );
        Session::put('guest_chat_name', '' );
        Session::put('guest_chat_email', '' );
        Session::put('guest_chat_contact_no', '' );
        Session::put('guest_chat_verified', 0 ); 
        Session::put('guest_chat_session_id', '' ); 
        return ["status"=>1, "message"=>"Clear guest chat"];
    }

    public static function check_chat(){
        return Session::has('guest_chat_id') && Session::get('guest_chat_id') ? true : false;
    }

    public static function get_chat_id(){
        if(static::check_chat())
            return Session::get('guest_chat_id');
        return 0;
    }

    public static function get_session_data($session_name){
        if(Session::has($session_name) && Session::get($session_name)){
            return  Session::get($session_name);
        }
        return "";
    }

    public static function get_chat_info(){

        
        $check = static::check_chat();
        $chat_id = static::get_chat_id();
        $chat_name = static::get_session_data('guest_chat_name');
        $chat_email = static::get_session_data('guest_chat_email');
        $chat_contact_no = static::get_session_data('guest_chat_contact_no');
        $chat_verified = static::get_session_data('guest_chat_verified');
        $chat_session_id = static::get_session_data('guest_chat_session_id');

        return [
            "guest_check"=>$check,
            "guest_chat_id"=>$chat_id,
            "guest_chat_name"=>$chat_name,
            "guest_chat_email"=>$chat_email,
            "guest_chat_contact_no"=>$chat_contact_no,
            "guest_chat_verified"=>$chat_verified,
            "guest_chat_session_id"=>$chat_session_id
        ];

    }



}