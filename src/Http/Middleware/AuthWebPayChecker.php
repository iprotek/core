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
use iProtek\Core\Helpers\PayHttp;

class AuthWebPayChecker
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
         $user = auth()->user();
         $pay_account = UserAdminPayAccount::where(["user_admin_id"=>$user->id])->first(); 

        //Check if account is sub account
        if($pay_account){
            $sub_account = SuperAdminSubAccount::where(['email'=>$pay_account->email])->first();
            if($sub_account){
                //abort(403, 'TEST');
                if($pay_account->sub_account_group_id != $sub_account->sub_account_group_id){
                    
                    $pay_account->default_proxy_group_id = 0;
                    $pay_account->sub_account_group_id = $sub_account->sub_account_group_id;
                    $pay_account->save();


                }

                if($pay_account->default_proxy_group_id == 0){
                    //Check login - if failed then go to setup
                    if( PayHttp::account_info() == null)
                    {
                        return redirect()->route('manage.setup-account')->with('error', 'Please login.')->withErrors([ 
                            'email' => 'Please contact your administrator seems your account got error.'
                        ])->withInput( ['email'=>$user->email ]);
                    }
                    //If Success then redirect to setup default..

                    return redirect()->route('manage.sub-account-default-group');
                }
                $is_own_group = false;

            }
        }
 
         return $next($request);
         
     }
}
