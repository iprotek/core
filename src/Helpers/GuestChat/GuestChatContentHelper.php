<?php
namespace iProtek\Core\Helpers\GuestChat;

use DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use iProtek\SmsSender\Helpers\PayMessageHttp;
use iProtek\Core\Helpers\MailHelper;
use Carbon\Carbon;

class GuestChatContentHelper
{   
    public static function message( $guest_chat_id, $before_id, $after_id, $pay_account_id=0){
        
        //SUBMIT VERIFICATION TO MESSAGING

        $response = PayMessageHttp::get_client("api/guest-chat/messages?guest_chat_id=$guest_chat_id&before_id=$before_id&after_id=$after_id&pay_account_id=$pay_account_id", true);
        $response_code = $response->getStatusCode(); 
        $data = $response->getBody();
        if(is_object($data) || is_string($data)){
            $data = json_decode( $data, TRUE );
        } 
        //if( !(200 <= $response_code && $response_code <=204)){
        return response()->json(  $data, $response_code);
        //}
    }
    
    public static function send_message(array $data){

        $pay_account_id = \iProtek\Core\Helpers\PayHttp::pay_account_id();
        $guest_chat_id = GuestChatHelper::get_chat_id();//$data["guest_chat_id"];
        $message = $data["message"];
        if(!$guest_chat_id){
            return response()->json(["message"=>"Guest Failed"], 403);
        }
        
        $response = PayMessageHttp::post_client("api/guest-chat/send-message", [ "guest_chat_id"=>$guest_chat_id, "message"=>$message, "pay_account_id"=>$pay_account_id ], true);
        $response_code = $response->getStatusCode(); 
        $data = $response->getBody();
        if(is_object($data) || is_string($data)){
            $data = json_decode( $data, TRUE );
        } 
        if( !(200 <= $response_code && $response_code <=204)){
            return response()->json(  $data, $response_code);
        }
        return response()->json(  $data, $response_code);
        //return ["status"=>1, "message"=>"message sent."];


    }
}