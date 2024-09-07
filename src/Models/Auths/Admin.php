<?php

namespace iProtek\Core\Models\Auths;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use iProtek\Core\Models\FileUpload;
class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;


	
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
        "default_image"
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getDefaultImageAttribute(){
        $fileUpload = FileUpload::where('target_name', 'user_admins')->where('target_id', $this->id)->first();
        if($fileUpload){
            return "/image-preview/".$fileUpload->id;
        }
        return "/images/temp-image.png";
    }
}
