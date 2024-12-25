<?php
namespace iProtek\Core\Helpers;

use DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PayGroup
{
    public static function GroupAuth( $access_token, $group_id ){
        //$user_admin = auth()->user();

        
        $client = PayHttp::auth($access_token);
        $response = $client->get('app-user-account/group/'.$group_id);
        
        $response_code = $response->getStatusCode();
        if($response_code != 200 && $response_code != 201){
            return null;
        }
        $result = json_decode($response->getBody(), true); 

        //Restrition on client_id
        if($result['app_user_account']['oauth_client_id'] != config('iprotek.pay_client_id')){
            return null;
        }

        return $result;
    }


    public static function getGroupId(){
        if(auth()->check()){
            $user = auth()->user();

            if(!Session::get("user_admin_group_".$user->id) ){
                return null;
            }
            return Session::get("user_admin_group_".$user->id);
        }
        return "";
    }

    public static function setGroupId($group_id){
        $user = auth()->user();
        Session::put("user_admin_group_".$user->id, $group_id);
    }

    public static function pay_account_id(){
        return PayHttp::pay_account_id();
    }
}