<?php

namespace iProtek\Core\Helpers;

use DB;
use iProtek\Core\Models\UserAdmin;
use iProtek\Core\Models\UserAdminPayAccount;
use Illuminate\Support\Facades\Log;

class UserAdminHelper
{
    public static function create_account($name, $email){
        
        $userAdmin = UserAdmin::create([
            'name' => $name,//Str::random(10),
            'username' => $email,
            'email' => $email,
            'company_id'=> $email,
            'password' => bcrypt('12345'),
            'is_verified'=>-1,
            'user_type'=>0,
            'region'=>'PH',
            'is_protected'=>1
        ]);
        DB::table('user_admin_infos')->insert([
            'user_admin_id' => $userAdmin->id,//Str::random(10),
            'company_id' => $email,
            'first_name' => 'N/A',
            'middle_name'=> '',
            'last_name' => 'N/A',
            'position'=>'N/A',
            'department'=>'N/A',
            'line'=>'N/A',
            'factory'=>'PH',
            'is_active'=>1,
            'status_id'=>1,
            'region'=>'PH'
        ]);

        return $userAdmin;

    }

    public static function create_pay_account($user_admin_id, $session_id, array $info){

        $pay_account = UserAdminPayAccount::create([
            "browser_session_id"=>$session_id,
            "user_admin_id"=>$user_admin_id,
            "default_proxy_group_id"=> $info["default_proxy_group_id"],
            "pay_app_user_account_id"=>$info['pay_account_id'],
            "own_proxy_group_id"=>$info["own_proxy_group_id"],
            "email"=>$info["email"],
            "access_token"=>$info["access_token"],
            "refresh_token"=>$info["refresh_token"],
            "sub_account_group_id"=>$info["sub_account_group_id"]
        ]);

        return $pay_account;

    }

    public static function get_current_pay_account($user_admin_id, $is_default=true){
        $pay_account = null;
        if(auth()->check()){
            $session_id = session()->getId();
            if($session_id){
                $pay_account = UserAdminPayAccount::where(['user_admin_id'=>$user_admin_id, 'browser_session_id'=>$session_id])->first();
                if($pay_account)
                    return $pay_account;
            }
        }
        if(!$pay_account && $is_default )
           return UserAdminPayAccount::where('user_admin_id', $user_admin_id)->first();
        return null;
    }

    public static function renew_pay_account_session($new_session_id, $old_session_id){
        if(auth('admin')->check() && $new_session_id){
            
            //Log::error("Checking...");
            
            $user_admin_id = auth('admin')->user()->id;

            $checkExists = UserAdminPayAccount::where(['browser_session_id'=>$new_session_id, 'user_admin_id'=>$user_admin_id])->first();
            if(!$checkExists && $old_session_id){
                $pay_account = UserAdminPayAccount::where(['browser_session_id'=>$old_session_id, 'user_admin_id'=>$user_admin_id])->first();
                if($pay_account){
                    $new_pay_account = $pay_account->replicate(); 
                    $new_pay_account->browser_session_id = $new_session_id;
                    $new_pay_account->save();
                    //Log::error("Created new...");
                }
                else{                    
                    //Log::error("Not exists pay account...");
                }
            }
            else{
                //Log::error("Already existed...");
            }


        }
    }
}
