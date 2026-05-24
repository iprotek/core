<?php

namespace iProtek\Core\Http\Controllers;

use Illuminate\Http\Request;
use iProtek\Core\Helpers\PayHttp;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\SuperAdminSubAccount;
use iProtek\Core\Models\UserAdmin;

class UserAdminPayAccountController extends _CommonController
{
    //
    public function app_user_auth(Request $request){

        $user_data = null;
        $status = 0;
        ///HEADER REQUIRED FOR COMPATIBILITY
        //CLIENT-ID
        //CLIENT-SECRET
        //APP-TYPE

        $this->validate($request, [
            "email"=>"required",
            "password"=>"required"
        ]);
        
 
        //CLIENT LOGIN CHECKING
        $client = PayHttp::client();
        $email = $request->email;
        $password = $request->password;
        $data = ["email"=>$email, "password"=>$password];
        $response = $client->post('login', [
            "json" => $data
        ]);
        $response_code = $response->getStatusCode();
        if($response_code != 200 && $response_code != 201){
            return response()->json(["status"=>0, "message"=>'User credential doesn\'t match.'], 403);
        }

        //USER TOKEN AND DATA
        $user_data = json_decode($response->getBody(), true);        
        $refresh_token = isset($user_data['refresh_token']) ? $user_data['refresh_token'] : "";
        $access_token = $user_data['access_token'];
        $client = PayHttp::auth($access_token);
        $response = $client->get('app-user-account');
        
        $response_code = $response->getStatusCode();
        if($response_code != 200 && $response_code != 201){
            return response()->json(["status"=>0, "message"=>'Access token Error. Please contact your administrator.'], 403);
        }

        //
        $sub_account_group_id = null; 
        $sub_account = SuperAdminSubAccount::where('email', $email)->first();
        if($sub_account)
        {
            $sub_account_group_id = $sub_account->sub_account_group_id;

           $restrict = UserAdmin::where('email', $email)->first();
           if($restrict){
              $restrict->user_type = 2;
              $restrict->save();
           }
        }

        $result = json_decode($response->getBody(), true); 
        $account_info = [
            "pay_account_id"=>$result['id'],
            "default_proxy_group_id"=>$sub_account ? 0 : $result["own_group"]['id'],
            "own_proxy_group_id"=>$result["own_group"]['id'],
            "email"=>$email,
            "access_token"=>$access_token,
            "refresh_token"=>$refresh_token,
            "sub_account_group_id"=> $sub_account_group_id
        ];


        return [
            "status"=>$status,
            "app_type"=>config('iprotek.app_type'),
            "user_data"=> $account_info
        ];
    }
}
