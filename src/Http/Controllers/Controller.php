<?php

namespace iProtek\Core\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request){ 
 
        $validator = Validator::make($request->all(), 
            ["name"=>"required|email"]
        );
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 403);
        } 

        return ["message"=>"Successful"];
    }

    public function check_app_compatibility(Request $request){
        return ["status"=>1, "message"=>"Compatible."];
    }

}
