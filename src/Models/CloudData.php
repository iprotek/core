<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudData extends Model
{
    use HasFactory;
    public $fillable = [
        "service_name",
        "file_upload_id",
        "file_allocation",
        "file_name",
        "is_uploaded",
        "backup_infos",
        "error_infos"
    ];
}
