<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request; 
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\DeviceAccess;
use iProtek\Core\Helpers\PayModelHelper;
use iProtek\Core\Helpers\Console\MikrotikHelper;
use iProtek\Core\Helpers\Console\SshHelper;

class DeviceAccessController extends _CommonController
{
    public $guard = "admin";
    //
    public function list(Request $request){
        $deviceList = PayModelHelper::get(DeviceAccess::class, $request, []);

        if($request->search_text){
            $search_text = '%'.str_replace(' ', '%', $request->search_text).'%';
           $deviceList->whereRaw(' CONCAT(type,name,user,port) LIKE ?', [$search_text]);

        }



        return $deviceList->paginate(10);
    }

    public function device_connection_check( array $data){

        if($data['type'] == "mikrotik"){
            $result =  MikrotikHelper::credential_login_check($data);
            
            if($result["status"] == 0 ){
                return ["status"=>0, "message"=>$result["message"]];
            }
            return $result;
        }
        else if($data['type'] == "ssh"){
            $result =  SshHelper::credential_login_check($data);
            
            if($result["status"] == 0 ){
                return ["status"=>0, "message"=>$result["message"]];
            }
            return $result;
            
        }

        return ["status"=>0, "message"=>"Type [".$data['type']."] not supported for checking yet."];
    }

    public function add(Request $request){

        //VALIDATIONS
        //Check if exists constrain by group_id and name

        $data = $this->validate($request, [
            "name"=>"required",
            "host"=>"required",
            "user"=>"required",
            "port"=>"required",
            "type"=>"required",
            "description"=>"nullable",
            "branch_ids"=>"required",
            "password"=>"nullable",
            "is_active"=>"required",
            "is_app_execute"=>"required",
            "is_ssl"=>"required"
        ])->validate();

        $exists = PayModelHelper::get(DeviceAccess::class, $request, [
            "name"=>$request->name
        ])->first();

        if(!in_array($request->type, ["mikrotik", "windows", "ssh"])){
            return [ "status"=>0, "message"=>"Device Type is not supported" ];
        }

        if($exists){
            return ["status"=>0, "message"=>"Device Custom Name already exists"];
        }

        //CHECKING DEVICE SUCCESS LOGIN ACCOUNT VALIDATION
        if($request->is_check_before_saving){ 
            //IF FAILED
            $result = $this->device_connection_check($data);
            if($result["status"] == 0){
                return $result;
            }
        }
        if(!$data["password"]){
            $data["password"] = "";
        }

        //ADDING
        $device_access = PayModelHelper::create(DeviceAccess::class, $request, $data);



        return ["status"=>1, "message"=>"Successfully Added.", "data"=>$device_access];
    }

    public function get(Request $request){
        
        //CHECK IF NAME EXISTTED
        $requiredId = $this->validate($request, [
            "device_access_id"=>"required"
        ])->validate();

        //VALIDATE NAME EXCEPT THE ID
        $device_access_id = $requiredId['device_access_id'];

        $can_manage = PayModelHelper::get(DeviceAccess::class, $request,[])->find($device_access_id);
        
        return $can_manage;
        
    }


    public function save(Request $request){
        
        //CHECK IF NAME EXISTTED
        $requiredId = $this->validate($request, [
            "device_access_id"=>"required"
        ])->validate();

        //VALIDATE NAME EXCEPT THE ID
        $device_access_id = $requiredId['device_access_id'];

        //CHECK IF YOU CAN MANAGE THE ID
        $can_manage = PayModelHelper::get(DeviceAccess::class, $request, [])->find($device_access_id);
        if(!$can_manage){
            return ["status"=>0, "message"=>"Access Device Forbidden access"];
        }

        $data = $this->validate($request, [
            "name"=>"required",
            "host"=>"required",
            "user"=>"required",
            "port"=>"required",
            "type"=>"required",
            "description"=>"nullable",
            "branch_ids"=>"required",
            "password"=>"nullable",
            "is_active"=>"required",
            "is_app_execute"=>"required",
            "is_ssl"=>"required"
        ])->validate();
        
        if(!in_array($request->type, ["mikrotik", "windows", "ssh"])){
            return [ "status"=>0, "message"=>"Device Type is not supported" ];
        }

        //CHECK IF ALREADY EXISTS
        $exists = PayModelHelper::get(DeviceAccess::class, $request,[])->whereRaw(' name = ? AND id NOT IN(?) ',[$data['name'], $device_access_id])->first();
        if($exists){
            return["status"=>0, "message"=>"Device custom name already exists"];
        }

        //Render password form existing.
        if(!$data['password']){
            $data['password'] = $can_manage['password'];
        }
        
        //CHECKING DEVICE SUCCESS LOGIN ACCOUNT VALIDATION
        if($request->is_check_before_saving){ 
            //IF FAILED
            $result = $this->device_connection_check($data);
            if($result["status"] == 0){
                return $result;
            }
        }



        PayModelHelper::update($can_manage, $request, $data);



        return ["status"=>1, "message"=>"Successfully updated", "data"=>$can_manage];

    }

    public function remove(Request $request){
        
        //CHECK IF NAME EXISTTED
        $requiredId = $this->validate($request, [
            "device_access_id"=>"required"
        ])->validate();

        //VALIDATE NAME EXCEPT THE ID
        $device_access_id = $requiredId['device_access_id'];

        //CHECK IF YOU CAN MANAGE THE ID
        $can_manage = PayModelHelper::get(DeviceAccess::class, $request, [])->find($device_access_id);
        if(!$can_manage){
            return ["status"=>0, "message"=>"Access Device Forbidden access"];
        }
        PayModelHelper::delete($can_manage, $request);
        
        return ["status"=>1, "message"=>"Successfully removed."];

    }
}
