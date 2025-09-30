<?php

namespace iProtek\Core\Models;

use iProtek\Core\Models\_CommonModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tagging extends _CommonModel
{
    //

    public $fillable = [
        "target_id",
        "target_name",
        "value"
    ];

    /*Has no effect better inline select
    public function description(){
        return $this->hasOne(TaggingDescription::class, 'target_name', 'target_name');
    }
        */
}
