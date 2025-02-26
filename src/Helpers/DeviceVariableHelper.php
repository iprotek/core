<?php
namespace iProtek\Core\Helpers;

use DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use iProtek\Core\Models\DeviceAccount;

class DeviceVariableHelper
{ 
    /**
     * [account field="id" ] - get the data id
     * [device_account_id] - get the account id from the device upon registration.
     * [account field="plan" ] - get the "plan" field value form target source model.
     * [account field="User Name" data-json="json"] - get the "User Name" field value form target source custom fields. 
     */
    
    static function account($template_str, $account){
        $sample = $template_str;

        // Define the regular expression pattern
        //$pattern = '/{{\s*(phb-event-start)\s*(?:format\s*=\s*"([^"]*)"\s*)?(?:timezone\s*=\s*"([^"]*)"\s*)?(?:offset_mins\s*=\s*([^"]*)\s*)*}}/';
        $pattern = '/\[\s*(account)\s*(?:field\s*=\s*"([^"]*)"\s*)?(?:data-json\s*=\s*"([^"]*)"\s*)*\]/';
        //$pattern = '/\[account_name format="[^"]+"\]/';
        preg_match($pattern, $sample, $matches);
        $matching_string = isset($matches[0]) ? $matches[0] : "";
        if($matching_string){
            $field = isset( $matches[2]) ?  $matches[2]:null;
            $data_json = isset( $matches[3]) ?  $matches[3]:null;
            //$offset_mins = isset( $matches[4]) ?  $matches[4]:0;

            //$str = static::event_time_setup($event->utc_start, $format, $timezone, $offset_mins);
            
            $str = "";
            if( $field ){

                if($data_json){

                    $str = "";

                    $json = json_decode( $account->{$data_json}, TRUE);

                    if($json){

                       $str = $json[$field];

                    }

                }
                else{
                    $str = $account->{$field}; 
                }

            }
            $result = str_replace($matching_string, $str, $sample);

            //recheck if still exists.
            return static::account($result, $account);
        }
        return $template_str;
    }

    static function device_account_id($template_str, $target_name, $traget_id){
        
        $sample = $template_str;

        // Define the regular expression pattern
        //$pattern = '/{{\s*(phb-event-start)\s*(?:format\s*=\s*"([^"]*)"\s*)?(?:timezone\s*=\s*"([^"]*)"\s*)?(?:offset_mins\s*=\s*([^"]*)\s*)*}}/';
        $pattern = '/\[\s*(device_account_id)\s*\]/';
        //$pattern = '/\[account_name format="[^"]+"\]/';
        preg_match($pattern, $sample, $matches);
        $matching_string = isset($matches[0]) ? $matches[0] : "";
        if($matching_string){

            $deviceAccount = DeviceAccount::where([
                "target_name"=>$target_name,
                "target_id"=>$traget_id
            ])->first();

            //$field = isset( $matches[2]) ?  $matches[2]:null;
            //$data_json = isset( $matches[3]) ?  $matches[3]:null;
            //$offset_mins = isset( $matches[4]) ?  $matches[4]:0; 
            //$str = static::event_time_setup($event->utc_start, $format, $timezone, $offset_mins); 
            $str = "";
            if( $deviceAccount ){
                $str = $deviceAccount->id;
            }
            $result = str_replace($matching_string, $str, $sample);

            //recheck if still exists.
            return static::device_account_id($result, $target_name, $traget_id);
        }
        return $template_str;

    }
}