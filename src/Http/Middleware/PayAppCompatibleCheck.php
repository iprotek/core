<?php

namespace iProtek\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use iProtek\Core\Models\UserAdminPayAccount;
use Illuminate\Support\Facades\Session;
use iProtek\Core\Helpers\PayGroup;

class PayAppCompatibleCheck
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
        $header_client_id = trim( $request->header('CLIENT-ID') ?: "" );
        $header_client_secret = trim( $request->header('CLIENT-SECRET') ?: "");
        $header_app_type = trim( $request->header('APP-TYPE') ?: "");

        $client_id = trim( config('iprotek.pay_client_id') ?: "" );
        $client_secret = trim( config('iprotek.pay_client_secret') ?: "");
        $app_type = trim( config('iprotek.app_type') ?: "");

        if( $header_client_id != $client_id || $header_client_secret != $client_secret  ){
            //return ["status"=>0, "message"=>"Invalid"];
            return response()->json([
                'status'=>0,
                'error' => 'Unauthorized',
                'message' => 'Invalid Credential' /*,
                'secret'=>$header_client_secret,
                'secret2'=>$client_secret,
                'client_id'=>$header_client_id,
                'client_id2'=>$client_id,*/
            ], 403);
            //abort(403, 'Invalid Credentials');
        }
        else if($header_app_type != $app_type){
            return response()->json([
                'status'=>0,
                'error' => 'Unauthorized',
                'message' => 'Invalid Application' /*
                'app_type'=>$header_app_type,
                'app_type2'=>$app_type*/
            ], 403);
        }


        return $next($request);  
     }
}
