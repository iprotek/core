<?php

namespace iProtek\Core\Http\Controllers\_Common;

use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Helpers\PayModelHelper;
use Illuminate\Http\Request;

class _CommonOwnGroupController extends _CommonController
{ 
    public $guard = 'admin'; 

    public function getOwn($model, Request $request,  $fields=[]){
        return PayModelHelper::get_own( $model, $request, $fields);
    }

    public function getValidOwn($model, Request $request, $fields=[]){
        $m = $this->getOwn($model, $request, $fields);
        $own = $m->first();
        if($own){
            return $own;
        }
        response()->json(["message"=>"Unauthorized"], 403)->send();
        die(); 
    }



}
