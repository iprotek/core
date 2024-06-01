<?php

namespace iProtek\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use iProtek\Core\Models\Factory;
use iProtek\Core\Models\UserAdminInfo;
use DB;

class UserAdmin extends Model
{
    use HasFactory,SoftDeletes;

    public $fillable = [
        'name',
        'username',
        'company_id',
        'email',
        'contact_no',
        'user_type', //0/null - admin, 1 - requestor, 2 - fie/cie, 3 - HR
        'is_verified',
        'region',
        'password',
        'is_active',
        'nopass',
        'is_protected',
        'lang',
        'recovery_requested',
        'recovery_requested_at',
        "recovery_requested_reason",
        "remember_token",
        "is_active",
        "receive_email_notif"
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $appends=[
        "created_from",
        //'user_factories',
        "recovery_requested_from",
        "default_image"
    ];

    public function getDefaultImageAttribute(){
        $fileUpload = FileUpload::where('target_name', 'user_admins')->where('target_id', $this->id)->first();
        if($fileUpload){
            return "/image-preview/".$fileUpload->id;
        }
        return "/images/temp-image.png";
    }

    public function getRecoveryRequestedFromAttribute()
    {
        if( $this->recovery_requested_at == null)
            return null;
      return \Carbon\Carbon::parse($this->recovery_requested_at)->diffForHumans();
    } 

    public function getCreatedFromAttribute()
    {
        if($this->created_at == null)
            return null;
      return $this->created_at->diffForHumans();
    } 

    public function getUserFactoriesAttribute(){
        //GET DEFAULT WHEN REQUESTOR
        if($this->user_type == 1)
        {
            $latest = UserAdminInfo::where( 'user_admin_id' , $this->id )->where( 'is_active' , 1 )->orderBy('id','DESC')->first();// $this->latest_info()->get();
            if($latest != null)
            {
                $factory = Factory::where('name', $latest->factory)->first();
                if($factory != null){
                    return [ $factory->id ];
                }
            }
            return [0];
        }
        //GET ALL FACTORIES
        else
        {
           $res = DB::select("select CONCAT('[', GROUP_CONCAT(factory_id), ']') as retval FROM user_admin_factories WHERE  user_admin_id = ? ",[$this->id]);
           return json_decode( $res[0]->retval );

        }
        return [];

    }




    public function latest_info(){
        $result = $this->hasOne('iProtek\Core\Models\UserAdminInfo', 'user_admin_id')->where('is_active', 1)->latest();
        if($result->count() <= 0)
            $result = $this->hasOne('iProtek\Core\Models\UserAdminInfo', 'user_admin_id')->latest();
        return $result;
    }





    public function request_update(){
        if($this->id == null)
            return;

        $company_id = $this->company_id;






    }
}
