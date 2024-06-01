<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\UserAdminPayAccount;
use Illuminate\Support\Facades\Session;
use App\Helpers\PayGroup;

class SuperAdmin
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
        $user = auth()->user();
        if($user->user_type * 1 > 0){
            abort(403, 'Forbidden Access');
        }
         return $next($request);
         
     }
}
