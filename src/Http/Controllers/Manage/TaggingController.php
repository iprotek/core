<?php

namespace iProtek\Core\Http\Controllers\Manage;

use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\Tagging;
use iProtek\Core\Helpers\PayModelHelper;

class TaggingController extends _CommonController
{
    //
    public $guard = "admin";


    public function set_tag(Request $request){
        $dataRequest = $this->validate($request, [
            "target_id" => "required",
            "target_name" => "required",
            "value" => "required"
        ])->validated();
        //if exists update
        $current_tag = PayModelHelper::get(Tagging::class, $request)->where(["target_id"=>$request->target_id, "target_name"=>$request->target_name])->first();
        //if not create
        if(!$current_tag){
            PayModelHelper::create(Tagging::class, $request, $dataRequest);
        }else{
            PayModelHelper::update($current_tag, $request, $dataRequest);
        }
        return ["status"=>1, "message"=>"Tag set"];
        
    }


    public function get_tag(Request $request){


    }



}
