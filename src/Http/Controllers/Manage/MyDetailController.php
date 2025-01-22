<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\UserAdmin;
use iProtek\Core\Models\UserAdminInfo;

class MyDetailController extends _CommonController
{
    //
    public $guard = 'admin';

    
    public function index(Request $request){
        return $this->view('iprotek_core::manage.my-info.index');
    }

    public function account_name_info(Request $request){
        if(auth('admin')->check()){

            $user_admin_id = auth('admin')->user()->id;

           $info =  UserAdminInfo::with(['user_admin'=>function($q){
            $q->select('id', 'email');
           }])->where('user_admin_id', $user_admin_id)->first();
        
           return $info;

        }
        return response()->json('{}', 200);
    }

    public function update_account_name_info(Request $request){

        $data = $this->validate($request, [
            "first_name"=>"required",
            "middle_name"=>"nullable",
            "last_name"=>"required"
        ])->validated();

        if(auth('admin')->check()){

            $user_admin_id = auth('admin')->user()->id;

            $info =  UserAdminInfo::with(['user_admin'])->where('user_admin_id', $user_admin_id)->first();

            $info->update($data);
            $info->user_admin->update(["name"=>($request->first_name." ".$request->last_name)]);


            return ["status"=>1, "message"=>"Information Updated"];

        }
        return ["status"=>0, "message"=>"failed to updated"];
    }



}
