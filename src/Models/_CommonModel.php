<?php

namespace iProtek\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use iProtek\Core\Models\UserAdmin;
use iProtek\Core\Models\UserAdminPayAccount;
use iProtek\Core\Traits\iProtekCommonTraits;

class _CommonModel extends Model
{
    use HasFactory, iProtekCommonTraits;


    public function created_info(){
        return $this->belongsTo(UserAdmin::class, 'created_by')->select('id','name');
    }

    public function updated_info(){
        return $this->belongsTo(UserAdmin::class, 'updated_by')->select('id', 'name');
    }

    public function deleted_info(){
        return $this->belongsTo(UserAdmin::class, 'deleted_by')->select('id', 'name');
    }

    public function pay_created_info(){
        return $this->belongsTo(UserAdminPayAccount::class, 'pay_created_by')->with(["user_admin"=>function($q){
            $q->select('id', 'name');
        }]);
    }

    public function pay_updated_info(){
        return $this->belongsTo(UserAdminPayAccount::class, 'pay_updated_by')->with(["user_admin"=>function($q){
            $q->select('id', 'name');
        }]);
    }

    public function pay_deleted_info(){
        return $this->belongsTo(UserAdminPayAccount::class, 'pay_deleted_by')->with(["user_admin"=>function($q){
            $q->select('id', 'name');
        }]);
    }
    
    public function file_uploads(){
        
        return $this->hasMany(FileUpload::class, 'target_id')->where('target_name', $this->getTable());
    }
 
    /**
     * Override the asDateTime method to prevent timezone conversion.
     *
     * @param  mixed  $value
     * @return \Carbon\Carbon
     */
    protected function asDateTime($value)
    {
        //return $value;
        $timezone = config('app.timezone') ?: 'UTC';
        return parent::asDateTime($value)->setTimezone($timezone);
    }
}
