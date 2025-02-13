<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\DeviceTemplateTrigger;

class DeviceTemplateTriggerController extends _CommonController
{
    protected $guard = "admin";
    //
    public function list(Request $request){

        $data = $this->validate($request,[
            "target_name"=>"required",
            "target_id"=>"required"
        ])->validated();

        $template = DeviceTemplateTrigger::where($data);
        

        return DeviceTemplateTrigger::where($data)->get();

    }
    public function add(Request $request){

    }
    public function update(Request $request){

    }
    public function remove(Request $request){

    }

}
