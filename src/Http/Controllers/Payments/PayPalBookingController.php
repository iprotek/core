<?php
namespace iProtek\Core\Http\Controllers\Payments;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use iProtek\Core\Http\Controllers\_Common\_CommonController;
use iProtek\Core\Models\BookingBundle;
use iProtek\Core\Models\Customer;
use Illuminate\Support\Facades\URL;
use iProtek\Core\Models\BookingPayment;
use iProtek\Core\Models\PaymentRefund;
use iProtek\Core\Models\Invoice;
use iProtek\Core\Models\InvoicePayment;

class PayPalBookingController extends _CommonController
{
    private $client;


    private $cliendId = "";
    private $secretKey = ""; 
    private $is_sandbox = false;
    public function __construct()
    { 
        //BUSINESS SANDBOX
        if($this->is_sandbox){
            $api_url = 'https://api.sandbox.paypal.com/';
            $this->cliendId = env("PAYPAL_CLIENT_ID_SANDBOX","");
            $this->secretKey = env("PAYPAL_CLIENT_SECRET_SANDBOX","");
        }

        //ACTUAL
        else{
            $api_url = 'https://api-m.paypal.com/';
            $this->cliendId =  env("PAYPAL_CLIENT_ID","");
            $this->secretKey =  env("PAYPAL_CLIENT_SECRET","");
        } 



        $this->client = new Client([
            'base_uri' => $api_url,//'https://api.sandbox.paypal.com/', // Use 'https://api-m.paypal.com/' for live mode
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Language' => 'en_US',
            ],
        ]);
 
    }

    public function createPayment(Request $request,  $pay_type, BookingBundle  $id, $debitCredit = false  )
    {
        $bookingBundle = BookingBundle::with(['payment'])->find($id->id);
        $payment = $bookingBundle->payment;
        if(!$payment){
            abort(403, 'No Booking Available');
        }
        $currency = $bookingBundle->currency;

       

        if($pay_type == 'partial' && !$payment->downpayment_at){
            $value = $payment->downpayment_amount;
        }
        else if($pay_type == 'full' && !$payment->fullpaid_at){
            
            //FULL PAY DEDUCTED FROM PARTIAL
            if($payment->downpayment_at)
                $value =  ($bookingBundle->payment_total_due *1) - ($payment->downpayment_amount * 1);
            
            //FULL PAY
            else
                $value = $bookingBundle->payment_total_due;
        }
        else{
            abort(403, 'Process invalidated');
        }

        if( $id->package_id == 0 ){
            $description = strtoupper( $pay_type )." Room Booking Ref#".$bookingBundle->id;
        }
        else{
            $description = strtoupper( $pay_type )." Package Booking Ref#".$bookingBundle->id;
        }

        //$description = "iProtech Solutions Product";
        //$value = 10; 
        //$currency = "USD";
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
                        'invoice_id' => ($pay_type == 'full' ? 'F':'P').$bookingBundle->id,
                    ],
                ],
                "final_capture"=> true,
                'application_context' => [
                    'cancel_url' => route('payments.paypal-booking.cancel'),
                    'return_url' => route('payments.paypal-booking.execute',[ "pay_type"=>$pay_type, "id" => $request->id, "customer_id" => $id->customer_id ]),
                ],
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        
        $ref_no = $data['id'];
        //return [ "ref_no"=> $ref_no, "link"=> $data['links'][1]['href']];
        //var_dump($data);
        //return $data;
        $url = "";
        //if($debitCredit){            
        if($this->is_sandbox){
            $url = 'https://www.sandbox.paypal.com/checkoutweb/signup?token='.$ref_no.'&useraction=CONTINUE';
        }
        else
            $url = 'https://www.paypal.com/checkoutweb/signup?token='.$ref_no.'&useraction=CONTINUE';
        //}
        //else $url = 'https://www.sandbox.paypal.com/checkoutweb/signup?token='.$ref_no.'&useraction=CONTINUE';
        //return redirect($data['links'][1]['href']);
        return redirect($url);
    }
    
    public function createPaymentCreditDebit(Request $request,  $pay_type, BookingBundle  $id  )
    {
       return $this->createPayment($request, $pay_type, $id, true);
    }
    

    public function viewPayment(Request $request, $ref_no){

        //$ref_no = $request->token ?: "6ED28032SG594935P";
        if(!auth()->check()){
            if (! $request->hasValidSignature()) {
                abort(403,'Invalid Request');
            }
        }

        $response = $this->client->get("v2/checkout/orders/".$ref_no, [
            'auth' => [$this->cliendId, $this->secretKey]
        ]); 
        $data = json_decode($response->getBody()->getContents(), true);
        return $data;
    }

    public function executePayment(Request $request, $pay_type, Customer $customer_id , BookingBundle $id)
    { 
        if($id->customer_id != $customer_id->id){
            abort(403, "Payment failed.");
        }
        $bookingBundle = BookingBundle::with(['payment'])->find($id->id);
        $payment = $bookingBundle->payment;
        if(!$payment){
            abort(403, "Unable to accept system got error");
        }

        //Check if the payment already done.
        if(($pay_type == 'partial' && $bookingBundle->is_paid == 1) || $bookingBundle->is_fullpaid == 1){
            abort(403, "Unable to accept payment due to error");
        }
        else if( ($pay_type == 'partial' && $payment->is_paid == 1) || $payment->is_fullpaid == 1){
            abort(403, "Unable to accept payment due to error(2)");
        }
        
        $response = $this->client->post("v2/checkout/orders/{$request->token}/capture", [
            'auth' => [$this->cliendId, $this->secretKey],
            'json' => [
                'payer_id' => $request->payerID,
            ],
        ]); 

        //$

        $data = json_decode($response->getBody()->getContents(), true);
        //$data['status']
        if($data){
            $status = trim(strtoupper($data['status']));
            if($status == "COMPLETED"){

                
                //generate confirmation link
                $ref_url = URL::temporarySignedRoute(
                    'payments.paypal-booking.view-payment', now()->addMinutes(525960), [ 'ref_no'=> $request->token ]
                );

                if(($bookingBundle->payment_total_due * 1)  == ($payment->downpayment_amount* 1)){
                    $bookingBundle->is_paid = 1;
                    $bookingBundle->is_fullpaid = 1;
                    //$payment->downpayment_at = \Carbon\Carbon::now();
                    //$payment->is_paid = 1;
                    //$payment->ref_no = $request->token;
                    //$payment->ref_url = $ref_url;
                    //$payment->method = "paypal";
                    $payment->fullpaid_at = \Carbon\Carbon::now();
                    $payment->fullpaid_amount = $bookingBundle->payment_total_due;
                    $payment->fullpaid_ref_no = $request->token;
                    $payment->fullpaid_method = "paypal"; 
                    $payment->fullpaid_ref_url = $ref_url;
                    $payment->is_fullpaid = 1;
                    $payment->status = "completed";
                }
                else if($pay_type == 'partial'){
                    $bookingBundle->is_paid = 1;
                    $payment->downpayment_at = \Carbon\Carbon::now();
                    $payment->paid_amount = $payment->downpayment_amount;
                    $payment->is_paid = 1;
                    $payment->ref_no = $request->token;
                    $payment->ref_url = $ref_url;
                    $payment->method = "paypal";
                }
                else if($pay_type == 'full'){
                    $bookingBundle->is_fullpaid = 1;
                    $payment->fullpaid_method = "paypal";
                    $payment->fullpaid_at = \Carbon\Carbon::now();
                    $payment->fullpaid_ref_url = $ref_url;
                    $payment->is_fullpaid = 1;
                    
                    if($payment->downpayment_at)
                        $payment->fullpaid_amount = ($bookingBundle->payment_total_due *1) - ($payment->downpayment_amount * 1);
                    else{
                        $payment->fullpaid_amount = $bookingBundle->payment_total_due;
                        $payment->downpayment_amount = 0;
                    }

                    $payment->fullpaid_ref_no = $request->token;
                    $payment->fullpaid_method = "paypal";
                    $payment->status = "completed";

                }
                $bookingBundle->save();
                $payment->save();


                \iProtek\Core\Helpers\InvoiceHelper::updateInvoice($bookingBundle);
                //Notify payment here

        
                $customerBooking = \iProtek\Core\Models\CustomerBookingList::where('booking_bundle_id', $bookingBundle->id)->first();
                if($customerBooking){
                    
                    \iProtek\Core\Helpers\NotifyEmailHelper::NotifyAdminPayment($customerBooking);
                    return redirect($customerBooking->confirmation_link);
                }
            }
            else{
                ?>
                <h3><?=$status?>.</h3>
                    <a href="<?=$customerBooking->confirmation_link?>">Return</a>
                <?php
            }
        }
        else{?>
            <h3>Something goes wrong</h3>
            <a href="<?=$customerBooking->confirmation_link?>">Return</a>
        <?php
        }
        //return $data;
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

    public function refund(Request $request, $pay_type, BookingPayment $id){
        if(!auth()->check()){
            abort(404);
            return;
        }
        if(auth()->user()->id != 1){
            abort(403, 'Only Superuser is allowed to refund');
        }
        $this->validate($request, [ 
            "refund_value" =>'required|numeric|gt:0',
            "refund_reason" => "required|min:20" 
        ]); 
        $bookingBundle = BookingBundle::find($id->booking_bundle_id);
        $ref_no = "";
        $paid_value = 0;
        $refund_value = $request->refund_value;
        $refund_reason = $request->refund_reason;
        if($pay_type == 'partial' &&  $id->method == 'paypal' && $id->is_paid == 1){
            if($id->is_partial_refunded == 1){
                abort(403, 'Already Refunded');
                return;
            }
            $paid_value = $id->paid_amount;
            $ref_no = $id->ref_no;
        }
        else if($pay_type == 'full' && $id->method == 'paypal' && $id->is_fullpaid == 1){
            if($id->is_full_refunded == 1){
                abort(403, 'Already Refunded');
                return;
            }
            $ref_no = $id->fullpaid_ref_no;
            $paid_value = $id->fullpaid_amount;
        }
        else{
            abort(403, 'Not Supported');
            return;
        }
        if($refund_value*1 > $paid_value *1){
            abort(403, 'Refund value is more than paid value');
            return;
        }

        //Get the capture ID
        $payment_info = $this->viewPayment($request, $ref_no);
        //return ["status"=>0, "message"=> $payment_info['purchase_units'][0]['payments']['captures'][0]['id'] ];
        //['purchase_units'][0]['payments']['captures'][0]['id']
        $capture_id = $payment_info['purchase_units'][0]['payments']['captures'][0]['id'];

        $response = $this->client->post('v2/payments/captures/'.$capture_id.'/refund', [
            'auth' => [$this->cliendId, $this->secretKey],
            'json' => [
                'amount' => [
                    'value' => $refund_value, // Adjust the refund amount as needed
                    'currency_code' => $bookingBundle->currency, // Change to your preferred currency
                ],
                'note_to_payer' => $refund_reason,
            ]
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);
        // Check if the refund was successful
        if ($responseData['status'] === 'COMPLETED') {
            //update refund
            if($pay_type == 'partial'){
                $id->is_partial_refunded = 1;
                $id->partial_refund_by = auth()->user()->id;
                $id->partial_refund_value = $refund_value;
                $id->partial_refund_at = \Carbon\Carbon::now();
                $id->partial_refund_reason = $refund_reason;
            }
            else{
                
                $id->is_full_refunded = 1;
                $id->full_refund_by = auth()->user()->id;
                $id->full_refund_value = $refund_value;
                $id->full_refund_at = \Carbon\Carbon::now();
                $id->full_refund_reason = $refund_reason;
            }
            $id->save();
            
            return ["status"=>1, "message"=> 'Refund was successfully processed.'];
        } else {
            return ["status"=>0, "message"=> $responseData['status']];
        }

    }

    public function refund_details(Request $request, $pay_type, BookingPayment $id){
        if(!auth()->check()){
            abort(403,'Unauthorized');
            return;
        }
        $ref_no = ""; 
        if($pay_type == 'partial' &&  $id->method == 'paypal' && $id->is_paid == 1){ 
            $ref_no = $id->ref_no;
        }
        else if($pay_type == 'full' && $id->method == 'paypal' && $id->is_fullpaid == 1){ 
            $ref_no = $id->fullpaid_ref_no; 
        }
        else{
            abort(403, 'Not found');
        }

        try{
            $payment_info = $this->viewPayment($request, $ref_no);
            $refunds = $payment_info['purchase_units'][0]['payments']['refunds'];//[0]['id'];
            $refund_info = [];
            if($pay_type == 'partial'){
                $refund_info = [
                    "refund_by"=>$id->partial_refund_by,
                    "refund_value"=>$id->partial_refund_value,
                    "refund_at"=>$id->partial_refund_at,
                    "refund_reason"=>$id->partial_refund_reason
                ];
            }
            else if($pay_type == 'full'){
                $refund_info = [
                    "refund_by"=>$id->full_refund_by,
                    "refund_value"=>$id->full_refund_value,
                    "refund_at"=>$id->full_refund_at,
                    "refund_reason"=>$id->full_refund_reason
                ];            
            }
            return [
                "details"=>$refund_info,
                "paypal_refunds"=> $refunds 
            ];
            /*
            $response = $this->client->get('v2/payments/refunds/'.$refund_id, [
                'auth' => [$this->cliendId, $this->secretKey],
            ]);
            $responseData = json_decode($response->getBody()->getContents(), true);
            return $responseData;
            */
        }catch(\Exception $ex){
            abort(403,'Error');
        }
    }
 

    public function refunded(Request $request, $is_sandbox){
        // Ensure this script only responds to POST requests
        $headers = getallheaders();
        //if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Read the JSON data from the request body
        $data = json_decode(file_get_contents("php://input"), true);

        // Process the event data and handle the refund event
        // Implement your logic here
        PaymentRefund::create([
            "name"=>"paypal",
            "is_sandbox"=> $is_sandbox,
            "data" => json_encode($data)
        ]);
        if($data['resource_type'] == 'refund'){
            $invoice_id = $data['resource']['invoice_id'];
            $refundsInfo = $data['resource']['seller_payable_breakdown']['total_refunded_amount'];
            $pay_type = substr($invoice_id,0,1) == 'P'? 'partial':'full';
            $booking_payment_id = substr($invoice_id, 1);
            $pref = trim( strtoupper( substr($invoice_id, 0, 1) ) );
            if($booking_payment_id && ( $pref == 'P' || $pref == 'F') ){
                $payment = BookingPayment::find($booking_payment_id);
                if($payment){
                    $ref_no = '';
                    $refund_value = $refundsInfo['currency_code']." ".$refundsInfo['value'];
                    if($pay_type == 'partial'){
                        $ref_no = $payment->ref_no; 
                    }
                    else if($pay_type == 'full'){
                        $ref_no = $payment->fullpaid_ref_no;
                    }
                    $paypalInfo = $this->viewPayment($request, $ref_no);
                    $paypalUserEmail = $paypalInfo['payment_source']['paypal']['email'];
                    $paypalFirstName = $paypalInfo['payment_source']['paypal']['name']['given_name'];
                    $paypalLastName = $paypalInfo['payment_source']['paypal']['name']['surname'];
                    
                    if($payment->is_partial_refunded != 1 && $pay_type == 'partial'){                        
                        $id->is_partial_refunded = 1;
                        $id->partial_refund_by = 0;
                        $id->partial_refund_value = $refundsInfo['value'];
                        $id->partial_refund_at = \Carbon\Carbon::now();
                        $id->partial_refund_reason = $data['resource']['note_to_payer'] ?: "Paid from paypal portal";
                        $id->save();
                    }
                    else if($payment->is_full_refunded != 1 && $pay_type == 'full'){                        
                        $id->is_full_refunded = 1;
                        $id->full_refund_by = 0;
                        $id->full_refund_value = $refundsInfo['value'];
                        $id->full_refund_at = \Carbon\Carbon::now();
                        $id->full_refund_reason = $data['resource']['note_to_payer'] ?: "Paid from paypal portal";
                        $id->save();
                    }
                    
                    //SEND A NOTIFICATION TO THE CUSTOMER AND ADMINS FOR REFUNDING.
                    \iProtek\Core\Helpers\NotifyEmailHelper::NotifyRefunding($payment, $pay_type, $paypalUserEmail, $paypalFirstName, $paypalLastName, $refundsInfo['currency_code'], $refundsInfo['value']);
                        
                }
            }
            else if($pref == 'M'){
                //InvoicePayment::find()
                $number_split = explode("-",$booking_payment_id);
                $booking_bundle_id =  $number_split[0];
                $invoice_payment_id = $number_split[1];
                $refund_value =  $refundsInfo['value'];;
                $invoice_payment = InvoicePayment::find($invoice_payment_id);
                //$invoice_payment->refund_paid_at = \Carbon\Carbon::now();
                $invoice_payment->refund_at = \Carbon\Carbon::now(); 
                $invoice_payment->refund_value = $refund_value;
                $invoice_payment->save();
                //add refund trans
                $refund = InvoicePayment::where('ref_no', $invoice_payment->ref_no)->whereRaw(' amount < ? ',["0"] )->first();
                if(!$refund){
                    $refund = InvoicePayment::create([
                        "invoice_id"=>$invoice_payment->invoice_id,
                        "is_auto"=>0,
                        "method"=>"REFUNDED ON PAYPAL",
                        "paid_at"=>\Carbon\Carbon::now(),
                        "amount"=>($refund_value*-1),
                        "note"=>"Refunded on paypal",
                        "created_by"=>0,
                        "is_online"=>1,
                        "refunded_paid_at"=>\Carbon\Carbon::now()
                    ]);
                }
                else{
                    $refund->method ="REFUNDED ON PAYPAL";
                    $refund->is_online = 1;
                    $refund->amount = ($refund_value*-1);
                    $refund->save();
                }
                
                $bookingBundle = BookingBundle::find($booking_bundle_id);
                \iProtek\Core\Helpers\InvoiceHelper::updateInvoice($bookingBundle);


            }
        }

    }

    public function payCardOnly(Request $request, $pay_type, BookingPayment $id){
        
        if (! $request->hasValidSignature()) {
            //abort(403,'Invalid Request');
            return ["status"=>0,"message"=>"Invalid Request"];
        }

        // Set up the API credentials for the Sandbox environment
        $apiUsername = 'sb-tsdov27631943_api1.business.example.com';
        $apiPassword = 'H8RTH5DVACGUJPBD';
        $apiSignature = 'AXBLrVJmA-PFtzNaYXEgSK0UIgknAua8OwhwsW0afSAd97o5SYJby2gQ';

        // Set up the API endpoint for the Sandbox environment
        $apiEndpoint = 'https://api-3t.sandbox.paypal.com/nvp';

        // Set up the payment details
        $paymentAmount = '10.00';
        $paymentCurrency = 'USD';

        // Set up the card details
        $cardNumber = $request->number ;//'CARD_NUMBER';
        $cardExpireMonth = $request->exp_month;//'CARD_EXPIRATION_MONTH';
        $cardExpireYear = $request->exp_year;//'CARD_EXPIRATION_YEAR';
        $cardCVV = $request->cvc;//'CARD_CVV';

        // Set up the API request parameters
        $requestParams = array(
            'METHOD' => 'SetExpressCheckout',
            'USER' => $apiUsername,
            'PWD' => $apiPassword,
            'SIGNATURE' => $apiSignature,
            'VERSION' => '204.0',
            'PAYMENTREQUEST_0_AMT' => $paymentAmount,
            'PAYMENTREQUEST_0_CURRENCYCODE' => $paymentCurrency,
            'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
            'PAYMENTREQUEST_0_ALLOWEDPAYMENTMETHOD' => 'InstantPaymentOnly',
            'RETURNURL' => 'http://example.com/success',
            'CANCELURL' => 'http://example.com/cancel',
            'BUTTONSOURCE' => 'PP-ECWizard',
            'SOLUTIONTYPE' => 'Sole',
            'L_PAYMENTREQUEST_0_NAME0' => 'Product Name',
            'L_PAYMENTREQUEST_0_DESC0' => 'Product Description',
            'L_PAYMENTREQUEST_0_AMT0' => $paymentAmount,
            'L_PAYMENTREQUEST_0_QTY0' => '1',
            'CREDITCARDTYPE' => 'VISA',
            'ACCT' => $cardNumber,
            'EXPDATE' => $cardExpireMonth . $cardExpireYear,
            'CVV2' => $cardCVV,
        );

        // Send the API request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiEndpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestParams));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // Process the API response
        $responseArray = array();
        parse_str($response, $responseArray);

        // Check if the API call was successful
        if ($responseArray['ACK'] == 'Success') {
            // Redirect the user to the PayPal Sandbox for payment
            $sandboxPaypalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $responseArray['TOKEN'];
            $sandboxPaypalURL = 'https://www.sandbox.paypal.com/checkoutweb/signup?flow[0]=1-P&flow[1]=1-P&token='.$responseArray['TOKEN'];
            return ["status"=>1, "message"=> $sandboxPaypalURL];
            //header('Location: ' . $sandboxPaypalURL);
        } else {
            // Handle API call failure
            return ["status"=>1, "message"=>$responseArray['L_LONGMESSAGE0']];
            //echo 'Error: ' . $responseArray['L_LONGMESSAGE0'];
        }

        
        /*

                // Set your PayPal credentials
                $paypalClientId =  $this->cliendId;
                $paypalSecret = $this->secretKey;

                // Set the payment details
                $paymentAmount = '10.00'; // Amount of payment
                $currencyCode = 'USD'; // Currency code

                // Set the return and cancel URLs
                $returnUrl = 'https://yourwebsite.com/checkout/success'; // URL to redirect after successful payment
                $cancelUrl = 'https://yourwebsite.com/checkout/cancel'; // URL to redirect if payment is canceled

                // Build the API request
                $requestParams = array(
                    'intent' => 'sale',
                    'payer' => array(
                        'payment_method' => 'credit_card',
                        'funding_instruments' => array(
                            array(
                                'credit_card' => array(
                                    'number' => '4032033355058384', // Replace with the test credit card number
                                    'type' => 'Visa', // Replace with the card type (e.g., visa, mastercard, etc.)
                                    'expire_month' => '10', // Replace with the card's expiration month
                                    'expire_year' => '2028', // Replace with the card's expiration year
                                    'cvv2' => '123', // Replace with the card's CVV
                                    'first_name' => 'John', // Replace with the customer's first name
                                    'last_name' => 'Doe' // Replace with the customer's last name
                                )
                            )
                        )
                    ),
                    'transactions' => array(
                        array(
                            'amount' => array(
                                'total' => $paymentAmount,
                                'currency' => $currencyCode
                            )
                        )
                    ),
                    'redirect_urls' => array(
                        'return_url' => $returnUrl,
                        'cancel_url' => $cancelUrl
                    )
                );

                // Convert the request parameters to JSON
                $requestJson = json_encode($requestParams);

                // Send the API request to PayPal
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v2/payments/payment');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $requestJson);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Authorization: Basic ' . base64_encode($paypalClientId . ':' . $paypalSecret)
                ));

                $responseJson = curl_exec($ch);
                curl_close($ch);

                // Process the API response
                $response = json_decode($responseJson, true);

                if (isset($response['id'])) {
                    // Redirect the user to PayPal for payment
                    $paypalUrl = 'https://www.paypal.com/checkoutnow?token=' . $response['id'];
                    //header('Location: ' . $paypalUrl);
                    return ["status"=>0, "message"=>$response['id']];
                } else {
                    // Handle the error
                    return ["status"=>0, "message"=> json_encode( $response)];
                    //echo 'Error: ' . $response['message'];
                } 
        */

        $clientId = $this->cliendId;
        $clientSecret = $this->secretKey;

        // Initialize PayPal API context
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential($clientId, $clientSecret)
        );
        //$apiContext->setConfig([
        //    'mode' => 'live'
        //]);

        $card_type = $this->getCardType($request->number);
        $card = $this->getCard($request->number);

        $name_on_card = $request->name_on_card;
        // Create a payment
        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale')
            ->setPayer(
                new \PayPal\Api\Payer([
                    'payment_method' => "credit_card",
                    'funding_instruments' => [
                        new \PayPal\Api\FundingInstrument([
                            "credit_card" => [
                                'number' => $request->number,
                                'type' => "visa", // visa or 'mastercard', 'amex', etc.
                                'expire_month' => $request->exp_month,
                                'expire_year' => $request->exp_year,
                                'cvv2' => $request->cvc, // Replace with the card's CVV
                                'first_name' => 'John', // Replace with the customer's first name
                                'last_name' => 'Doe' // Replace with the customer's last name
                            ]
                        ])
                    ]
                ])
            )
            ->setTransactions([
                new \PayPal\Api\Transaction([
                    'amount' => [
                        'total' => '0.10', // Your payment amount
                        'currency' => 'USD'
                    ]
                ])
            ]);

        // Execute the payment
        try {
            $payment->create($apiContext);
            return ["status"=>1,"message"=>"Process Successful"];
            //echo "Payment successful!";
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            //echo "Error: " . $ex->getData();
            //abort(403, json_encode( $ex));
            //error_log("PayPal Connection Exception: " . $ex->getMessage());
            $error = json_decode($ex->getData());

            return ["status"=>0,"message"=>  ( $error && $error->name ? $error->name  : json_encode($ex))."XM: ".$request->exp_month. " XY: ".$request->exp_year. " NUMBER: ".$request->number." CVV: ".$request->cvc];
        }
    }

    public function paymentSuccess()
    {
        // Display success message or perform further actions

        return view('payment.success');
    }

    public function createInvoicePayment(Request $request, Invoice $invoice_id ){
        /*
        $this->is_sandbox = true;

        if($this->is_sandbox){
            $this->cliendId = 'ARrLmqXkKpKB2M7dY1ry6kFLHwdCld1DQWGKzr-TwBpsNYd3ENqh6TC9_sCh1f83HfKibr91p5UJLtEG';
            $this->secretKey = 'ELeosDh3XQti3w3NNR4WAEi7lFmpPl-WY8FoozhpJ-6a8_hu2Y0_xng1DuNOSqWu9swLFo-EX90j95Vb';
            $api_url = 'https://api.sandbox.paypal.com/';

            $this->client = new Client([
                'base_uri' => $api_url,//'https://api.sandbox.paypal.com/', // Use 'https://api-m.paypal.com/' for live mode
                'headers' => [
                    'Accept' => 'application/json',
                    'Accept-Language' => 'en_US',
                ],
            ]);
        }
        */
        $invoice = $invoice_id;
        $payables = $invoice->total_payable;
        if(($invoice->total_payable*1) <= 0){
            abort(403, "Has no payables");
        }
        $booking_bundle_id = $invoice_id->booking_bundle_id;
        $user_id = 0;
        if(auth()->check()){
            $user_id = auth()->user()->id;
        }

        $invoice_payment = InvoicePayment::create([
            "method"=>"paypal",
            "paid_at"=>\Carbon\Carbon::now(),
            "amount"=>"0",
            "ref_no"=>"N/A",
            "note"=>"Paying via paypal",
            "created_by"=>$user_id,
            "invoice_id"=>$invoice->id,
            "is_auto"=>0,
            "is_online"=>1,
            "to_pay"=>$payables
        ]);

        $response = $this->client->post('v2/checkout/orders', [
            'auth' => [$this->cliendId, $this->secretKey],
            'json' => [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'description'=> "Booking Payment #". $booking_bundle_id,
                        'amount' => [
                            'currency_code' => "PHP",
                            'value' => $payables
                        ],
                        'invoice_id' => "M".$invoice_id->booking_bundle_id."-".$invoice_payment->id,
                    ],
                ],
                "final_capture"=> true,
                'application_context' => [
                    'return_url' => route('payments.paypal-booking.execute-invoice',["invoice_id"=>$invoice_id->id, "invoice_payment_id"=>$invoice_payment->id]), 
                    'cancel_url' => route('paypal.cancel'),//route('payments.paypal-booking.cancel'),
               ],
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        //return $data;
        $ref_no = $data['id'];        
        $invoice_payment->ref_no = $ref_no;
        $invoice_payment->save();
        
        if($this->is_sandbox){
            return redirect('https://www.sandbox.paypal.com/checkoutweb/signup?token='.$ref_no.'&useraction=CONTINUE');
        }
        return redirect('https://www.paypal.com/checkoutweb/signup?token='.$ref_no.'&useraction=CONTINUE');
       
    }

    public function invoiceExecutePayment(Request $request, Invoice $invoice_id, InvoicePayment $invoice_payment_id ){
       
        if(!($invoice_id->id == $invoice_payment_id->invoice_id && $invoice_payment_id->ref_no == $request->token) )
        {
            abort(403, "Processing Invalidated");
        } 
        /*
        $this->is_sandbox = true;
        if($this->is_sandbox){
            $this->cliendId = 'ARrLmqXkKpKB2M7dY1ry6kFLHwdCld1DQWGKzr-TwBpsNYd3ENqh6TC9_sCh1f83HfKibr91p5UJLtEG';
            $this->secretKey = 'ELeosDh3XQti3w3NNR4WAEi7lFmpPl-WY8FoozhpJ-6a8_hu2Y0_xng1DuNOSqWu9swLFo-EX90j95Vb';
            $api_url = 'https://api.sandbox.paypal.com/';

            $this->client = new Client([
                'base_uri' => $api_url,//'https://api.sandbox.paypal.com/', // Use 'https://api-m.paypal.com/' for live mode
                'headers' => [
                    'Accept' => 'application/json',
                    'Accept-Language' => 'en_US',
                ],
            ]);
        }
        //return $request->getContent();
        */
        
        $response = $this->client->post("v2/checkout/orders/{$request->token}/capture", [
            'auth' => [$this->cliendId, $this->secretKey],
            'json' => [
                'payer_id' => $request->payerID,
            ],
        ]);
       
        
        $data = json_decode($response->getBody()->getContents(), true);
        //$data['status']
        if($data){
                
            $return_link = "";
            $bookingBundle = BookingBundle::find($invoice_id->booking_bundle_id);
            if(auth()->check()){
                $return_link = route("manage.bookings.view-details",["id"=>$invoice_id->booking_bundle_id]);
            }
            else{
                $customerBooking = \iProtek\Core\Models\CustomerBookingList::where('booking_bundle_id', $bookingBundle->id)->first();
                if($customerBooking){
                    $return_link = $customerBooking->confirmation_link;
                }
            }
            $status = trim(strtoupper($data['status']));
            if($status == "COMPLETED"){
                $invoice_payment_id->paid_at = \Carbon\Carbon::now();
                $invoice_payment_id->amount = $invoice_payment_id->to_pay;
                $invoice_payment_id->save();

                \iProtek\Core\Helpers\InvoiceHelper::updateInvoice($bookingBundle);
            
                ?>
                    <h3>Successfully Paid.</h3>
                    <a href="<?=$return_link?>">Return</a>
                <?php
            }
            else{ 
                echo "<h3>".$data['status']."</h3>";
                ?>
                <a href="<?=$return_link?>">Return</a>
                <?php
            }
        }
        
    }

    public function refundInvoice(Request $request, Invoice $invoice_id, InvoicePayment $invoice_payment_id){
       
        if(!($invoice_id->id == $invoice_payment_id->invoice_id ) )
        {
            abort(403, "Processing Invalidated");
        }
       
        if(!auth()->check()){
            abort(404);
            return;
        }
        if(auth()->user()->id != 1){
            abort(403, 'Only Superuser is allowed to refund');
        }
        $this->validate($request, [ 
            "refund_value" =>'required|numeric|gt:0',
            "refund_reason" => "required|min:20" 
        ]); 

        /*
        $this->is_sandbox = true;
        if($this->is_sandbox){
            $this->cliendId = 'ARrLmqXkKpKB2M7dY1ry6kFLHwdCld1DQWGKzr-TwBpsNYd3ENqh6TC9_sCh1f83HfKibr91p5UJLtEG';
            $this->secretKey = 'ELeosDh3XQti3w3NNR4WAEi7lFmpPl-WY8FoozhpJ-6a8_hu2Y0_xng1DuNOSqWu9swLFo-EX90j95Vb';
            $api_url = 'https://api.sandbox.paypal.com/';

            $this->client = new Client([
                'base_uri' => $api_url,//'https://api.sandbox.paypal.com/', // Use 'https://api-m.paypal.com/' for live mode
                'headers' => [
                    'Accept' => 'application/json',
                    'Accept-Language' => 'en_US',
                ],
            ]);
        }
        */




        $refund_value = $request->refund_value;
        $refund_reason =  $request->refund_reason;
        if($refund_value > $invoice_payment_id->amount ){
            abort(403, "Greater refund amount is invalidated");
        }
        $ref_no = $invoice_payment_id->ref_no;

        $payment_info = $this->viewPayment($request, $ref_no);
        $capture_id = $payment_info['purchase_units'][0]['payments']['captures'][0]['id'];
        $bookingBundle = BookingBundle::find($invoice_id->booking_bundle_id);

        $response = $this->client->post('v2/payments/captures/'.$capture_id.'/refund', [
            'auth' => [$this->cliendId, $this->secretKey],
            'json' => [
                'amount' => [
                    'value' => $refund_value, // Adjust the refund amount as needed
                    'currency_code' => $bookingBundle->currency, // Change to your preferred currency
                ],
                'note_to_payer' => $refund_reason,
            ]
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);
        // Check if the refund was successful
        if ($responseData['status'] === 'COMPLETED') {

            //UPDATE INVOICE PAYMENT
            //$invoice_payment_id->refund_paid_at = \Carbon\Carbon::now();
            $invoice_payment_id->refund_at = \Carbon\Carbon::now(); 
            $invoice_payment_id->refund_value = $refund_value;
            $invoice_payment_id->save();

            $has_refunds = InvoicePayment::where('ref_no', $ref_no)->whereRaw('amount < 0')->first();
            if(!$has_refunds){
                //ADD REFUND
                InvoicePayment::create([
                    "invoice_id"=>$invoice_payment_id->invoice_id,
                    "is_auto"=>0,
                    "ref_no"=>$ref_no,
                    "method"=>"REFUNDED ON PAYPAL",
                    "paid_at"=>\Carbon\Carbon::now(),
                    "amount"=>($refund_value*-1),
                    "note"=>$refund_reason,
                    "created_by"=>auth()->user()->id,
                    "is_online"=>1,
                    "refunded_paid_at"=>\Carbon\Carbon::now()
                ]);
            }
            $bookingBundle = BookingBundle::find($invoice_id->booking_bundle_id);
            \iProtek\Core\Helpers\InvoiceHelper::updateInvoice($bookingBundle);
            return ["status"=>1, "message"=>"Refund Completed"];
        }
        
        return ["status"=>0, "message"=>$responseData['status']];

    }

}