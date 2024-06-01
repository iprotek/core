<?php

namespace iProtek\Core\Http\Controllers;
use Illuminate\Http\Request;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\CreditCard;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
//use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
//use PayPal\Api\Payer;
//use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Address;
use GuzzleHttp\Client;
//use PayPal\Api\Transaction;
//use PayPal\Rest\ApiContext;
//use PayPal\Auth\OAuthTokenCredential;

class PayPalBankTransferController extends Controller
{/*
    public function initiateBankTransfer3()
    {
        $clientId = 'ARrLmqXkKpKB2M7dY1ry6kFLHwdCld1DQWGKzr-TwBpsNYd3ENqh6TC9_sCh1f83HfKibr91p5UJLtEG';
        $clientSecret = 'ELeosDh3XQti3w3NNR4WAEi7lFmpPl-WY8FoozhpJ-6a8_hu2Y0_xng1DuNOSqWu9swLFo-EX90j95Vb';
        $apiContext = new ApiContext(
            new OAuthTokenCredential($clientId, $clientSecret)
        );

        $apiContext->setConfig([
            'mode' => 'sandbox', // 'live', Use 'sandbox' for testing
        ]);

        // Create a credit card as the funding source
        $creditCard = new CreditCard();
        $creditCard->setType('visa') // Card type (e.g., Visa)
            ->setNumber('4032032170977604') // Replace with the actual card number
            ->setExpireMonth(7) // Expiration month
            ->setExpireYear(2028) // Expiration year
            ->setCvv2('123') // Card Verification Value (CVV)
            ->setFirstName('Joseph')
            ->setLastName('Aguilar');

        
        $fundingInstrument = new FundingInstrument();
        $fundingInstrument->setCreditCard($creditCard);

        // Create a funding instrument
        $payer = new Payer();
        $payer->setPaymentMethod('credit_card')
            //->setPaymentEmail('drimcaster2@gmail.com')
            ->setPayerInfo([
                'email' => 'drimcaster2@gmail.com',
                'first_name' => 'Joseph',
                'last_name' => 'Aguilar',
            ])
            ->setFundingInstruments([$fundingInstrument]);
        
        $amount = new Amount();
        $amount->setTotal(1000.00) // Amount to transfer
            ->setCurrency('USD');

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription('Item Description')
            ->setInvoiceNumber('123456');

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction]);

        try {
            $payment->create($apiContext);

            // Check the payment status and handle the result
            if ($payment->state === 'approved') {
                return 'Bank transfer initiated successfully.';
            } else {
                return 'Bank transfer failed. Payment not approved.';
            }
        } catch (\Exception $e) {
            return 'Bank transfer failed. Error: ' . $e->getMessage();
        }
    }
    public function initiateBankTransfer2()
    {       
        //require_once(ROOT . DS . 'vendor' . DS . "autoload.php");       

        // integrate paypal 
        $apiContext = new \PayPal\Rest\ApiContext(
          new \PayPal\Auth\OAuthTokenCredential(
            'ARrLmqXkKpKB2M7dY1ry6kFLHwdCld1DQWGKzr-TwBpsNYd3ENqh6TC9_sCh1f83HfKibr91p5UJLtEG',
            'ELeosDh3XQti3w3NNR4WAEi7lFmpPl-WY8FoozhpJ-6a8_hu2Y0_xng1DuNOSqWu9swLFo-EX90j95Vb'
            )
        );

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");
        //Itemized information (Optional) Lets you specify item wise information

        $item1 = new Item();
        $item1->setName('Ground Coffee 40 oz')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice(17);

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        $details = new Details();
        $details->setShipping(1)
            ->setTax(2)
            ->setSubtotal(17);

        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal(20)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());

        $baseUrl = $this->getBaseUrl();

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("$baseUrl")
            ->setCancelUrl("$baseUrl");

        $payment = new Payment();


        $payment->setIntent("order")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        //For Sample Purposes Only.

        $request = clone $payment;

        try {
            $paymentdetail = $payment->create($apiContext);
            echo '<pre>';
            print_r($paymentdetail);
            echo '</pre>';

            die('inside try ');
        } catch (Exception $ex) {
            //NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY

            ResultPrinter::printError("Created Payment Order Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
            die('Inside catch');
        }
        //Get redirect url
        //The API response provides the url that you must redirect the buyer to. Retrieve the url from the $payment->getApprovalLink() method

        $approvalUrl = $payment->getApprovalLink();
        //NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY

         ResultPrinter::printResult("Created Payment Order Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $payment);

        return $payment;    
    }
    public function initiateBankTransfer4(){
        // Set your PayPal API credentials
        $client_id = 'ARrLmqXkKpKB2M7dY1ry6kFLHwdCld1DQWGKzr-TwBpsNYd3ENqh6TC9_sCh1f83HfKibr91p5UJLtEG';
        $client_secret = 'ELeosDh3XQti3w3NNR4WAEi7lFmpPl-WY8FoozhpJ-6a8_hu2Y0_xng1DuNOSqWu9swLFo-EX90j95Vb';

        // Set the PayPal API endpoint
        //$paypal_endpoint = 'https://api-m.paypal.com/v2/';// 'https://api.paypal.com/v1/';
        $paypal_endpoint = 'https://api.sandbox.paypal.com/v1/';

        // Set the payment amount
        $amount = 100.00;
        $currency = 'USD';

        // Set the card details
        
        //$cardholder_name = 'John Doe';
        //$card_number = '4032032170977604';
        //$card_expiration = '07/2028'; // MM/YY format
        //$card_cvv = '123';

        // Set the item description and invoice number
        $item_description = 'Item Description';
        $invoice_number = '123456';
        $cardholder_email = "drimcaster2@gmail.com";
        // Build the API request payload
        $billing_address = [
            'line1' => '123 Billing St',
            'line2' => 'Suite 456',
            'city' => 'New York City',
            'state' => 'New York',
            'postal_code' => '10001',
            'country_code' => 'US', // Specify the two-letter country code (e.g., 'US' for the United States)
        ];
        $data = [
            'intent' => 'sale',
            'payer' => [
                'payment_method' => 'credit_card',
                'payer_info' => [
                    'email' => $cardholder_email, // Payer's email
                    'billing_address' => $billing_address,
                    // Add more payer information if needed
                ],
                'funding_instruments' => [
                    [
                        'credit_card' => [
                            'number' => 4032032170977604,
                            'type' => 'Visa', // Card type (e.g., Visa)
                            'expire_month' => 7,//substr($card_expiration, 0, 2),
                            'expire_year' => 2028,//substr($card_expiration, -4),
                            'cvv2' => 123,//$card_cvv,
                            'first_name' => "Joseph", // Cardholder's first name
                            'last_name' => 'Aguilar', // Cardholder's last name (optional)
                            //'email' => 'drimcaster2@gmail.com'
                        ],
                    ],
                ],
            ],
            'transactions' => [
                [
                    'amount' => [
                        'total' => $amount,
                        'currency' => $currency,
                    ],
                    'description' => $item_description,
                    'invoice_number' => $invoice_number,
                ],
            ],
        ];

        $data = json_encode($data);

        // Set cURL options for the API request
        $options = [
            CURLOPT_URL => $paypal_endpoint . 'payments/payment',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . base64_encode($client_id . ':' . $client_secret),
                'Content-Type: application/json',
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);

        // Execute the API request
        $response = curl_exec($ch);

        if (!$response) {
            echo 'cURL Error: ' . curl_error($ch);
        } else {
            // Process the response (e.g., output it)
            echo $response;
        }

        // Close cURL resource
        curl_close($ch);
    }

    public function initiateBankTransfer5(Request $request)
    {
        $client_id = 'ARrLmqXkKpKB2M7dY1ry6kFLHwdCld1DQWGKzr-TwBpsNYd3ENqh6TC9_sCh1f83HfKibr91p5UJLtEG';
        $client_secret = 'ELeosDh3XQti3w3NNR4WAEi7lFmpPl-WY8FoozhpJ-6a8_hu2Y0_xng1DuNOSqWu9swLFo-EX90j95Vb';
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $client_id ,
                $client_secret
            )
        );



        $creditCard = new CreditCard();
        $creditCard->setType('visa') // Card type (e.g., Visa)
            ->setNumber('4032032170977604') // Replace with the actual card number
            ->setExpireMonth(7) // Expiration month
            ->setExpireYear(2028) // Expiration year
            ->setCvv2('123') // Card Verification Value (CVV)
            ->setFirstName('Joseph')
            ->setLastName('Aguilar');

        $fundingInstrument = new FundingInstrument();
        $fundingInstrument->setCreditCard($creditCard);

        $billingAddress = new Address();
        $billingAddress->setLine1('123 Test Street');
        $billingAddress->setLine2('Apt 4B');
        $billingAddress->setCity('San Jose');
        $billingAddress->setState('CA');
        $billingAddress->setPostalCode('95131');
        $billingAddress->setCountryCode('US');

        $payerInfo = [
            'email' => 'drimcaster2@gmail.com',
            'first_name' => 'Joseph',
            'last_name' => 'Aguilar',
            'billing_address' => $billingAddress
        ];

        $payer = new Payer();
        $payer->setPaymentMethod('credit_card')
            ->setFundingInstruments([$fundingInstrument]);
        $payer->setPayerInfo($payerInfo);
        

        $item = new Item();
        $item->setName('Product Name')
            ->setCurrency('USD')
            ->setSKU("sku123")
            ->setQuantity(1)
            ->setPrice(10.00);

        $itemList = new ItemList();
        $itemList->setItems([$item]);
        $itemList->setShippingAddress([
            'line1' => '123 Test Street',
            'state' => 'CA',
            'postal_code' => '95131',
            'city' => 'San Jose',
            'country_code' => 'US', 
            "phone" => "4085068904", 
            "type" => "HOME_OR_WORK"
        ]);

        $details = new Details();
        $details->setSubtotal(10.00);

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal(10.00)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription('Payment description');
        

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal.success'))
            ->setCancelUrl(route('paypal.cancel'));

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($apiContext);

            return redirect($payment->getApprovalLink());
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
*/ 
    private $client;
    private $cliendId = "";
    private $secretKey = ""; 
    public function initiateBankTransfer(Request $request){

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
        $response = $this->client->post('v2/checkout/orders', [
            'auth' => [$this->cliendId, $this->secretKey],
            'json' => [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'description'=> "TEST",
                        'amount' => [
                            'currency_code' => "USD",
                            'value' => "100"
                        ],
                        'invoice_id' => "TEST",
                    ],
                ],
                "final_capture"=> true,
                'application_context' => [
                    'cancel_url' => route('paypal.cancel'),//route('payments.paypal-booking.cancel'),
                    'return_url' => route('paypal.success')//route('payments.paypal-booking.execute',[ "pay_type"=>$pay_type, "id" => $request->id, "customer_id" => $id->customer_id ]),
                ],
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        //return $data;
        $ref_no = $data['id'];
        //return redirect('https://www.paypal.com/checkoutweb/signup?token='.$ref_no.'&useraction=CONTINUE');
        return redirect('https://www.sandbox.paypal.com/checkoutweb/signup?token='.$ref_no);
        //return $data;

    }
    
    public function initiateBankTransfer_old(){ 
            
        $this->cliendId = 'ARrLmqXkKpKB2M7dY1ry6kFLHwdCld1DQWGKzr-TwBpsNYd3ENqh6TC9_sCh1f83HfKibr91p5UJLtEG';
        $this->secretKey = 'ELeosDh3XQti3w3NNR4WAEi7lFmpPl-WY8FoozhpJ-6a8_hu2Y0_xng1DuNOSqWu9swLFo-EX90j95Vb';
            $apiContext = new ApiContext(
                new OAuthTokenCredential( $this->cliendId, $this->secretKey
                )
            );
    
            $apiContext->setConfig([
                'mode' => 'sandbox', // Use 'live' for production
            ]);
    
            // Create a payer
            $payer = new Payer();
            $payer->setPaymentMethod('credit_card');
    
            // Create an amount
            $amount = new Amount();
            $amount->setCurrency('USD')
                   ->setTotal(100.00); // Set the payment amount
    
            // Create a transaction
            $transaction = new Transaction();
            $transaction->setAmount($amount)
                        ->setDescription('Payment description');
    
            // Create a payment
            $payment = new Payment();
            $payment->setIntent('sale')
                    ->setPayer($payer)
                    ->setTransactions([$transaction]);
    
            // Execute the payment
            $payment->create($apiContext);
    
            return $payment; 
    }
}