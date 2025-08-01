<?php

namespace iProtek\Core\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use iProtek\Core\Helpers\GuestChat\GuestChatHelper;
use iProtek\Core\Helpers\GuestChat\GuestChatContentHelper;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Helpers\UserAdminHelper;

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

    public function get_messages(Request $request){

        if(auth('admin')->check()){
            $this->validate($request, [
                "guest_chat_id"=>"required"
            ]);
            $chat_id = $request->guest_chat_id;
        }else{
            $chat_id = GuestChatHelper::get_chat_id();
        }
        $pay_account_id = 0;
        if(auth('admin')->check()){
            $user_id = auth('admin')->user()->id;            
            $pay_account = UserAdminHelper::get_current_pay_account($user_id);
            if($pay_account){
                $pay_account_id = $pay_account->pay_app_user_account_id;
            }
        }
        

        return GuestChatContentHelper::message( $chat_id, $request->before_id, $request->after_id, $pay_account_id );
    }

    public function send_message(Request $request){
        
        $data = [
            "message"=>$request->message
        ]; 
        return GuestChatContentHelper::send_message($data);
    }

}
