<?php
namespace iProtek\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;

class TrackSessionChanges
{
    public function handle($request, Closure $next)
    {
        if(request()->hasSession()){

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
                
                //Check if user has current log-in pay-account else auto log-out
                else if (!$lastId){
                    $pay_account = \iProtek\Core\Helpers\UserAdminHelper::get_current_pay_account(auth()->user()->id, false);
                    if(!$pay_account){
                        //Logout and redirect to login.
                        //return redirect('/logout');
                        
                        auth('admin')->logout(); // Logs out the current user

                        // Optionally invalidate the session
                        request()->session()->invalidate();

                        // Regenerate CSRF token
                        request()->session()->regenerateToken();

                        // Redirect to login page
                        return redirect()->to(config('app.url').'/login');
                    }
                }

                $years = 10;
                $minutes = now()->addYears($years)->diffInMinutes();
                Cookie::queue('__last_session_id', $currentId, $minutes);

                //return $next($request)->cookie('__last_session_id', $currentId, $minutes);
                $response = $next($request);

                //For download purpose..
                if (method_exists($response, 'headers')) {
                    $response->headers->setCookie(cookie('__last_session_id', $currentId, $minutes));
                }

                return $response;
            
            }
            
        }

        return $next($request);
    }
}