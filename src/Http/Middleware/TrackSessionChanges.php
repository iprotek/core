<?php
namespace iProtek\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class TrackSessionChanges
{
    public function handle($request, Closure $next)
    {
        $currentId = Session::getId();
        $lastId = session('__last_session_id');

        //Log::error("Detected".$currentId);
        // If session ID changed, store the previous one
        if ($lastId && $lastId !== $currentId) {
            session(['__previous_session_id' => $lastId]);
            //Log::error("NEW: ". $currentId." OLD: ".$lastId);
            \iProtek\Core\Helpers\UserAdminHelper::renew_pay_account_session($currentId, $lastId);
        }

        // Always update last known session ID
        session(['__last_session_id' => $currentId]);

        return $next($request);
    }
}