<?php

namespace iProtek\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cms extends Model
{
    use HasFactory;

    public $fillable = [
        
        "group_id",
        "pay_created_by",
        "pay_updated_by",
        "pay_deleted_by",

        "target_name",
        "target_id",
        "type",
        "is_default",
        "content"

    ];
}
