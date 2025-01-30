<?php

namespace iProtek\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppVariable extends Model
{
    use HasFactory, SoftDeletes;
    public $fillable = [
        "name",
        "value", 
        "target_id",
        //"created_by",
        "updated_by"
    ];
    
    public $casts = [
        "created_at" => "datetime:Y-m-d H:i",
        "updated_at" => "datetime:Y-m-d H:i"
    ];
}
