<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\DeviceTemplateTrigger;

class DeviceAccountController extends _CommonController
{
    //

    public function list_device_triggers(Request $request){

        //TODO: branch_id and existing account

        //DeviceTemplateTrigger

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
