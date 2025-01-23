<?php

namespace iProtek\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class FileImportBatch extends _CommonModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        
        "group_id",
        "pay_created_by",
        "pay_updated_by",
        "pay_deleted_by",

        "file_name",
        "file_ext",
        "target_field", //Name of the table or any for the purpose of table
        "settings",
        "total_lines",
        "line_processing",
        "line_succeed",
        "line_failed",
        "line_valid",
        "status_id", //0-Pending, 1-Completed, 2-Failed, 3-Processing, 4-Stopped, 5-Reset, 6-Restart
        "status_info"

    ];

    public $casts = [
        "created_at"=>"datetime:Y-m-d H:i:s A"
    ];

    public $appends = [
        "file_path"
    ];

    public $hidden = [
        "file_path"
    ];

    public function getFilePathAttribute(){
        try{
           return Storage::path('imports/'. $this->id.".".$this->file_ext) ;
        }catch(\Exception $ex){ 
        }
        return "";
    }

    public function created_by(){ 

        return $this->hasOne(UserAdminPayAccount::class, 'pay_app_user_account_id', 'pay_created_by')->with(['user_admin'=>function($q){
            $q->select('id', 'name');
        }]);
    }
}
