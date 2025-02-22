<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\DeviceTemplateTrigger;
use iProtek\Core\Models\DeviceAccess;
use iProtek\Core\Models\DeviceAccount;
use iProtek\Core\Helpers\PayModelHelper;

class DeviceAccountController extends _CommonController
{
    //

    public function list_device_triggers(Request $request){

        //Required
        //branch_id
        //target_name
        //target_id

        //TODO: branch_id and existing account
        $deviceAccessIds = DeviceAccess::whereRaw(' JSON_CONTAINS( branch_ids, ? ) ', [$request->branch_id])->get()->pluck('id')->toArray();

        //DeviceTemplateTrigger
        $list = PayModelHelper::get(DeviceTemplateTrigger::class, $request)->where('is_active', true);
        $list->whereIn('device_access_id', $deviceAccessIds );
        $list->where('target_name', $request->target_name);
        $list->with(['device_access','device_accounts'=>function($q)use($request){
            $q->where('target_id', $request->target_id);
            $q->limit(1);
        }]);

        return $list->get();

    }

    public function get_one(Request $request){

    }

    public function register_account(Request $request){ 

        //TODO:: device_template_trigger_id
        $this->validate($request, [
            "target_id"=>"required",
            "target_name"=>"required",
            "device_template_trigger_id"=>"required"
        ]);


        //CHECK IF ACCOUNT EXISTS
        $deviceAccount = PayModelHelper::get(DeviceAccount::class, $request)->where([
            "target_id"=>$request->target_id,
            "target_name"=>$request->target_name,
            "device_template_trigger_id"=>$request->device_template_trigger_id
        ])->first();

        if($deviceAccount){
            return ["status"=>0,"message"=>"Account already exists"];
        }


        //GET TEMPLATE TRIGGER INFO
        $trigger = PayModelHelper::with(['device_access'])->get(DeviceTemplateTrigger::class, $request)->where('is_active', true)->first();

        if(!$trigger || $trigger->is_active !== true ){
            return ["status"=>0,"message"=>"Device Trigger not available."];
        }

        //GET DEVICE
        $device_access = $trigger->device_access;
        if(!$device_access || $device_access->is_active !== true){
            return ["status"=>0,"message"=>"Device Access is not available."];
        }

        //CHECK IF ALLOW REGISTER
        if($trigger->enable_register !== true){
            return ["status"=>0, "message"=>"Register is disabled."];
        }

        //CHECK DEVICE
        //ADD DEVICE LOG

        //GET TEMPLATE
        //$trigger->register_command_template
        
        $request->template = $trigger->register_command_template;

        //CONVERT TEMPLATE TO PREVIEW
        $result = $this->register_account_preview($request);
        if($result['status'] == 0){
            return $result;
        }

        //ADD TO LOG

        //CHECK DEVICE CONNECTION


        //ADD TO LOG
        //EXECUTE THE COMMAND


      
        //RENDER THE ID

        //ADD DEVICE ACCOUNT

        return ["status"=>1, "message"=>"Successfully Added.", "data"=>null];
 

    }
    public function register_account_preview(Request $request){
        $this->validate($request, [
            "target_id"=>"required",
            "target_name"=>"required",
            "device_template_trigger_id"=>"nullable",
            //"template"=>"required"
        ]);

        $template = $request->template ?? ""; 

    }

    
    public function set_active_account(Request $request){ 

        //TODO:: device_template_trigger_id

        //check account if existed


    }    
    public function set_active_account_preview(Request $request){ 

        //TODO:: device_template_trigger_id

        //check account if existed


    }

    public function set_inactive_account(Request $request){

        //TODO:: device_template_trigger_id

        //check account if existed


    }
    public function set_inactive_account_preview(Request $request){

        //TODO:: device_template_trigger_id

        //check account if existed


    }
    
    public function remove_account(Request $request){

        //TODO:: device_template_trigger_id



    }
    public function remove_account_preview(Request $request){

        //TODO:: device_template_trigger_id



    }


}
