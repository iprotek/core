<?php

namespace iProtek\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;

class AppVariableController extends Controller
{
    //
    public function api_applist(Request $request){
        
        
        //abort(403, config("iprotek.system"));
        $app_systems_url = trim(config("iprotek.system")); 
        if(!$app_systems_url){
            abort(403, "APP_SYSTEMS IS EMPTY");
            //return [];
        }

        if(config('iprotek.system') == config('app.url')){
            $requests = Request::create("/api/raw-app-list", 'GET');

            $response = app()->handle($requests);
            return json_decode($response->getContent(), true);
        }

        $curl_header = [
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0, // Specify HTTP/2
            CURLOPT_DNS_SERVERS => '8.8.8.8,8.8.4.4'
        ];
        
        $parsedUrl = parse_url($app_systems_url);
        $host = $parsedUrl['host'];
        $is_localhost =  in_array($host, ['127.0.0.1', 'localhost', '::1']);
        if($is_localhost){
            $curl_header = [
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0
            ]; 
            
            Log::error("is localhost"); 
        }

        $handler = new CurlHandler();
        $stack = HandlerStack::create($handler);
        
        $client = new Client([
            'handler' => $stack,
            'curl' => $curl_header,
            "headers"=>[
                "Accept"=>"application/json"
            ]
        ]);



        
        try {
            $response = $client->request('GET', $app_systems_url."/api/raw-app-list");
            $result = json_decode($response->getBody(), true);
            return $result;
        } catch (RequestException $ex) {
            Log::error($ex->getMessage()); 
            if ($ex->hasResponse()) {
                Log::error($ex->getResponse()->getBody()->getContents()); 
            }
        } catch (\Exception $ex) {
            Log::error($ex->getMessage()); 
        }

        return [];


        //if($this->isLoc)
        $parsedUrl = parse_url($app_systems_url);
        $host = $parsedUrl['host'];
        $is_localhost =  in_array($host, ['127.0.0.1', 'localhost', '::1']);
        if($is_localhost){
            $curl_header = [
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0
            ]; 
        }
        try{
            $client = new Client([ 
                'base_uri' => $app_systems_url,
                'timeout' => 10,
                "http_errors"=>false, 
                "verify"=>true, 
                "curl"=> $curl_header,
                "headers"=>[
                    "Accept"=>"application/json"
                ]
            ]);
            $response = $client->get("/api/raw-app-list");
            
            $response_code = $response->getStatusCode();
            if($response_code != 200 && $response_code != 201){
                return [];
            }
            $result = json_decode($response->getBody(), true);
            return $result;
        }
        catch(\Exception $ex){
            Log::error($ex->getMessage()); 
            Log::error($app_systems_url); 
        }
        return [];
    }

    public function raw_api_applist(Request $request){
        
        $apps = \App\Models\Application::on();
        $apps->select('id', 'name', 'url');
        return $apps->get();
    }

}
