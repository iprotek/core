<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\DeviceAccess;
use iProtek\Core\Helpers\PayModelHelper;
use iProtek\Core\Helpers\Console\MikrotikHelper;
use iProtek\Core\Helpers\Console\SshHelper;
use iProtek\Core\Models\DeviceAccessTriggerLog;

class DeviceAccessTriggerLogController extends _CommonController
{
    //
    public $guard = "admin";
    //
    public function list(Request $request){

        $data = $this->validate($request, [
            "device_access_id"=>"required"
        ])->validated(); 

        $deviceList = PayModelHelper::get(DeviceAccessTriggerLog::class, $request, $data);

        if($request->search_text){
            $search_text = '%'.str_replace(' ', '%', $request->search_text).'%';
            $deviceList->whereRaw(' CONCAT(target_name, command, log_info) LIKE ?', [$search_text]); 
        } 

        return $deviceList->paginate(10);
    }
}
