<?php

namespace App\Models;

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
        "target_id"
    ];
}
