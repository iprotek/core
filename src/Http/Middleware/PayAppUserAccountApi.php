<?php

namespace iProtek\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use iProtek\Core\Models\UserAdminPayAccount;
use Illuminate\Support\Facades\Session;
use iProtek\Core\Helpers\PayGroup;
use iProtek\Core\Models\Auths\Admin;

class PayAppUserAccountApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    static $user = null;
    static $is_own_group = false;
    
     public function handle($request, Closure $next)
     { 
        
        if (PayAppUserAccountApi::$user) {
            return $next($request);
        }

        $bearerToken = $request->bearerToken() ?: "";
        if(!$bearerToken && auth()->check()){
            $user = auth()->user();
            $user_admin_id = $user->id;
            $pay_account = \iProtek\Core\Helpers\UserAdminHelper::get_current_pay_account($user_admin_id);
            if(!$pay_account){
                abort(403, 'Forbidden Access2');
            }
            $bearerToken = $pay_account->access_token;
        }
        if(!$bearerToken){
            abort(403,'Forbidden Access1');
        }
        
        $auth_info = PayGroup::GroupAuth($bearerToken, $request->group_id);
        if(!$auth_info){
            abort(403,'Not allowed to access group:'.$request->group_id);
        }

        //Log::error($auth_info);
        
        $request->attributes->add([
            'user'=>$auth_info 
        ]);
        if(!auth()->check()){
            //TODO:: PLEASE FIX THIS FOR BROWSER.... DESTROY THIS WHEN LOGOUT...
            $userAdminPay = UserAdminPayAccount::where('pay_app_user_account_id', $auth_info["app_user_account"]["id"])->first();
            if($userAdminPay){
                $userAdmin = Admin::find($userAdminPay->user_admin_id);
                if($userAdmin){
                    Auth::setUser($userAdmin);
                }
            }
        }
        PayAppUserAccountApi::$user = $auth_info;


         return $next($request);  
     }
}
