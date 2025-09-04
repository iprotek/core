<?php

namespace iProtek\Core\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use iProtek\Core\Helpers\AppVarHelper;

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
            $requests = Request::create("/api/raw-app-list", 'POST', []);

            $response = app()->handle($requests);
            
            //$response = Route::dispatch($request);
            return json_decode($response->getContent(), true);
        }

        $curl_header = [
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0, // Specify HTTP/2
            //CURLOPT_DNS_SERVERS => '8.8.8.8, 8.8.4.4'
        ];
        //if($this->isLoc)
        $parsedUrl = parse_url($app_systems_url);
        $host = $parsedUrl['host'];
        $is_localhost =  in_array($host, ['127.0.0.1', 'localhost', '::1']);
        if($is_localhost){
            $curl_header = [
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0
            ]; 
        }
        
        $client = new Client([ 
            'base_uri' => $app_systems_url,
            'timeout' => 10,
            "http_errors"=>false, 
            "verify"=>false, 
            "curl"=> $curl_header,
            "headers"=>[
                "Accept"=>"application/json",
                "Content-type"=>"application/json",
            ]
        ]);
        $response = $client->request("POST","/api/raw-app-list",[
            'body' => "{}"
        ]);
        
        $response_code = $response->getStatusCode();
        if($response_code != 200 && $response_code != 201){
            Log::error($response->getBody());
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

    public function get_field_settings(Request $request){
        $this->validate($request, [
            'target_name' => 'required|string|max:191',
            'target_id' => 'required|integer|min:1',
        ]);

        $varName = $request->target_name."_".$request->target_id;
        $result = AppVarHelper::get($varName, "[]", $request->target_id);
        //CONVERT T JSON RESULT

        return json_decode($result, true);

    }

    public function set_field_settings(Request $request){

        $this->validate($request, [
            'target_name' => 'required|string|max:191',
            'target_id' => 'required|integer|min:1',
            'data' => 'required|array',
        ]);

        $varName = $request->target_name."_".$request->target_id;
        $newValue = json_encode( $request->data );

        $result = AppVarHelper::set(
            $varName, 
            $newValue, 
            $request->target_id, 
            true
        );

        return ["status"=>1, "message"=>"Field settings saved."];
    }

}
