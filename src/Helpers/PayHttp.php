<?php

namespace iProtek\Core\Helpers;

use DB; 
use Illuminate\Support\Facades\Log;
use iProtek\Core\Models\UserAdminPayAccount;

class PayHttp
{
    
    public static function pay_account_id(){
        $pay_account_id = 0;
        if(auth('admin')->check()){
            $user_admin_id = auth('admin')->user()->id;
            $user_admin = UserAdminPayAccount::where('user_admin_id', $user_admin_id)->first();
            if($user_admin){
                $pay_account_id = $user_admin->pay_app_user_account_id;
            }
        }

        return $pay_account_id;
    }

    public static function proxy_group_id(){
        //GETTING THE SESSION BUT IF FAILED THEN GET THE DB DEFAULTS
        $proxy_group_id = PayGroup::getGroupId();
        if( !$proxy_group_id && auth('admin')->check() ){
            $user_admin_id = auth('admin')->user()->id;
            $user_admin = UserAdminPayAccount::where('user_admin_id', $user_admin_id)->first();
            if($user_admin){
                $proxy_group_id = $user_admin->default_proxy_group_id;
            }
        }
        return $proxy_group_id;
    }

    public static function http2($is_auth = true, $access_token=""){
        $pay_url = config('iprotek.pay_url');
        $client_id = config('iprotek.pay_client_id');
        $client_secret = config('iprotek.pay_client_secret'); 

        $headers = [];
        if($is_auth == false){
            $headers = [
                "Accept"=>"application/json",
                "Authorization"=>"Bearer ".$client_id.":".$client_secret
            ];
        }
        else{
            $headers = [
                "Accept"=>"application/json",
                "Authorization"=>"Bearer ".$access_token
            ];
        }
        
        $client = new \GuzzleHttp\Client([
            'base_uri' => $pay_url,
            "http_errors"=>false, 
            "verify"=>false, 
            "curl"=>[
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0, // Specify HTTP/2
            ],
            "headers"=>$headers
         ]);
        return $client;
    }

    public static function client(){
        return static::http2(false);
    }

    public static function auth($access_token){
        return static::http2(true, $access_token);
    }

    public static function account_info(){
        
        $user = auth()->user();
        $pay_account = UserAdminPayAccount::where(["user_admin_id"=>$user->id])->first();
        if(!$pay_account)
            return null;
        
        $client = PayHttp::auth($pay_account->access_token);
        $response = $client->get('app-user-account');
        
        $response_code = $response->getStatusCode();
        if($response_code != 200 && $response_code != 201){
            return null;
        }
        $result = json_decode($response->getBody(), true);

        //Restrict Group from sub account
        if($pay_account->sub_account_group_id && $pay_account->default_proxy_group_id != "0" ){
            $new_group = [];
            $groups = $result['groups'];
            foreach($groups as $g){
                if($g['group_id'] == $pay_account->sub_account_group_id){
                    $new_group[] = $g;
                    break;
                }
            }
            $result['groups'] = $new_group;
        }


        return ["default_group_id"=>$pay_account->default_proxy_group_id,"user_info"=>$result];
        //return static::auth($pay_account->access_token);

    }

    public static function app_user_account($search, $page=1, $items_per_page=""){
        $queryString = http_build_query(["search"=>$search, "page"=>$page, "items_per_page"=>$items_per_page]);
        
        $user = auth()->user();
        $pay_account = UserAdminPayAccount::where(["user_admin_id"=>$user->id])->first();
        if(!$pay_account)
            return null;
        
        $client = PayHttp::auth($pay_account->access_token);
        $response = $client->get('app-user-account/share-group-list?'.$queryString);
        
        $response_code = $response->getStatusCode();
        if($response_code != 200 && $response_code != 201){
            return null;
        }
        $result = json_decode($response->getBody(), true); 
        foreach($result['data'] as &$row){

            //user_name_full_name
            $row['user_name_full_name'] = "N/A";
            $email = "";
            if($row['share_to_app_user_account_id'] == 0){
                if($row['owner']){
                    $row['user_name_full_name'] = $row['owner']['name']." (".$row['owner']['email'].")";
                    $email = $row['owner']['email'];
                }
            }
            else if($row['share_user']){
                $row['user_name_full_name'] = $row['share_user']['name']." (".$row['share_user']['email'].")";
                $email = $row['share_user']['email'];
            }
            $row['email'] = $email;

            //group_name_full
            $email_display = "";
            if($row['share_to_app_user_account_id'] == 0){
                $email_display = "Owner";
            }

            if($email_display == ""){
                $email_display = $row['owner']['email'];
            }

            if(!$row['owner']){
                $row['group_name_full'] = "N/A";
            }
            else if(!$row['owner']['group_name']){
                $row['group_name_full'] = "Personal (".$email_display.")";
            }
            else 
                $row['group_name_full'] = $row['owner']['group_name']." (".$email_display.")";
            unset($row['owner']);// = null;
            unset($row['share_user']);// = null;

        }

        return $result;
    }

