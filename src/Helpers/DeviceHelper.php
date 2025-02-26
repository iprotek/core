<?php
namespace iProtek\Core\Helpers;

use DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use iProtek\Core\Models\Cms;
use iProtek\Core\Enums\CmsType;

class DeviceHelper
{ 
    /**
     * [account field="id" ] - get the data id
     * [device_account_id] - get the account id from the device upon registration.
     * [account field="plan" source="data"] - get the "plan" field value form target source model.
     * [account field="User Name" source="custom"] - get the "User Name" field value form target source custom fields. 
     */

    public static function translate_template($template, $target_id, $target_name){

    }
}