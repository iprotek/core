<?php

namespace iProtek\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "group_id",
        "name",
        "address",
        "coordinates",
        "status",
        "is_active",
        "data",
        "status_info",
        "pay_created_by",
        "pay_updated_by",
        "pay_deleted_by",
        "deleted_at"
    ];

    protected $casts = [
        "is_active"=>"boolean",
        "deleted_at"=>"datetime:Y-m-d H:i:s",
        "data"=>"json"
    ];
}
