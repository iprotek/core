<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileImportData extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        
        "group_id",
        "pay_created_by",
        "pay_updated_by",
        "pay_deleted_by",

        "json_data",
        "file_import_batch_id",
        "status_id", //0-Pending, 1-Success, 2-Failed
        "status_info"
        
    ];

}
