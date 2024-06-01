<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileLink extends Model
{
    use HasFactory;
    public $fillable = [
        "name",
        "url",
        "file_type",
        "created_by",
        "updated_by"
    ];
    
    public $casts = [
        "created_at" => "datetime:Y-m-d H:i",
        "updated_at" => "datetime:Y-m-d H:i"
    ];
}
