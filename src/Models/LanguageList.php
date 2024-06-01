<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageList extends Model
{
    use HasFactory;
    protected $connection = 'mysql_translation';
    protected $fillable = [
        'code',
        'name'
    ];
}
