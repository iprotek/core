<?php

namespace iProtek\Core\Http\Controllers\Auth;

use iProtek\Core\Http\Controllers\Controller;
use iProtek\Core\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    use ThrottlesLogins;

    protected $maxAttempts = 10; // Maximum number of login attempts
    protected $decayMinutes = 1; // Lockout duration in minutes

     
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::MANAGE;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        //$this->middleware('throttle:60,3')->only('login'); 
        //$this->middleware('throttle.login:60,3');//->only('login');
    }
    
    // Maximum number of login attempts allowed.
    public function maxAttempts()
    {
        return 3; // You can adjust this value as per your requirements.
    }
    // Override the username method to use a different field for throttling.
    public function username()
    {
        return 'email'; // Replace 'username' with the field name you want to use.
    }
    
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );
        abort(403, "TOO MANY LOGIN ATTEMPTS");
        /*
        return redirect()->route('login.locked')->withErrors([
            'message' => 'Too many login attempts. Please try again after some time.',
            'seconds' => $seconds,
        ]);
        */
    }

    public function index(Request $request){
        return view('auth.login');
    }

    public function logout(Request $request){
        Session::flush();

        auth('admin')->logout();

        return redirect('/');
    }
}
