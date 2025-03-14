<?php

namespace iProtek\Core\Models\Auths;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use iProtek\Core\Models\FileUpload;
use iProtek\Core\Helpers\BranchSelectionHelper;
use iProtek\Core\Helpers\PayHttp;
use iProtek\Xrac\Models\XuserRole;
use iProtek\Xrac\Models\Xrole;


class Admin extends Authenticatable
{
    //use HasApiTokens, HasFactory, Notifiable,SoftDeletes;
    use HasFactory, Notifiable, SoftDeletes;
    
	/**
     * The attributes that are mass assignable.
     *
     * @var string
     */
	//use Notifiable;

	protected $guard = "admin";
	
	//protected $table = 'user_admins';
	protected $table = 'user_admins';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'company_id',
        'name',
        'email',
        'password',
        'user_type',
        'lang',
        'lang',
        'region',
        'contact_no',
        'is_active'
    ];
    protected $appends=[
        "default_image",
        "super_admin",
        "branch_user_type_id",
        "branch_user_type_name"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        "is_active"=>"boolean"
    ];

    public function getBranchUserTypeIdAttribute(){

        if($this->can('superadmin')){
            return 0;
        }


        $branch_id = BranchSelectionHelper::get();
        $pay_account_id = PayHttp::pay_account_id($this);

        $role = XuserRole::where(["app_account_id"=>$pay_account_id, "branch_id"=>$branch_id])->first();
        if($role){
            return $role->xrole_id;
        }

        return null;
    }

    public function getBranchUserTypeNameAttribute(){

        if($this->can('superadmin')){
            return "SuperAdmin";
        }


        $branch_id = BranchSelectionHelper::get();
        $pay_account_id = PayHttp::pay_account_id($this);

        $role = XuserRole::where(["app_account_id"=>$pay_account_id, "branch_id"=>$branch_id])->first();
        if($role){

            $xrole = Xrole::find($role->xrole_id);
            if($xrole){
                return ($role->is_default ? "": "Customized-").$xrole->name;
            }

        }

        return "None";

    }

    public function getSuperAdminAttribute(){
        return $this->can('superadmin');
    }

    public function getDefaultImageAttribute(){
        $fileUpload = FileUpload::where('target_name', 'user_admins')->where('target_id', $this->id)->orderBy('is_default','DESC')->first();
        if($fileUpload){
            //return "/image-preview/".$fileUpload->id;
            return $fileUpload->link;
        }
        return "/images/temp-image.png";
    }
}
