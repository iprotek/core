<?php

namespace iProtek\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use iProtek\Core\Models\UserAdminPayAccount;
use Illuminate\Support\Facades\Session;
use iProtek\Core\Helpers\PayGroup;
use iProtek\Core\Models\SuperAdminSubAccount;

class PayAppUserAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    
     public function handle($request, Closure $next)
     {
        \iProtek\Core\Helpers\PayHttp::client();
         if (!Auth::guard()->check()) {
             return redirect('/login');//->route('login');
         }
         $user = auth()->user();
         $pay_account = UserAdminPayAccount::where(["user_admin_id"=>$user->id])->first();
         if(!$pay_account){
            
            return redirect()->route('login')->with('error', 'Please ')->withErrors([ 
                'email' => 'Please login your account.'
            ])->withInput( ['email'=>$user->email ]);

         } 


        $has_other_group_access = false;
        $group_id = PayGroup::getGroupId();
        //Log::error("1-".$group_id);
        if(!$group_id){
            PayGroup::setGroupId( $pay_account->default_proxy_group_id);
            $group_id = $pay_account->default_proxy_group_id;
        }

        $is_own_group = $pay_account->own_proxy_group_id == $group_id;
    
        //If don't own the group try, if try failed switch to own group.
        if($is_own_group == false){
            $auth_info = PayGroup::GroupAuth($pay_account->access_token, $group_id);
            if($auth_info){
                $has_other_group_access = true;
            }
            else{
                //Reset to own group.
                $group_id = $pay_account->own_proxy_group_id;
                $pay_account->default_proxy_group_id = $group_id; 
                $pay_account->save();
                PayGroup::setGroupId( $group_id);
                //Log::error("2-".$group_id);
            }
        }

        //Validate token
        //Check Session
        //IF had no access to other group then switch to own group.
        if($has_other_group_access == false){
            //Check if not sub account
           $is_subaccount = SuperAdminSubAccount::where('email', $user->email)->first();


            if($is_subaccount){
            
                return redirect()->route('login')->with('error', 'Please ')->withErrors([ 
                    'email' => 'Invalidated. Please login again.'
                ])->withInput( ['email'=>$user->email ]);

            }
            else{
                $auth_info = PayGroup::GroupAuth($pay_account->access_token, $group_id);
                if(!$auth_info){
                    //Log::error("3-".$group_id);
                    return redirect()->route('login');
                }
            }
        }    
        
        $request->attributes->add([
            'user'=>$auth_info,
            "is_own_group"=>$is_own_group
        ]);


 
         return $next($request);
         
     }
}
