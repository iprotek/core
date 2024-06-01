<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController; 
use DB;
use iProtek\Core\Helpers\PayGroup;
use iProtek\Core\Models\UserAdminPayAccount;

class SettingController extends _CommonController
{
    //
    public $guard = 'admin';
    public function __construct()
    { 
    }
    public function index(Request $request){

        return $this->view('manage.settings.index');
    } 

    public function set_default_workspace(Request $request){
        $proxy_group_id = $request->proxy_group_id;
        PayGroup::setGroupId($proxy_group_id);
    
        $admin_id = auth()->user()->id;
        
        $billing_user_admin = UserAdminPayAccount::where(["user_admin_id"=>$admin_id])->first();
        $billing_user_admin->default_proxy_group_id = $proxy_group_id;
        $billing_user_admin->save();

        return ["status"=>1, "message"=>"Default workspace has already set."];

    }
}
