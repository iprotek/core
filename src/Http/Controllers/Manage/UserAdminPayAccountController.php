<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\UserAdminPayAccount;
use iProtek\Core\Models\SuperAdminSubAccount;
use iProtek\Core\Models\UserAdmin;
use iProtek\Core\Helpers\PayHttp;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use DB;
use iProtek\Account\Helpers\AccountHelper;
use iProtek\Core\Helpers\UserAdminHelper;

class UserAdminPayAccountController extends _CommonController
{
    public $guard = 'admin';
    //
    public function setup(Request $request){
 
        return view('iprotek_core::pay-account.setup');
    }

    public function get_list_selection(Request $request){

        $groups = UserAdminPayAccount::on(); 
        $search_text = str_replace(' ','%', $request->search?:"");
        $groups->whereRaw(" email LIKE CONCAT('%',?,'%')",[$search_text]);
        $groups->select('pay_app_user_account_id as id', 'email as text');
        $groups->orderBy('email','ASC');
        $groups->groupByRaw('pay_app_user_account_id, email');
        return $groups->paginate(10);

    }


    public function login_account_provider(Request $request){


        $login_request_id = $request->login_request_id;
        $login_code = $request->login_code;
        $login_account_auth_code = $request->login_account_auth_code;
        //Account Helper
        $result = AccountHelper::verifyLoginRequest($login_request_id,  $login_code, $login_account_auth_code);

        $err_message = "";
        $user_admin = null;
        $pay_account = null;
        $sub_account = null;
        $name = null;
        $email= null;
        if($result && $result["status"] == 1){                
            
            $auth_result = $result["result"];

            if($auth_result && $auth_result["status"] == 1){

                //$err_message = $auth_result["message"];
                $user_admin = $auth_result["user_admin"];
                $pay_account = $auth_result["pay_account"];
                $sub_account = $auth_result["sub_account"];

                 $name = $user_admin["name"];
                 $email = $user_admin["email"];

            }
            else if($auth_result && $auth_result["message"]){
                $err_message = $auth_result["message"];
            }
            else{
                $err_message = "Something goes wrong(2)";
            }

            if(!$user_admin || !$pay_account){
                $err_message = "Something goes wrong rendering the account info(2)";
            }

        }
        else{
            $err_message = "Something is wrong with your account provider";
        }

        if($err_message){
            return redirect()->back()->with('error', 'Disabled.')->withErrors([ 
                'email' => $err_message//'Render pay account information.'
            ])->withInput($request->only('email'));
        }

        //CHECK USER ADMIN IF EXISTS BY EMAIL
        $set_user_admin = UserAdmin::where('email', $user_admin["email"])->first();
        //IF NOT ADD AND ADD INFO
        if(!$set_user_admin){        
            $set_user_admin = UserAdminHelper::create_account($name, $email);
        }
        //ELSE UPDATE
        else{

        }

        //JUST ADD

        //FORCE TO LOGIN
        $user = \iProtek\Core\Models\Auths\Admin::find($set_user_admin->id);

        //CHECK IF USER HAS BRANCH ACCESS
        Auth::login($user, true);


        //CONFIGURE ITS SUBACCOUNT SETTINGS
        //SUBACCOUNT FEATURE
        if($sub_account){
            $sub_account_group_id = null; 
            $sub_account = SuperAdminSubAccount::where('email', $user->email)->first();
            if($sub_account)
            {
                $sub_account_group_id = $sub_account->sub_account_group_id;

                $restrict = UserAdmin::where('email', $user->email)->first();
                if($restrict){
                    $restrict->user_type = 2;
                    $restrict->save();
                }
            }
            else{
                SuperAdminSubAccount::create([
                    "email"=>$sub_account["email"],
                    "user_type"=>$sub_account["user_type"],
                    "sub_account_group_id"=>$sub_account["sub_account_group_id"]
                ]);
            }
        }

        
        $account_info = [
            "pay_account_id"=>$pay_account['pay_app_user_account_id'],
            "default_proxy_group_id"=>$sub_account ? 0 : $pay_account["default_proxy_group_id"],
            "own_proxy_group_id"=>$pay_account["own_proxy_group_id"],
            "email"=>$pay_account["email"],
            "access_token"=>$pay_account["access_token"],
            "refresh_token"=>$pay_account["refresh_token"],
            "sub_account_group_id"=>$sub_account ? $sub_account["sub_account_group_id"] : $pay_account["sub_account_group_id"]

        ];

        UserAdminHelper::create_pay_account($set_user_admin->id, session()->getId(), $account_info);

        return $this->loginToIntended();
    }

