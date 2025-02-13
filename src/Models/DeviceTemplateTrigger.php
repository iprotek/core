<?php

namespace iProtek\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceTemplateTrigger extends Model
{
    use HasFactory;

    
    protected $fillable = [
        
        "group_id",
        "pay_created_by",
        "pay_updated_by",
        "pay_deleted_by",
        
        "trigger_name",
        "target_name",
        "target_id",
        "device_access_id",

        "enable_register",
        "register_command_template",
        "enable_update",
        "update_command_template",
        "enable_inactive",
        "inactive_command_template",
        "enable_active",
        "active_command_template",
        "enable_remove",
        "remove_command_template",
         
        "is_active",
        "inactive_reason"

    ];

    protected $casts = [
        "is_active"=>"boolean",
        "enable_register"=>"boolean",
        "enable_update"=>"boolean",
        "enable_inactive"=>"boolean",
        "enable_active"=>"boolean",
        "enable_remove"=>"boolean"
    ];
}
