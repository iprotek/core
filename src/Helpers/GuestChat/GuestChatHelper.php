<?php
namespace iProtek\Core\Helpers\GuestChat;

use DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use iProtek\SmsSender\Helpers\PayMessageHttp;
use iProtek\Core\Helpers\MailHelper;
use Carbon\Carbon;

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


        //SET HIDDEN
        Session::put('guest_chat_verify_code', $guest_info['verify_code'] );
        Session::put('guest_chat_verify_attempts', 0 );
        Session::put('guest_chat_resend_verify_code_count', 0);

         
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
        Session::put('guest_chat_catered_by', 0 ); 

        //HIDDEN
        Session::put('guest_chat_verify_code', '' );
        Session::put('guest_chat_verify_attempts', 0 );
        Session::put('guest_chat_attempt_at', '');
        Session::put('guest_chat_resend_verify_code_count', 0);


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
        $guest_chat_catered_by = static::get_session_data('guest_chat_catered_by');
        $chat_verify_attempts = static::get_session_data('guest_chat_verify_attempts');

        return [
            "guest_check"=>$check,
            "guest_chat_id"=>$chat_id,
            "guest_chat_name"=>$chat_name,
            "guest_chat_email"=>$chat_email,
            "guest_chat_contact_no"=>$chat_contact_no,
            "guest_chat_verified"=>$chat_verified,
            "guest_chat_session_id"=>$chat_session_id,
            "guest_chat_catered_by"=>$guest_chat_catered_by,
            "guest_chat_verify_attempts"=>$chat_verify_attempts?:0
        ];

    }

    public static function submit_code($verify_code, $force=false){
        
        if(!$verify_code){
            return response()->json(["code"=>["Verify code is required"]], 403);
        }

        if(!static::check_chat()){
            return response()->json(["code"=>["Error encountered."]], 403);
        }
        $chat_verified = static::get_session_data('guest_chat_verified');
        if($chat_verified){
            return response()->json(["code"=>["Already Verified"]], 403);
        }
        //return response()->json(["code"=>["Already Verified"]], 403);

        if(!$force && false){
            $attempts = static::get_session_data('guest_chat_verify_attempts') ?: 0;
            if($attempts && $attempts >= 3){
                return response()->json(["code"=>["Verification invalidated only three(3) attempts allowed."]], 403);
            }
            //STORE ATTEMPTS
            Session::put('guest_chat_verify_attempts', ($attempts+1) );
        }

        //PRECHECK
        $code = static::get_session_data('guest_chat_verify_code');
        if(!$code){
            return response()->json(["code"=>["Error encountered."]], 403);
        }

        if($code != $verify_code){
            return response()->json(["code"=>["Invalid Code. Please retry."]], 403);
        }

        $chat_id = static::get_session_data('guest_chat_id');
        $email = static::get_session_data('guest_chat_email');

        //SUBMIT VERIFICATION TO MESSAGING 
        $response = PayMessageHttp::post_client("api/guest-chat/verify-code", [ "guest_chat_id"=>$chat_id, "email"=>$email, "code"=>$verify_code ], true);
        $response_code = $response->getStatusCode(); 
        $data = $response->getBody();
        if(is_object($data) || is_string($data)){
            $data = json_decode( $data, TRUE );
        } 
        if( !(200 <= $response_code && $response_code <=204)){
            return response()->json(  $data, $response_code);
        }
        
        Session::put('guest_chat_verified', 1 ); 

        return ["status"=>1, "message"=> "Verified!"];
    }

    public static function send_verification_code(){

        $attempts = static::get_session_data('guest_chat_verify_attempts');
        if($attempts > 3){
            return ["status"=>0, "message"=>"Vefication 3 attempts reached!"];
        }


        if(!static::check_chat()){
            return ["status"=>0, "message"=>"Something went wrong."];
        }
        

        $is_verified = static::get_session_data('guest_chat_verified');
        if($is_verified){
            return ["status"=>0, "message"=>"Already Verified"];
        }

        $code = static::get_session_data('guest_chat_verify_code');

        if(!$code){
            return ["status"=>0, "message"=>"Unable to retrieve code"];

        }

        $name = static::get_session_data('guest_chat_name');
        $email = static::get_session_data('guest_chat_email');


        //
        $secs = static::get_seconds_attempts();
        if($secs && $secs<120){
            return ["status"=>0, "message"=>"Wait until countdown ends", "seconds"=>$secs];
        }

    

        //SENDING VERIFICATION CODE HERE
        $mailable =  new \iProtek\SmsSender\Mailables\GuestVerifyEmailMailable($name, $code);
        MailHelper::send( $email, $mailable);
        
        
        Session::put('guest_chat_attempt_at', Carbon::now()->format('Y-m-d H:i:s') ); 
        $send_code_count = (static::get_session_data('guest_chat_resend_verify_code_count') ?: 0)+1;
        Session::put('guest_chat_resend_verify_code_count', $send_code_count);


        return ["status"=>1, "message"=>"We have sent the verification code", "seconds"=>static::get_seconds_attempts()];
    }

    public static function get_seconds_attempts(){
        $attempt_at = static::get_session_data('guest_chat_attempt_at');
        if($attempt_at ){
            //GET THE NOW AND ATTEMPT_AT DIFFERENCE
            $now = Carbon::now();
            $att = Carbon::parse($attempt_at);
            $sec = $now->diffInSeconds($att);
            if($sec > 120)
                return null;
            if($sec<2)
                return 1;
            return $sec;
        }
        return null;
    }

    public static function get_verify_attempts(){
        $verify_attempts = static::get_session_data('guest_chat_verify_attempts');
        return $verify_attempts?:0;
    }

    public static function send_verify_code_count(){
        $verify_attempts = static::get_session_data('guest_chat_resend_verify_code_count');
        return $verify_attempts?:0;

    }



}