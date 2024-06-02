<?php

namespace iProtek\Core\Http\Controllers\Customer\Auth;

use iProtek\Core\Http\Controllers\Controller;
use iProtek\Core\Providers\RouteServiceProvider;
use iProtek\Core\Models\Auths\Customer;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use iProtek\Core\Http\Controllers\_Common\_CommonController;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    //use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::APPS;

    /**
     * Create a new controller instance.
     *
     * @return void
     */ 
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            //'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customers,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'first_name' =>['required'],
            'last_name' => ['required'],
            //'company' => ['required'],
            //'contact_no' => ['required']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {


        //$new_account_no = DB::select(" SELECT  ( IF( IFNULL(max(account_no * 1), 10000) < 10000, 10000, max(account_no * 1)) +1) as new_account_no FROM app_users WHERE account_no IS NOT NULL " )[0]->new_account_no;

        return Customer::create([
            //'account_no' =>$new_account_no,
            'name' => $data['first_name']." ".$data['last_name'],
            'email' => $data['email'],
            "username"=>$data['email'],
            'password' => Hash::make($data['password']),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'gender' => $data['gender'],
            'address' => $data['address'],
            'contact_no' => $data['contact_no'],
            'created_by' => "0"
        ]);
    }

    
    protected function guard()
    {
        return Auth::guard('customer');
    }

        /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
 
        $regUser = null;
        event( new Registered($user = $this->create($request->all())));
         
        $user->sendEmailVerificationNotification(); 
        
        return redirect()->route('customer.login')->with('error', 'Please verify your email address.')->withErrors([
            'verify_email' => 'Email verification sent to your email: '.$request->email,
        ])->withInput($request->only('email')); 
 
    }

    public function app_user_register(Request $request){
        $comm = new _CommonController;
        $comm->validate($request, [
            "email"     =>'required|string|email|unique:customers,email|max:100',
            'password'  => 'required|string|min:8|confirmed',
            'first_name'=>'required',
            'last_name' =>'required'
        ]);

        event(new Registered($user = $this->create($request->all())));

        return ['status'=>1, 'data'=>["email"=>$request->email,"info"=>"Please verify your email, verification link sent to your email."], 'message'=>'Successfully Registered.'];

    }


}
