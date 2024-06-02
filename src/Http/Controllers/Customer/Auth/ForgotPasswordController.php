<?php

namespace iProtek\Core\Http\Controllers\Customer\Auth;

use iProtek\Core\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Auth;
use iProtek\Core\Models\Auths\Customer;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Passwords\PasswordBroker;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    public function __construct()
    {
        $this->middleware('guest');
    }

    use SendsPasswordResetEmails; 
    //protected $linkRequestView = 'customer-auths.passwords.email';
    protected $emailView = 'customer-auths.passwords.email'; 
    protected function guard()
    {
        return Auth::guard('customer');
    }

    // Override the broker() method to use the custom model
    public function broker()
    {
        /*return Password::broker('customer')->sendResetLink($request->only('email'));
        $userProvider = app('auth')->createUserProvider('customer');
        $passwordBroker = new PasswordBroker(
            app('auth')->createUserProvider('customer'),
            app('auth')->createUserProvider('customer')->getPasswordBroker()
        );
        return $passwordBroker;
        
        */
        return Password::broker('customer'); // Use the 'customers' password broker for the custom model
    }

    public function showLinkRequestForm()
    {
        return view('customer-auths.passwords.email'); // Use your custom email view
    }

    protected function sendPasswordResetLink(Request $request)
    {
        $this->validateEmail($request);
    
        // Customize the URL here
        //$link = 'https://example.com/password/reset/' . $this->broker() . '/' . $request->email;
        
        $link = route('customer.password.reset',["token"=>$this->broker(),"email"=>$request->email]);
        $this->broker()->sendResetLink(
            $request->only('email')
        );
    
        return back()->with('status', 'Password reset link sent to ' . $request->email);
    }
    /*
    public function sendPasswordResetNotification($token): void
    {
        $url = route('customer.password.reset',["token"=>$token]);//'https://example.com/reset-password?token='.$token;
    
        $this->notify(new ResetPasswordNotification($url));
    }
    
    protected function sendResetLinkResponse(Request $request, $response)
    {
        // Customize the URL here
        $this->showLinkRequestResponse($request, trans($response, ['url' => route('custom.password.reset')]));
    }
    */ 
}
