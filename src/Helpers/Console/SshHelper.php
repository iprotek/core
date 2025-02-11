<?php
namespace iProtek\Core\Helpers\Console;

use DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use iProtek\Core\Models\Cms;
use iProtek\Core\Enums\CmsType;
use phpseclib3\Net\SSH2;

class SshHelper
{  
    public static function credential_login_check(array $credential){
        //CREDENTIAL CHECK
        //host
        //user
        //port
        //password
        //Log::error($credential);
        
        $host = $credential['host'];
        $user = $credential['user'];
        $pass = $credential['password'];
        $port = (int)$credential['port'];
        $is_ssl = $credential['is_ssl'];
        //Log::error($credential);
        
        //EVILFREELANCER

        try {
            
            $ssh = new SSH2($host, $port); // Specify the custom port

            if (!$ssh->login($user, $pass)) {
                return [ "status"=>0, "message"=> "Login Failed"];
            }

            // Execute a MikroTik command
            $output = $ssh->exec('/system identity print');
            //print_r($response);
            //Log::error( $output );
            return [ "status"=>0, "message"=> $output ];
        } catch (\Exception $e) {
            //Log::error( "GG". $e->getMessage() );
            return [ "status"=>0, "message"=> $e->getMessage() ];
        }

        return ["status"=>0, "message"=>"Unable to connect to mikrotik"];

    }

}