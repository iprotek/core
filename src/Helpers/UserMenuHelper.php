<?php

namespace iProtek\Core\Helpers;
use Illuminate\Support\Facades\Session;
use App\Models\UserAdminCompany;
use DB;
use iProtek\Core\Models\Branch;
use iProtek\Core\Helpers\PayHttp;
use iProtek\Xrac\Models\XuserRole;
use iProtek\Core\Helpers\BranchSelectionHelper;

class UserMenuHelper
{

    public static function userHasMenu($user, $menu_control_name){

        $menu = \DB::table('sys_sidemenu_items')->where('menu_control_name', $menu_control_name)->first();
        if($menu){
            $userTypes = explode(',', $menu->user_types);
            //GET CURRENT BRANCH
            $branch_id = BranchSelectionHelper::get();
            $app_account_id = PayHttp::pay_account_id();
            $userRole = XuserRole::where(["app_account_id"=>$app_account_id, "branch_id"=>$branch_id])->first();
            if($userRole){
                if(in_array($userRole->xrole_id, $userTypes)){
                    return true;
                } 
            }
        }

        return false;

    }

}