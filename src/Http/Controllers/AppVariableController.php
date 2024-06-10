<?php

namespace iProtek\Core\Http\Controllers;

use Illuminate\Http\Request;

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

        
        //$request = Request::create($app_systems_url."/app-list", 'GET');

        //$response = app()->handle($request);

        //return json_decode($response->getContent(), true);

        $client = new \GuzzleHttp\Client([ 
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
         $response = $client->get("app-list");
         
         $response_code = $response->getStatusCode();
         if($response_code != 200 && $response_code != 201){
             return [];
         }
         $result = json_decode($response->getBody(), true);
         return $result;
    }
}
