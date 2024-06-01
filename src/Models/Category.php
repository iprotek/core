<?php

namespace iProtek\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        "group_id",
        "name",
        "pay_created_by",
        "pay_updated_by",
        "pay_deleted_by"
    ];
    
}
