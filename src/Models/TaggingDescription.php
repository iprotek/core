<?php

namespace iProtek\Core\Models;

use iProtek\Core\Models\_CommonModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaggingDescription extends _CommonModel
{
    //
    public $fillable = [
        "target_id",
        "target_name",
        "value"
    ];
}
