<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\DeviceTemplateTrigger; 
use iProtek\Core\Helpers\PayModelHelper;

class DeviceTemplateTriggerController extends _CommonController
{
    protected $guard = "admin";
    //
    public function list(Request $request){

        $data = $this->validate($request,[
            "target_name"=>"required",
            "target_id"=>"required"
        ])->validated();

        $template = PayModelHelper::get(DeviceTemplateTrigger::class, $request)->where($data);
        

        return DeviceTemplateTrigger::where($data)->get();

    }
    public function add(Request $request){
        $data = $this->validate($request, [
            "trigger_name"=>"required",
            "target_name"=>"required",
            "target_id"=>"required",
            "device_access_id"=>"required",
            "enable_register"=>"required",
            "register_command_template"=>"nullable",
            "enable_update"=>"required",
            "update_command_template"=>"nullable",
            "enable_inactive"=>"required",
            "inactive_command_template"=>"nullable",
            "enable_active"=>"required",
            "active_command_template"=>"nullable",
            "enable_remove"=>"required",
            "remove_command_template"=>"nullable",
            "is_active"=>"required"
        ])->validated();




    }
    public function update(Request $request){
        $data = $this->validate($request, [
            "id"=>"required",
            "trigger_name"=>"required",
            "target_name"=>"required",
            "target_id"=>"required",
            "device_access_id"=>"required",
            "enable_register"=>"required",
            "register_command_template"=>"nullable",
            "enable_update"=>"required",
            "update_command_template"=>"nullable",
            "enable_inactive"=>"required",
            "inactive_command_template"=>"nullable",
            "enable_active"=>"required",
            "active_command_template"=>"nullable",
            "enable_remove"=>"required",
            "remove_command_template"=>"nullable",
            "is_active"=>"required"
        ])->validated();

    }
    public function remove(Request $request){

    }

}
