<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileImportBatch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        
        "group_id",
        "pay_created_by",
        "pay_updated_by",
        "pay_deleted_by",

        "file_name",
        "target_field", //Name of the table or any for the purpose of table
        "settings",
        "total_lines",
        "line_processing",
        "line_succeed",
        "line_failed",
        "status_id", //0-Pending, 1-Completed, 2-Failed, 3-Processing, 4-Stopped, 5-Reset, 6-Restart
        "status_info"

    ];
}
