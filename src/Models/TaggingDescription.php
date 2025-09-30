<?php

namespace App\Models;

use iProtek\Core\Models\_CommonModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaggingDescription extends Model
{
    //
    public $fillable = [
        "target_id",
        "target_name",
        "value"
    ];
}
