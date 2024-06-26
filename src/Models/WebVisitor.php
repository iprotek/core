<?php

namespace iProtek\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebVisitor extends Model
{
    use HasFactory;
    public $fillable = [
        'ip_address',
        'user_agent'
    ];
}
