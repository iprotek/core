<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\DeviceTemplateTrigger;
use iProtek\Core\Models\DeviceAccess;
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
        $list = PayModelHelper::get(DeviceTemplateTrigger::class, $request);
        $list->whereIn('device_access_id', $deviceAccessIds );
        $list->where('target_name', $request->target_name);
        $list->with(['device_access','device_accounts'=>function($q)use($request){
            $q->where('target_id', $request->target_id);
        }]);

        return $list->get();

    }

    public function get_one(Request $request){

    }

    public function register_account(Request $request){ 

        //TODO:: device_template_trigger_id

        
        //Check if template has enabled registration

        //Check if account already existed

    }
    
    public function set_active_account(Request $request){ 

        //TODO:: device_template_trigger_id

        //check account if existed


    }

    public function set_inactive_account(Request $request){

        //TODO:: device_template_trigger_id

        //check account if existed


    }
    
    public function remove_account(Request $request){

        //TODO:: device_template_trigger_id



    }


}
