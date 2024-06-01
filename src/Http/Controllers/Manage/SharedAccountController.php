<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Helpers\PayHttp;

class SharedAccountController extends _CommonController
{
    //
    public $guard = 'admin';
    
    public function index(Request $request){
        return $this->view('manage.sharedaccounts');
    }


    public function pay_shared_accounts(Request $request){
        return PayHttp::app_user_account($request->search, $request->page?:1, $request->items_per_page?:10);
    }

    public function send_invitation(Request $request){
        
        $this->validate($request, [
            "email"=>"required|email",
            "role"=>"required",
            "password"=>"required|confirmed"
        ]);
        if(strtolower(trim($request->role)) == "owner"){
            return ["status"=>0,"message"=>"Role Owner is not allowed."];
        }
        return PayHttp::send_invitation($request->email, $request->role, $request->password);

    }
}
