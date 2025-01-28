<?php
namespace iProtek\Core\Helpers;

use DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use iProtek\Core\Models\Cms;
use iProtek\Core\Enums\CmsType;

class CmsHelper
{ 
    public static function getContent(Cms $cms){
        return $cms->content;
    }

    public static function getContentByDetail($target_name, $target_id, $target_type){
        $type = CmsType::getValue($target_type);
        if(!$type) return "";

        $cms = Cms::where([
            "target_name"=>$target_name,
            "target_id"=>$target_id,
            "type"=>$type
        ])->first();
        if(!$cms){
            return "";
        }
        return static::getContent($cms);
    }

}