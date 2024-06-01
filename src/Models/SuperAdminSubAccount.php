<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperAdminSubAccount extends Model
{
    use HasFactory;
    use HasFactory;

    protected $fillable = [
        "email",
        "sub_account_group_id",
        "user_type"
    ];

    public function target_workspace(){
        return $this->hasOne(gUserAdminPayAccount::class, 'pay_app_user_account_id', 'sub_account_group_id');// BillingUserAdminPayAccount::where('sub_account_group_id', $this->sub_account_group_id)->first();
    }
}
