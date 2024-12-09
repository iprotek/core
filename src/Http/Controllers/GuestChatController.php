<?php

namespace iProtek\Core\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use iProtek\Core\Helpers\GuestChat\GuestChatHelper;
use iProtek\Core\Http\Controllers\_Common\_CommonController;

class GuestChatController extends _CommonController
{
    use AuthorizesRequests, DispatchesJobs;

    public function index(Request $request){ 
 
        $validator = Validator::make($request->all(), 
            ["name"=>"required|email"]
        );
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 403);
        } 

        return ["message"=>"Successful"];
    }

    public function start_chat(Request $request){
        /*
        $this->validate($request, [
            "name"=>"required",
            "email"=>"required|email"
        ]);
        */

        //REGISTER SESION
        return GuestChatHelper::set_start_chat($request->name, $request->email, $request->contact_no);
 
        //return ["status"=>1, "message"=>"Chatting successful"];

    }

}
