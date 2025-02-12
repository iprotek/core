<?php

namespace iProtek\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceAccessTriggerLog extends Model
{
    use HasFactory;

    protected $fillable = [
        "device_access_id",
        "command",
        "response",
        "log_info",
        "target_name",
        "target_id",

        "group_id",
        "pay_created_by",
        "pay_updated_by",
        "pay_deleted_by",
        "status_id"
    ];
}
