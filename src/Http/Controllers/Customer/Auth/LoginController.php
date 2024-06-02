<?php

namespace iProtek\Core\Http\Controllers\Customer\Auth;

//use iProtek\Core\Http\Controllers\Controller;
use iProtek\Core\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\Customer; 

class LoginController extends _CommonController
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
    public $guard = 'customer';

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::CUSTOMER;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function guard()
    {
        return Auth::guard('customer');
    }
    protected function authenticated(Request $request, $user)
    {
        if (!$user->hasVerifiedEmail()) {    
            
            auth('customer')->logout();        
            $user->sendEmailVerificationNotification();
            
            return redirect()->route('customer.login')->with('error', 'Please verify your email address.')->withErrors([
                'email' => 'Please verify your email address.',
                'info' => 'A verification link sent to your email: '.$user->email
            ])->withInput($request->only('email'));        
        }
    }

    public function login_app_user(Request $request){
        $this->validate($request, [
            "email"=>[
                'required',
                function ($attribute, $value, $fail) {
                    $appUser = Customer::whereRaw(' ( email = ? ) ',[$value])->first();
                    if(!$appUser){
                        $fail("Your credential did not match our records.(1)");
                    }
                    else if( !$appUser->email_verified_at){
                        $fail('The email must be verified.');
                    }

                },
            ],
            "password"=>"required|min:8"
        ],["email.required"=>"Account No. or Email is required!"]);


        if (Auth::guard($this->guard)->attempt(['email' => $request['email'], 'password' => $request['password']], $request['remember'] )) {
            return [
                "status"=>1,
                "message"=>"Successfully Login using email."
            ];
        }
        else if (Auth::guard($this->guard)->attempt(['account_no' => $request['email'], 'password' => $request['password']], $request['remember'] )) {
            return [
                "status"=>1,
                "message"=> "Login successful using account no."
            ];
        }
        if(auth('customer')->check()){

        }

        $this->validate($request, [
            "email"=>[ 
                function ($attribute, $value, $fail) { 
                        $fail('Your credential did not match our records.(2)');
                },
            ] 
        ]);

    }

    public function verify_email(Request $request){
        if (! $request->hasValidSignature()) {
            abort(403,'Invalid Request');
        }

        $customer = Customer::where('email', $request->email)->first(); 
        if(!$customer->is_email_confirmed){
            $customer->is_email_confirmed = 1;
            $customer->email_verified_at = \Carbon\Carbon::now();
            $customer->save();
        }

        
        return redirect()->route('customer.login')->with('error', 'Email Verified')->withErrors([
            //'email' => 'Please verify your email address.',
            'info' => 'You\'re email has been verified. Please login again.'
        ])->withInput($request->only('email'));

    }

    
}
