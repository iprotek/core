<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grouping extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "group_id",
        "name",
        "pay_created_by",
        "pay_updated_by",
        "pay_deleted_by"
    ];
}
