<?php

namespace iProtek\Core\Helpers;

use DB; 
use Swift_SmtpTransport;
use Swift_Mailer;
use Illuminate\Support\Facades\Log;

class MailHelper
{
    
    public static function send($to,$mailable,$cc=null, $replyTo=null, $smtpConnection = null)
    {

        //CC Coverstion
        if( $cc && is_string( $cc )){
            $cc = explode( ',', $cc );
        }
        $toList = null;


        //To and carbon copy
        if(is_string($to)){
            //breakdown to
            $toList = explode(',', $to);
        }
        else if(is_array($to)){
            $toList = $to;
        }
        else{
            return null;
        }

        //Constraint to and cc array
        $to = null;
        for($i=0; $i < count($toList); $i++ ){
            if( $to == null && filter_var($toList[$i], FILTER_VALIDATE_EMAIL) ){
                $to = $toList[$i];
                continue;
            }
            else if($cc == null){
                $cc = [];
            }
            else if( is_array($cc) ){
                $cc[] = $toList[$i];
            }
        }
        if(!$to){
            return null;
        }
        
        //VALIDATIONS
        $valid_to = "";
        if(filter_var($to, FILTER_VALIDATE_EMAIL)){
            $valid_to = $to;
        }
        $valid_ccs = array(); 
        if( $cc != null && is_array($cc) ){
            foreach($cc as $cItem){
                if(filter_var( $cItem, FILTER_VALIDATE_EMAIL)){
                    $valid_ccs[] = $cItem;
                }
            }
        }
        //TEMPORARY
        //$valid_to = "joseph.aguilar@sportscity.com.ph";

        # Remove empty emails from array values
        if(!empty($valid_ccs))
            $valid_ccs = array_filter($valid_ccs);
        
        if( !empty($valid_to) && count($valid_ccs) > 0 )
        {
            $mail = \Mail::to($valid_to)->cc($valid_ccs);
            if($smtpConnection){
                $mail->onConnection($smtpConnection);
            }
            if($replyTo && is_array($replyTo)){
                if($replyTo['email']){
                    $mailable->replyTo($replyTo['email'], $replyTo['name']);
                }
            }
           return  $mail->queue($mailable);
        }
        else if(!empty($valid_to))
        {
            $mail = \Mail::to($valid_to);
            if($smtpConnection){
                $mail->onConnection($smtpConnection);
            }
            if($replyTo && is_array($replyTo)){
                if($replyTo['email']){
                    $mailable->replyTo($replyTo['email'], $replyTo['name']);
                }
            }
           return  $mail->queue($mailable);
        }
        return null;
    }


    public static function check($host, $port, $encryption, $username, $password){ 
        try { 
            $transport = (new Swift_SmtpTransport($host, $port, $encryption))
            ->setUsername($username)
            ->setPassword($password); 
            $mailer = new Swift_Mailer($transport);
            $mailer->getTransport()->start();
            return true;
        } catch (\Exception $e) { 
            //Log::error($e->getMessage());
            return false;
        }
        return false;
    }

}
