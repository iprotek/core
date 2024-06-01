<?php

namespace iProtek\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use iProtek\Core\Models\UserAdmin; 

class _CommonModel extends Model
{
    use HasFactory;


    public function created_info(){
        return $this->belongsTo(UserAdmin::class, 'created_by')->select('id','name');
    }

    public function updated_info(){
        return $this->belongsTo(UserAdmin::class, 'updated_by')->select('id', 'name');
    }

    public function deleted_info(){
        return $this->belongsTo(UserAdmin::class, 'deleted_by')->select('id', 'name');
    }
    
    public function file_uploads(){
        
        return $this->hasMany(FileUpload::class, 'target_id')->where('target_name', $this->getTable());
    }
 
}
