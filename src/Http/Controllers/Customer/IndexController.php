<?php

namespace iProtek\Core\Http\Controllers\Customer;

use iProtek\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use DB;
use Illuminate\Support\Facades\Hash;
use iProtek\Core\Models\AppLicense;
use Illuminate\Support\Facades\URL;
use iProtek\Core\Models\CustomerBookingList;

class IndexController extends Controller
{
    //
    public function index(Request $request){

        $retval = $request->all();

        if(!auth('customer')->check()){
            return $this->login($request);
        }

        return view('customer.index', [ "selection"=>$retval ]);
    }

    public function login(Request $request){
        if(auth('customer')->check()){
            return $this->index($request);
        }
        return view('customer-auths.login');
    }
    public function register(Request $request){
        if(auth('customer')->check()){
            return $this->index($request);
        }
        return view('customer-auths.register');
    }

    public function booking_requests(Request $request){
        $customer = auth('customer')->user();
        $customerRequests = CustomerBookingList::where('customer_id', $customer->id );
        $customerRequests->with(['booking_bundle'=>function($q){
            $q->with('invoice');
        }]);
        $customerRequests->orderBy('date_from', 'DESC');
        return $customerRequests->get();
    }

    public function customer_user_password_check(Request $request){
        
        try {

            if(!auth('customer')->check()){
                $errors = [
                    'login' => ['Login Required.'],
                ];
                throw ValidationException::withMessages($errors);
            }
            $app_user_id = auth('customer')->user()->id;


            $validator = Validator::make($request->all(), [
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $password = $request->input('password');
            $hashedPassword = DB::select('SELECT id, `password` FROM app_users WHERE id=?',[$app_user_id])[0]->password;//'hashed_password_stored_in_database';

            if (Hash::check($password, $hashedPassword)) {
                // Password is correct, proceed with login logic
                // ...
                // Return a success response if login is successful
                return response()->json(['message' => 'Login successful']);
            } else {
                // Password is incorrect, return a validation error response
                $errors = [
                    'password' => ['Wrong password'],
                ];

                throw ValidationException::withMessages($errors);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function app_user_check(){
        if(auth('app_user')->check()){
            return [
                "status"=>1,
                "data"=>[],
                "message"=>"User has login."
            ];
        }
        return ["status"=>0, "data"=>[], "message"=>"Login Required."];
    }

    public function view_license(Request $request, AppLicense $id ){
        $isValid = $request->hasValidSignature();
        if (!$isValid) {
            // The URL in the request has a valid signature
            abort(403, "Forbidden access");
        }
        if($id->app_user_id != auth('app_user')->user()->id){
            abort(403, "Unauthorized access");
        }
        
        return view('apps.view-purchase-license', ["license"=> $id]);
    }


}
