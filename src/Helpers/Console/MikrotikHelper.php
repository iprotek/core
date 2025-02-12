<?php
namespace iProtek\Core\Helpers\Console;

use DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use iProtek\Core\Models\Cms;
use iProtek\Core\Enums\CmsType;
use MikrotikAPI\MikrotikAPI;
use RouterOS\Client as MikroTikClient;
use RouterOS\Query as MikroTikQuery;

class MikrotikHelper
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

        $command = '/system/identity/print';

        try {
            // Establish connection to MikroTik
            $client = new MikroTikClient([
                'host' => $host,  // Change to your MikroTik IP
                'user' => $user,          // Change to your MikroTik username
                'pass' => $pass,       // Change to your MikroTik password
                'port' => $port,             // API port (8728 for HTTP, 8729 for HTTPS)
                "ssl" => $is_ssl,
            ]);

        
            // Example: Get router identity
            $query = new MikroTikQuery( $command );
            $response = $client->query($query)->read();
        
            //$response = $client->query(  $command )->read();
            //$response = $client->export();

            //Log::error($response);
            //print_r($response);
            return [ "status"=>1, "message"=> json_encode($response), "command"=>$command ];
        } catch (\Exception $e) {
            //Log::error( $e->getMessage() );
            return [ "status"=>0, "message"=> $e->getMessage(), "command"=>$command ];
        }

    }

}