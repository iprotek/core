<?php

namespace iProtek\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\WebVisitor;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {    
        //Check session

        if($this->isNewSession()){

            $ip = $request->ip();

            // Get the user agent (browser information)
            $userAgent = $request->userAgent();
        
            // Store the visitor information in the database
            WebVisitor::create([
                'ip_address' => $ip,
                'user_agent' => $userAgent
            ]);
        
        }
        return $next($request);
    }


    public function isNewSession(){
        //Session::forget('track_visitor');
        if (!Session::has('track_visitor')) {
            $this->generateTrackSession();
            return true;
        }
        else if (now()->gt(Session::get('track_visitor.expiration'))) {
            Session::forget('track_visitor');
            $this->generateTrackSession();
            return true;
        }

        return false;
    }
    public function generateTrackSession(){
        $variableData = [
            'data' => Str::random(10),
            'expiration' => now()->addHours(24), // Set expiration to 24 hours from now
        ];                
        Session::put('track_visitor', $variableData);
    }
}
