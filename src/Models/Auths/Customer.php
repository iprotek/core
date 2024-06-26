<?php

namespace iProtek\Core\Models\Auths;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use iProtek\Core\Notifications\CustomerPasswordResetNotification;

class Customer extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;


	
	/**
     * The attributes that are mass assignable.
     *
     * @var string
     */
	//use Notifiable;

	protected $guard = "customer";
	
	//protected $table = 'user_admins';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 
        'name',
        'email',
        'contact_no',
        'gender',
        'password'
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

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomerPasswordResetNotification($token, $this->email));
    }
    
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \iProtek\Core\Notifications\CustomerVerifyEmailNotification($this->email));
    }
    
}
