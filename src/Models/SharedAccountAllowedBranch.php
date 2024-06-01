<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedAccountAllowedBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        "group_id",
        "branch_id",
        "shared_account_id",
        "is_allowed",
        "pay_created_by",
        "pay_updated_by",
        "pay_deleted_by"
    ];

}
