<?php

namespace iProtek\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAdminPayAccount extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        "user_admin_id",
        "browser_session_id",
        "default_proxy_group_id",
        "pay_app_user_account_id",
        "email",
        "own_proxy_group_id",
        "access_token",
        "refresh_token",
        "pay_created_by",
        "pay_updated_by",
        "pay_deleted_by",
        "sub_account_group_id",
    ];

    public $hidden = [
        "access_token",
        "refresh_token"
    ];

    public function user_admin(){
        return $this->belongsTo(UserAdmin::class,'user_admin_id');
    }

}
