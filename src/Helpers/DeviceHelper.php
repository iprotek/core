<?php
namespace iProtek\Core\Helpers;

use DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use iProtek\Core\Helpers\DeviceVariableHelper;

class DeviceHelper
{ 
    /**
     * [account field="id" ] - get the data id
     * [device_account_id] - get the account id from the device upon registration.
     * [account field="plan" ] - get the "plan" field value form target source model.
     * [account field="User Name" data-json="json"] - get the "User Name" field value form target source custom fields. 
     */

    public static function translate_template($template, $target_id, $target_name){
        $data = \DB::table($target_name)->where('id', $target_id)->first();
        if(!$data){
            return ["status"=>0, "message"=>"Data Not Found", "template"=>$template];
        }

        //ACCOUNT
        //ACCOUNT TEMPLATE
        $translate = DeviceVariableHelper::account($template, $data);

        //DEVICE ACCOUNT ID
        $translate = DeviceVariableHelper::account($translate, $target_name, $traget_id); 
        
        return ["status"=>1, "message"=>"Translate Successful", "translate"=>$translate, "template"=>$template ];

    }
}