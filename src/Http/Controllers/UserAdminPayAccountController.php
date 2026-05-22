<?php

namespace iProtek\Core\Http\Controllers;

use Illuminate\Http\Request;

class UserAdminPayAccountController extends Controller
{
    //
    public function app_user_auth(Request $request){

        $user_data = null;
        $status = 0;
        ///HEADER REQUIRED FOR COMPATIBILITY
        //CLIENT-ID
        //CLIENT-SECRET
        //APP-TYPE

        //"Authorization"=>"Bearer ".$client_id.":".$client_secret


        return [
            "status"=>$status,
            "app_type"=>config('iprotek.app_type'),
            "user_data"=>$user_data
        ];
    }
}
