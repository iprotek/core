<?php
namespace iProtek\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class TrackSessionChanges
{
    public function handle($request, Closure $next)
    {
        if(auth('admin')->check()){
            $currentId = session()->getId();
            $lastId = $request->cookie('__last_session_id');//
            //$lastId = Session::get('__last_session_id');

            // If session ID changed, store the previous one
            if ($lastId && $lastId !== $currentId) {
                session(['__previous_session_id' => $lastId]);
                //Log::error("NEW: ". $currentId." OLD: ".$lastId);
                \iProtek\Core\Helpers\UserAdminHelper::renew_pay_account_session($currentId, $lastId);
            }
            //Log::error("Curr: ". $currentId);
            //Log::error("Last: ". $lastId);

            //SET THE LAST SESSION?

            Session::put('__last_session_id', $currentId); 

            //10 YEARS COOKIE EXPIRE 
            $years = 10;
            $minutes = now()->addYears($years)->diffInMinutes();

            return $next($request)->cookie('__last_session_id', $currentId, $minutes );
        }

        return $next($request);
    }
}