<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    
    protected $fillable = [ 
        "first_name",
        "last_name",
        "name",
        "contact_no",
        "email",
        "gender",
        "username",
        "address",
        "created_by",
        "updated_by",
        "deleted_by",
        "address",
        "is_email_confirmed",
        "is_contact_verified",
        "password",
        "email_verified_at",
        "remember_token",
        "contact_no_verified_at"
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

}
