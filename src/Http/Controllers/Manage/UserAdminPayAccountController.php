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


    public function login_pay_account(Request $request){
        
 
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
            $userAdmin = UserAdmin::create([
                'name' => $name,//Str::random(10),
                'username' => $email,
                'email' => $email,
                'company_id'=> $email,
                'password' => bcrypt('12345'),
                'is_verified'=>-1,
                'user_type'=>0,
                'region'=>'PH',
                'is_protected'=>1
            ]);
            DB::table('user_admin_infos')->insert([
                'user_admin_id' => $userAdmin->id,//Str::random(10),
                'company_id' => '12345',
                'first_name' => 'N/A',
                'middle_name'=> '',
                'last_name' => 'N/A',
                'position'=>'N/A',
                'department'=>'N/A',
                'line'=>'N/A',
                'factory'=>'PH',
                'is_active'=>1,
                'status_id'=>1,
                'region'=>'PH'
            ]);

        }
        $user = \iProtek\Core\Models\Auths\Admin::find($userAdmin->id);

        //CHECK IF USER HAS BRANCH ACCESS
        Auth::login($user, true);

        
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
        //$pay_account = UserAdminPayAccount::where(["user_admin_id"=>$user_admin->id])->first();
        //if(!$pay_account){
            $pay_account = UserAdminPayAccount::create([
                "browser_session_id"=>session()->getId(),
                "user_admin_id"=>$user_admin->id,
                "default_proxy_group_id"=> $sub_account ? 0 : $result["own_group"]['id'],
                "pay_app_user_account_id"=>$result['id'],
                "own_proxy_group_id"=>$result["own_group"]['id'],
                "email"=>$request->email,
                "access_token"=>$access_token,
                "refresh_token"=>$refresh_token,
                "sub_account_group_id"=>$sub_account_group_id
            ]);
        //}
        //else{
        /*
        $pay_account->user_admin_id = $user_admin->id;
        $pay_account->default_proxy_group_id =  $sub_account ? 0 : $result["own_group"]['id'];
        $pay_account->pay_app_user_account_id = $result['id'];
        $pay_account->own_proxy_group_id = $result["own_group"]['id'];
        $pay_account->email = $request->email;
        $pay_account->access_token = $access_token;
        $pay_account->refresh_token = $refresh_token;
        $pay_account->sub_account_group_id = $sub_account_group_id;
        $pay_account->save();
        */
        //}

        return redirect()->intended();

        return redirect('/manage');//->route('manage');
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
