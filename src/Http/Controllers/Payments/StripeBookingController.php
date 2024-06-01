<?php

    

namespace iProtek\Core\Http\Controllers\Payments;

use Illuminate\Http\Request;
use Session;
use Stripe; 
use Illuminate\Routing\Controller as BaseController;
use GuzzleHttp\Client;
use iProtek\Core\Models\BookingPayment;
use Illuminate\Support\Facades\URL;

class StripeBookingController extends BaseController

{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
    */

    protected $client;
    public function stripe()

    {

        return view('stripe');

    }

    

    /**

     * success response method.

     *

     * @return \Illuminate\Http\Response

     */
    /*
    public function stripePost(Request $request)

    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Charge::create ([
                "amount" => 100 * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Test payment from itsolutionstuff.com." 
        ]);
        Session::flash('success', 'Payment successful!');
        return back();
    }
    */



    public function stripePost(Request $request)
    {

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        //IF OTHER CURRENCY JUST CONVERT FROM OTHER TO USD.
        try{
            return $request;
            Stripe\Charge::create ([
                "amount" => 100,
                "currency" => "PHP",
                "source" => $request->stripeToken,
                "description" => "Test payment from mariegold" 
            ]);
        }catch(\Excepetion $ex){
            abort(403, $ex->message);
        }

        /*
        WITH SHIPPING DETAILS
        $customer = Stripe\Customer::create(array(
                "address" => [
                        "line1" => "Virani Chowk",
                        "postal_code" => "360001",
                        "city" => "Rajkot",
                        "state" => "GJ",
                        "country" => "IN",
                    ],
                "email" => "demo@gmail.com",
                "name" => "Hardik Savani",
                "source" => $request->stripeToken
            ));
        Stripe\Charge::create ([
                "amount" => 100 * 100,
                "currency" => "usd",
                "customer" => $customer->id,
                "description" => "Test payment from test",
                "shipping" => [
                "name" => "Jenny Rosen",
                "address" => [
                    "line1" => "510 Townsend St",
                    "postal_code" => "98140",
                    "city" => "San Francisco",
                    "state" => "CA",
                    "country" => "US",
                ],
            ]
        ]); 
        */
        Session::flash('success', 'Payment successful!');
        return back();

    }

    
    public function pay(Request $request, $pay_type, BookingPayment $id)
    {
        if (! $request->hasValidSignature()) {
            //abort(403,'Invalid Request');
            return ["status"=>0,"message"=>"Invalid Request"];
        }
        //abort(403, 'Valid Action');
        $usdValue = 0;
        //Check validator if payment on full and partial has been paid. 
        if($id->is_fullpaid == 1){
            return ["status"=>0, "message"=>"Already Fully Paid"];
        }
        if($pay_type == 'partial'){
            //Check if partial and full has been made has been made.
            if( $id->is_paid == 1){
                return ["status"=>0, "message"=>"Partial Already Paid"];
            } 
            $usdValue = $id->stripe_partial_usd_cost;
        }
        else if($pay_type == 'full'){
            //Check if full has been made
            if($id->is_paid == 1){
                $usdValue = $id->stripe_full_usd_cost - $id->stripe_partial_usd_cost;
            }
            else{
                $usdValue = $id->stripe_full_usd_cost;
            }
        }
        else{
            return ["status"=>0, "message"=>"Action not valid"];
        }
        $stripeAmount =ceil( $usdValue *100 );
        //return ["status"=>0,"message"=>$usdValue];

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        //IF OTHER CURRENCY JUST CONVERT FROM OTHER TO USD.
        try{
            $bookingBundle = \iProtek\Core\Models\BookingBundle::find($id->booking_bundle_id);
            $data = Stripe\Charge::create ([
                "amount" => $stripeAmount,
                "currency" => "USD",
                "source" => $request->token,
                "description" => strtoupper($pay_type)." PAY for Mariegold Booking Ref#".$bookingBundle->id 
            ]);
            //return ["message"=>json_encode($data)];
            if($data && $data->status == "succeeded"){
                $charge_id = $data->id;
                $stripe_pay_url = URL::temporarySignedRoute(
                    'payments.stripe.charge-info', now()->addMinutes(525960), [ 'chargeId'=> $charge_id ]
                );

                if($pay_type == 'partial'){
                    $id->method = "stripe";
                    $id->is_paid = 1;
                    $id->ref_url = $stripe_pay_url;
                    $id->ref_no = $charge_id;
                    $id->downpayment_at = \Carbon\Carbon::now();
                    $id->paid_amount = $id->downpayment_amount;
                    $bookingBundle->is_paid = 1;
                    $id->status = "partial";
                    $bookingBundle->save();
                    $id->save();
                }
                else if($pay_type == 'full'){
                    $charge_value = 0;
                    if($id->is_paid == 1){
                        $charge_value = $id->total_due_amount - $id->downpayment_amount;
                    }
                    else{
                        $charge_value = $id->total_due_amount;
                    }
                    $id->fullpaid_method = "stripe";
                    $id->fullpaid_amount = $charge_value;
                    $id->fullpaid_at = \Carbon\Carbon::now();
                    $id->fullpaid_ref_no = $charge_id;
                    $id->is_fullpaid = 1;
                    $id->is_paid = 1;
                    $id->status = "completed";
                    $id->fullpaid_ref_url = $stripe_pay_url;
                    $id->save();
                    $bookingBundle->is_paid = 1;
                    $bookingBundle->is_fullpaid = 1;
                    $bookingBundle->save();
                }
                
                $customerBooking = \iProtek\Core\Models\CustomerBookingList::where('booking_bundle_id', $bookingBundle->id)->first();
                if($customerBooking){                    
                    \iProtek\Core\Helpers\NotifyEmailHelper::NotifyAdminPayment($customerBooking);
                }
                return [ "status"=>1, "message"=>"Paid Successfully"];
            }
            else{
                return ["status"=>0, "message"=>"Failed"];
            }



            //return ["status"=>1, "message"=> json_encode( $data ) ];


        }catch(\Excepetion $ex){
            //abort(403, $ex->message);
            return ["status"=>0, "message"=>$ex->message];
        }
        return ["status"=>1,"message"=>"Payment Successful"];

        /*
        WITH SHIPPING DETAILS
        $customer = Stripe\Customer::create(array(
                "address" => [
                        "line1" => "Virani Chowk",
                        "postal_code" => "360001",
                        "city" => "Rajkot",
                        "state" => "GJ",
                        "country" => "IN",
                    ],
                "email" => "demo@gmail.com",
                "name" => "Hardik Savani",
                "source" => $request->stripeToken
            ));
        Stripe\Charge::create ([
                "amount" => 100 * 100,
                "currency" => "usd",
                "customer" => $customer->id,
                "description" => "Test payment from test",
                "shipping" => [
                "name" => "Jenny Rosen",
                "address" => [
                    "line1" => "510 Townsend St",
                    "postal_code" => "98140",
                    "city" => "San Francisco",
                    "state" => "CA",
                    "country" => "US",
                ],
            ]
        ]); 
        */
        //Session::flash('success', 'Payment successful!');
        //return back();

    }



    public function getPaymentInfo(Request $request, $chargeId)
    {

        if(!auth()->check()){
            if (! $request->hasValidSignature()) {
                abort(403,'Invalid Request'); 
            }
        }

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $charge = \Stripe\Charge::retrieve($chargeId);
            return $charge;
            // Access payment information
            $paymentInfo = [
                'Amount' => $charge->amount,
                'Currency' => $charge->currency,
                'Payment Status' => $charge->status,
                // Add more payment-related fields as needed
            ];
            //return $paymentInfo;
            return view('payment_info', ['paymentInfo' => $paymentInfo]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Handle any Stripe API errors here
            return redirect()->back()->with('error', 'Payment information not found.');
        }
    }

    public function getPHPtoUSD($value=1){
        //https://api.exchangerate.host/latest?base=PHP&amount=1&symbols=USD
        return \iProtek\Core\Helpers\CurlHelper::convertPHPUSD($value);

    }

}