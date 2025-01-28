<?php

namespace iProtek\Core\Http\Controllers\Manage;

use iProtek\Core\Http\Controllers\_Common\_CommonController;
use Illuminate\Http\Request;
use iProtek\Core\Models\Cms;
use iProtek\Core\Helpers\PayModelHelper;
use iProtek\Core\Enums\CmsType;
use iProtek\Core\Enums\FileImportBatchStatus;
use iProtek\Core\Helpers\CmsHelper;

class CmsController extends _CommonController
{
    
    //
    public $guard = 'admin';
 

    public function save_cms(Request $request){
        $data = $this->validate($request, [
            "type"=>"required",
            "target_name"=>"required",
            "target_id"=>"required",
            "content"=>"nullable"
        ])->validated();
         
        $type = CmsType::getValue($data['type']);
        if(!$type){
            return [ "status"=>0,"message"=>"Invalid Type"];
        }
        $data['type'] = $type;


        //Constraint for Content
        if($data['content']){
            $data['content'] = trim($data['content']);
        }else {
            $data['content'] = '';
        }


        $cms = PayModelHelper::get(Cms::class, $request, [
            "type"=>$request->type,
            "target_name"=>$request->target_name,
            "target_id"=>$request->target_id
        ])->first();
        
        if(!$cms){
           $cms = PayModelHelper::create(Cms::class, $request, $data);
        }else{
            PayModelHelper::update($cms, $request, $data);
        }
        return ["status"=>1, "message"=>"Successfully Saved.", "data"=>$cms];
    }

    public function get_cms(Request $request){
        
        $data = $this->validate($request, [
            "type"=>"required",
            "target_name"=>"required",
            "target_id"=>"required"
        ])->validated();

        return [
            "content"=> \iProtek\Core\Helpers\CmsHelper::getContentByDetail($request->target_name, $request->target_id, $request->type)
        ];
    }


}
