<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\UserAdminPayAccount;
use Illuminate\Support\Facades\Session;
use App\Helpers\PayGroup;

class PayAppUserAccountApi
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
        $bearerToken = $request->bearerToken() ?: "";
        if(!$bearerToken && auth()->check()){
            $user = auth()->user();
            $pay_account = UserAdminPayAccount::where(["user_admin_id"=>$user->id])->first();
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
        
        $request->attributes->add([
            'user'=>$auth_info 
        ]);


         return $next($request);  
     }
}