    public static function send_invitation($email, $role, $verify_password){
        $user = auth()->user();
        $pay_account = UserAdminPayAccount::where(["user_admin_id"=>$user->id])->first();
        if(!$pay_account)
            return null;
        $data = [
            "email"=>$email,
            "role"=>$role,
            "password"=>$verify_password
        ];
        $client = PayHttp::auth($pay_account->access_token);
        $response = $client->post('app-user-account/send-invitation', [
            "json" => $data
        ]);
        $response_code = $response->getStatusCode();
        if($response_code != 200 && $response_code != 201){
            
            return [ "status"=>0, "message" => "Invalidated:(".$response_code.")".$response->getReasonPhrase(), "status_code"=>$response_code ];
        }
        $result = json_decode($response->getBody(), true);

        return $result;

    }

    public static function send_reconvery_link($email){
        
        $data = [
            "email"=>$email
        ];
        $client = static::client();
        $response = $client->post('send-recovery', [
            "json" => $data
        ]);
        $response_code = $response->getStatusCode();
        if($response_code != 200 && $response_code != 201){
            
            return [ "status"=>0, "message" => "Invalidated:(".$response_code.")".$response->getReasonPhrase(), "status_code"=>$response_code ];
        }
        $result = json_decode($response->getBody(), true);

        return $result;
    }

    public static function logout(){
        
        $user = auth()->user();
        $pay_account = UserAdminPayAccount::where(["user_admin_id"=>$user->id])->first();
        if(!$pay_account)
            return null;
        
        $client = PayHttp::auth($pay_account->access_token);
        $response = $client->get('app-user-account/logout');
        
        $response_code = $response->getStatusCode();
        if($response_code != 200 && $response_code != 201){
            //return null;
            return ["status"=>0, "message"=>"Failed logged out"];
        }
        return ["status"=>1, "message"=>"Successfulyy logged out"];
        //$result = json_decode($response->getBody(), true); 
        //return ["default_group_id"=>$pay_account->default_proxy_group_id,"user_info"=>$result];
    }

    public static function client_info(){
        
        $client = static::client();
        
        $response = $client->get('client-info');
        
        $response_code = $response->getStatusCode(); 
        if($response_code != 200 && $response_code != 201){
            return null;
        }
        $result = json_decode($response->getBody(), true);
        return $result;
    }

    public static function get_client_load($url){

        $client_id = config('iprotek.pay_client_id');
        $client_secret = config('iprotek.pay_client_secret');
        $pay_url = config('iprotek.pay_url');
        
        $client = new \GuzzleHttp\Client([
            'base_uri' => $url,
            "http_errors"=>false, 
            "verify"=>false, 
            "curl"=>[
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0, // Specify HTTP/2
            ],
            "headers"=>[
                "Accept"=>"application/json",
                "CLIENT-ID"=>$client_id,
                "SECRET"=>$client_secret,
                "PAY-URL"=>$pay_url
            ]
         ]);
         $response = $client->get('');
         $response_code = $response->getStatusCode(); 
         if($response_code != 200 && $response_code != 201){
             return json_decode($response->getBody(), true);
         }
         $result = json_decode($response->getBody(), true);
         return $result;
    }

    public static function pusher_info(){
        $client_info = static::client_info(); 
        if($client_info){

            if(is_array($client_info)){
                $socket_settings = isset( $client_info['socket_settings'] ) ?  $client_info['socket_settings'] : null;
                if($socket_settings){
                    $socket_settings = json_decode( json_encode($socket_settings), TRUE);
                } 
                return $socket_settings;
            }
            else{
                if(isset( $client_info->socket_settings ) ){
                    $socket_settings = json_decode( json_encode($client_info->socket_settings), TRUE);
                    return $socket_settings;
                } 
            }
        }
        return null;
    }

    public static function send_pusher_notification($channel, $bind_trigger, $data=[]){
        $pusher_info = static::pusher_info();

        if($pusher_info && $pusher_info['is_active'] && $pusher_info['socket_name'] == 'PUSHER.COM'){


            $cluster = isset($pusher_info['cluster']) ? $pusher_info['cluster']:"";
            $key = isset($pusher_info['key']) ? $pusher_info['key']:"";
            $secret = isset($pusher_info['secret']) ? $pusher_info['secret']:"";
            $app_id = isset($pusher_info['app_id']) ? $pusher_info['app_id']:"";  
            
            $options = array(
                'cluster' => $cluster,//'ap1', //cluster 
                'useTLS' => false
            );
            $pusher = new \Pusher\Pusher(
                $key,//'3ba4f1b9531904744a8e', //key
                $secret, //'1b7dd30d6604966641ab', //secret
                $app_id, //'1858123', //app_id
                $options
            );
            
            //$data['message'] = 'new sms';
            $pusher->trigger($channel, $bind_trigger, $data);


        }

    }



}
