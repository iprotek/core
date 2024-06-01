<?php

namespace iProtek\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAdminInfo extends Model
{
    use HasFactory, SoftDeletes;
    public $fillable = [
        'user_admin_id',
        'company_id',
        'first_name',
        'middle_name',
        'last_name',
        'position', //0/null - admin, 1 - requestor, 2 - fie/cie, 3 - HR
        'department',
        'line',
        'factory',
        'is_active',
        'status_id',
        'region'
    ];
}