    public function loginToIntended(){
        
        if(config('session.secure') === true){
            $url = session('url.intended', '/');
            $url = preg_replace('/^http:/i', 'https:', $url);
            return redirect()->to($url);
        }

        return redirect()->intended();
    }


    public function login_pay_account(Request $request){

        if($request->login_request_id){

            return $this->login_account_provider($request);

        }
 
        $checkUser = \iProtek\Core\Models\Auths\Admin::where('email', $request->email )->first();
        //CHECK IF USER IS ALLOWED
        if( $checkUser && !$checkUser->is_active ){
            return redirect()->back()->with('error', 'Disabled.')->withErrors([ 
                'email' => 'You are currently disabled. Please contact your administrator.'
            ])->withInput($request->only('email'));
        }
        //CHECK BRANCHES
        else if($checkUser){
            $allowedBranches = \iProtek\Core\Helpers\BranchSelectionHelper::active_branches($checkUser);
            if( count($allowedBranches) <= 0 ){
                return redirect()->back()->with('error', 'No Access.')->withErrors([ 
                    'email' => 'Please contact your administrator to gain access on any branch.'
                ])->withInput($request->only('email'));
            }
        }
 
        $client = PayHttp::client();
        
        $data =  ["email"=>$request->email, "password"=>$request->password]; 
        $response = $client->post('login', [
            "json" => $data
        ]);
        $response_code = $response->getStatusCode();
        if($response_code != 200 && $response_code != 201){
            
            return redirect()->back()->with('error', 'Credential Error')->withErrors([ 
                'email' => 'User credential doesn\'t match.'
            ])->withInput($request->only('email'));
            //return [ "status"=>0, "message" => "Invalidated:".$response->getReasonPhrase().$response->getBody(), "status_code"=>$response_code ];
        }
        $result = json_decode($response->getBody(), true);

        //Log::error($result);
        //die();

        $refresh_token = $result['refresh_token'];
        $access_token = $result['access_token'];

        //GET INFO
        $client = PayHttp::auth($access_token);
        $response = $client->get('app-user-account');
        
        $response_code = $response->getStatusCode();
        if($response_code != 200 && $response_code != 201){
            
            return redirect()->back()->with('error', 'Credential Error')->withErrors([ 
                'email' => 'Access token Error. Please contact your administrator.'
            ])->withInput($request->only('email'));
            //return [ "status"=>0, "message" => "Invalidated:".$response->getReasonPhrase().$response->getBody(), "status_code"=>$response_code ];
        }
        $result = json_decode($response->getBody(), true); 


        //Get the sub account
        //Log::error($result);
        //die();

        //Check if useradmin exists. if not then create
        $email = $result['email'];
        $name = $result['name'];
        $userAdmin = UserAdmin::where('email', $result['email'])->first();
        if(!$userAdmin){
            $userAdmin = UserAdminHelper::create_account($name, $email);
        }
        $user = \iProtek\Core\Models\Auths\Admin::find($userAdmin->id);

        //CHECK IF USER HAS BRANCH ACCESS
        Auth::login($user, true);

        //SUBACCOUNT FEATURE
        $sub_account_group_id = null; 
        $sub_account = SuperAdminSubAccount::where('email', $user->email)->first();
        if($sub_account)
        {
            $sub_account_group_id = $sub_account->sub_account_group_id;

           $restrict = UserAdmin::where('email', $user->email)->first();
           if($restrict){
              $restrict->user_type = 2;
              $restrict->save();
           }
        }
        
        $user_admin = auth()->user(); 
        $account_info = [
            "pay_account_id"=>$result['id'],
            "default_proxy_group_id"=>$sub_account ? 0 : $result["own_group"]['id'],
            "own_proxy_group_id"=>$result["own_group"]['id'],
            "email"=>$email,
            "access_token"=>$access_token,
            "refresh_token"=>$refresh_token,
            "sub_account_group_id"=>$sub_account ? $sub_account->sub_account_group_id : null

        ];

        UserAdminHelper::create_pay_account($user_admin->id, session()->getId(), $account_info); 

        return $this->loginToIntended();
    }
    
    /*
    public function view_forgot_password(Request $request){
        return view('pay-account.request-password');
    }
    */

    public function post_forgot_password(Request $request){
        
        $this->validate($request, [
            "email"=>"required|email"
        ]);

        return PayHttp::send_reconvery_link($request->email);
    }

}
