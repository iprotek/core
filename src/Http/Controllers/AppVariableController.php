<?php

namespace iProtek\Core\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AppVariableController extends Controller
{
    //
    public function api_applist(Request $request){
        
        
        //abort(403, config("iprotek.system"));
        $app_systems_url = config("iprotek.system"); 
        if(!$app_systems_url){
            abort(403, "APP_SYSTEMS IS EMPTY");
            //return [];
        }

        if(config('iprotek.system') == config('app.url')){
            $requests = Request::create("/api/raw-app-list", 'GET');

            $response = app()->handle($requests);
            return json_decode($response->getContent(), true);
        }
        
        $client = new Client([ 
            'base_uri' => $app_systems_url,
            "http_errors"=>false, 
            "verify"=>false, 
            "curl"=>[
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0, // Specify HTTP/2
            ],
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

    public function raw_api_applist(Request $request){
        
        $apps = \App\Models\Application::on();
        $apps->select('id', 'name', 'url');
        return $apps->get();
    }

}
