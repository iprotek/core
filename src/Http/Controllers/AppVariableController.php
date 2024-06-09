<?php

namespace iProtek\Core\Http\Controllers;

use Illuminate\Http\Request;

class AppVariableController extends Controller
{
    //
    public function api_applist(Request $request){
        
        
        $app_systems_url = env("APP_SYSTEMS","");
        if(!$app_systems_url){
            abort(403, "APP_SYSTEMS IS EMPTY");
            //return [];
        }


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
         $response = $client->get('app-list');
         
         $response_code = $response->getStatusCode();
         if($response_code != 200 && $response_code != 201){
             return [];
         }
         $result = json_decode($response->getBody(), true);
         return $result;
    }
}
