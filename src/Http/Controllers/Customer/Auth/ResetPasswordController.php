<?php

namespace iProtek\Core\Http\Controllers\Customer\Auth;

use iProtek\Core\Http\Controllers\Controller;
use iProtek\Core\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use iProtek\Core\Models\Customer;
use DB;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords; 

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::CUSTOMER;

    
    protected function guard()
    {
        return Auth::guard('customer');
    } 

    public function showResetForm(Request $request, $token)
    {
        return view('customer-auths.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    protected function reset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Add your custom logic here
        // For example, you can perform additional checks, trigger events, or update the password.
        DB::table('customer_resets')->where('email', $request->email)->delete();
        $customer = Customer::where('email', $request->email)->first();
        $customer->password = bcrypt($request->password);
        if(!$customer->is_email_confirmed){
            $customer->is_email_confirmed = 1;
            $customer->email_verified_at = \Carbon\Carbon::now();
        }
        $customer->save();

        
        return redirect()->route('customer.login')->with('error', 'Password Reset')->withErrors([
            //'email' => 'Please verify your email address.',
            'info' => 'Your password has been changed. Please login again.'
        ])->withInput($request->only('email'));
        /*
        return redirect($this->redirectPath())
            ->with('status', 'Password reset successful');
        */
    }


}
