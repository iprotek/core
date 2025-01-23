<?php

namespace iProtek\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileImportData extends _CommonModel
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        
        "group_id",
        "pay_created_by",
        "pay_updated_by",
        "pay_deleted_by",

        "json_data",
        "file_import_batch_id",
        "line_no",
        "status_id", //0-Pending, 1-Success, 2-Failed
        "status_info"
        
    ];

    protected $casts = [
        "json_data"=>"json"
    ];

    public function file_import_batch(){
        return $this->belongsTo(FileImportBatch::class, 'file_import_batch_id');
    }
}
