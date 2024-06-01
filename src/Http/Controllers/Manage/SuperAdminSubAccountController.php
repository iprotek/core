<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\BillingSuperAdminSubAccount;
use iProtek\Core\Models\BillingUserAdminPayAccount;
use iProtek\Core\Models\UserAdmin;
use iProtek\Core\Models\UserAdminInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSubAccountController extends _CommonController
{
    //
    public $guard = 'admin'; 

    public function index(Request $request){
        return $this->view('manage.sub-accounts');
    }


    public function list(Request $request){
                
        $groups = BillingSuperAdminSubAccount::on();//BillingAccount::on(); 
        $search_text = str_replace(' ','%', $request->search?:"");
        $groups->whereRaw(" email LIKE CONCAT('%',?,'%')",[$search_text])->orderBy('email','ASC');
        $groups->with(["target_workspace"=>function($q){
            $q->select('id', 'pay_app_user_account_id', 'email' );
        }]);


        $results = $groups->paginate(10);
        
        
        return $results;
    } 

    public function get(Request $request){

        $id =  $request->id;
        $sub_acc = BillingSuperAdminSubAccount::with(['target_workspace'=>function($q){
            $q->select('id', 'pay_app_user_account_id', 'email' );
        }])->find($id);

        if($sub_acc){
            $ui_account =  \iProtek\Core\Models\UserAdmin::where('email',$sub_acc->email)->first();
            $sub_acc['has_web_ui'] =  $ui_account ? 1 : 0;
        }
        return $sub_acc;

    }


    public function add(Request $request){
        $this->validate($request, [
            "email" => "required|email",
            "target_workspace" => "required" 
        ]);

        //Check if already exists
        $exists = BillingSuperAdminSubAccount::where('email', $request->email)->first();
        if($exists){
            return ["status"=>0, "message"=>"Sub account already exists"];
        }


        if($request->create_web_ui == 1){
            $this->validate($request,[
                "first_name"=>"required",
                "last_name"=>"required"
            ]);
        }

        $add =  BillingSuperAdminSubAccount::create(["email"=>$request->email,"sub_account_group_id"=>$request->target_workspace,"user_type"=>2]);


        //Check web account
        if($request->create_web_ui == 1){

            //UserAdmin
            $exists = UserAdmin::where('email', $request->email)->first();
            if(!$exists){
                $fname = $request->first_name;
                $lname = $request->last_name;
                $user_admin = UserAdmin::create([
                    "name"=>$fname." ".$lname,
                    "username"=>$request->email,
                    "company_id"=>$request->email,
                    "email"=>$request->email,
                    "user_type"=>$add->user_type,
                    "is_verified"=>"-1",
                    "region"=>"PH",
                    "password"=>Hash::make( Str::random(20)),
                    "is_protected"=>1
                ]);
                UserAdminInfo::create([
                    "user_admin_id"=>$user_admin->id,
                    "company_id"=>$request->email,
                    "first_name"=>$fname,
                    "last_name"=>$lname,
                    "middle_name"=>"",
                    "position"=>"TMP",
                    "department"=>"TMP",
                    "line"=>"TEMP",
                    "factory"=>"TEMP",
                    "is_active"=>1,
                    "region"=>"PH",
                    "status_id"=>1
                ]);
            }else{
                $exists->user_type = $add->user_type;
                $exists->save();
            }
        }
        //Invite User


        return ["status"=>1, "message"=>"Successfully Created.", "data_id"=>$add->id];
    }

    public function update(Request $request){
        $this->validate($request, [
            "email" => "required|email",
            "target_workspace" => "required" 
        ]);

        $sub_acc  =  BillingSuperAdminSubAccount::with(['target_workspace'=>function($q){
            $q->select('id', 'pay_app_user_account_id', 'email' );
        }])->find($request->sub_account_id);
        if(!$sub_acc){
            return ["status"=>0, "message"=>"Subaccount not found."];
        }
        
        $sub_acc->sub_account_group_id = $request->target_workspace;
        $sub_acc->save();

        if($request->create_web_ui == 1){
            $this->validate($request,[
                "first_name"=>"required",
                "last_name"=>"required"
            ]);
            if($request->create_web_ui == 1){
                $exists = UserAdmin::where('email', $request->email)->first();

                if(!$exists){
                    $fname = $request->first_name;
                    $lname = $request->last_name;
                    $user_admin = UserAdmin::create([
                        "name"=>$fname." ".$lname,
                        "username"=>$request->email,
                        "company_id"=>$request->email,
                        "email"=>$request->email,
                        "user_type"=>$sub_acc->user_type,
                        "is_verified"=>"-1",
                        "region"=>"PH",
                        "password"=>Hash::make( Str::random(20)),
                        "is_protected"=>1
                    ]);
                    UserAdminInfo::create([
                        "user_admin_id"=>$user_admin->id,
                        "company_id"=>$request->email,
                        "first_name"=>$fname,
                        "last_name"=>$lname,
                        "middle_name"=>"",
                        "position"=>"TMP",
                        "department"=>"TMP",
                        "line"=>"TEMP",
                        "factory"=>"TEMP",
                        "is_active"=>1,
                        "region"=>"PH",
                        "status_id"=>1
                    ]);
                }else{
                    $exists->user_type = $add->user_type;
                    $exists->save();
                }
            }
        }
        //
        


        return ["status"=>1, "message"=>"Sub Account updated.", "data_id"=>$sub_acc->id];
    }

    public function sub_account_default_group(Request $request){
        return view('pay-account.set-default-group');
    }

    public function post_sub_account_default_group(Request $request){

        $user = auth()->user();
        $pay_account = BillingUserAdminPayAccount::where('email', $user->email)->first();

        if($pay_account){
            //BillingSuperAdminSubAccount
            //Load workspace list.
            $req = Request::create('/manage/pay-account', 'GET');
            $response = app()->handle($req);
            $result = json_decode($response->getContent(), true);

            $groups = $result['user_info']['groups'];
            if(count($groups) > 0){
                foreach($groups as $g){
                    if($g['group_id'] == $pay_account->sub_account_group_id){
                        $pay_account->default_proxy_group_id = $g['id'];
                        \iProtek\Core\Helpers\PayGroup::setGroupId($g['id']);
                        $pay_account->save();
                        return ["status"=>1, "message"=>"Default loaded.", "group"=>$g];
                    }
                }
                return ["status"=>0, "message"=>"Unable to find the default group."];

            }


            return ["status"=>0, "message"=>"Failed to retrieve"];
        }

        return["status"=>0, "message"=>"Failed."];

    }

}
