<?php
namespace iProtek\Core\Http\Controllers\Payments;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Routing\Controller as BaseController;

class PayPalController extends BaseController
{
    private $client;


    private $cliendId = "";
    private $secretKey = "";
    public function __construct()
    {
        $this->cliendId = env("PAYPAL_CLIENT_ID","");
        $this->secretKey =  env("PAYPAL_CLIENT_SECRET","");

        $this->client = new Client([
            'base_uri' => 'https://api-m.sandbox.paypal.com/', // Use 'https://api-m.paypal.com/' for live mode
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Language' => 'en_US',
            ],
        ]);

        //SANDBOX
        //APP NAME: iprotech
        //Client ID: ARrLmqXkKpKB2M7dY1ry6kFLHwdCld1DQWGKzr-TwBpsNYd3ENqh6TC9_sCh1f83HfKibr91p5UJLtEG
        //Secret Key: ELeosDh3XQti3w3NNR4WAEi7lFmpPl-WY8FoozhpJ-6a8_hu2Y0_xng1DuNOSqWu9swLFo-EX90j95Vb
    }

    public function createPayment(Request $request, $description = "iProtech Solutions Product", $value = 10 ,$currency = "USD" )
    {
        $response = $this->client->post('v2/checkout/orders', [
            'auth' => [$this->cliendId, $this->secretKey],
            'json' => [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'description'=> $description,
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => $value
                        ],
                    ],
                ],
                "final_capture"=> true,
                'application_context' => [
                    'cancel_url' => route('apps.payments.paypal.cancel'),
                    'return_url' => route('apps.payments.paypal.execute'),
                ],
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        
        $ref_no = $data['id'];
        return [ "ref_no"=> $ref_no, "link"=> $data['links'][1]['href']];
        //var_dump($data);
        //return redirect($data['links'][1]['href']);
    }

    public function viewPayment(Request $request){

         $ref_no = $request->token ?: "6ED28032SG594935P";
         $response = $this->client->get("v2/checkout/orders/".$ref_no, [
            'auth' => [$this->cliendId, $this->secretKey]
        ]); 
        $data = json_decode($response->getBody()->getContents(), true);
        return $data;
    }

    public function executePayment(Request $request)
    { 
        
        $response = $this->client->post("v2/checkout/orders/{$request->token}/capture", [
            'auth' => [$this->cliendId, $this->secretKey],
            'json' => [
                'payer_id' => $request->payerID,
            ],
        ]); 

        $data = json_decode($response->getBody()->getContents(), true);
        return $data;
        //var_dump($data);
        //print_r($data);
        // Handle successful payment
        //return null;
        //return redirect()->route('apps.payments.paypal.success')->with('success', 'Payment completed successfully!');
    }

    public function cancelPayment(Request $request)
    {
        // Handle canceled payment
        return redirect('/apps');
        /*return [
            "status"=>0,
            "data"=>$request,
            "message"=>"Cancelled"
        ];*/

        //return redirect()->back()->with('error', 'Payment canceled.');
    }

    public function paymentSuccess()
    {
        // Display success message or perform further actions

        return view('payment.success');
    }
}