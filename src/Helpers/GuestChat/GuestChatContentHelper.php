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
    public static function message( $guest_chat_id, $before_id, $after_id){
        
        //SUBMIT VERIFICATION TO MESSAGING

        $response = PayMessageHttp::get_client("api/guest-chat/messages?guest_chat_id=$guest_chat_id&before_id=$before_id&after_id=$after_id", true);
        $response_code = $response->getStatusCode(); 
        $data = $response->getBody();
        if(is_object($data) || is_string($data)){
            $data = json_decode( $data, TRUE );
        } 
        //if( !(200 <= $response_code && $response_code <=204)){
        return response()->json(  $data, $response_code);
        //}
    }
}