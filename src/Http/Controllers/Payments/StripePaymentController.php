<?php

    

namespace iProtek\Core\Http\Controllers\Payments;

use Illuminate\Http\Request;
use Session;
use Stripe; 
use Illuminate\Routing\Controller as BaseController;
use GuzzleHttp\Client;

class StripePaymentController extends BaseController

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
    public function getPaymentInfo($chargeId)
    {
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
        
        $this->client = new Client();
        try {
            $response = $this->client->get("https://api.exchangerate.host/latest", [
                'query' => [
                    "base"=>"PHP",
                    "amount"=>$value,
                    "symbols"=>"USD"
                ]
            ]);

            $data = json_decode($response->getBody());

            return $data;
        } catch (\Exception $e) {
            // Handle errors
            return $e;
        }
    }

}