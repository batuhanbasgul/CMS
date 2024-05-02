<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppMaintenanceModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'description',
        'short_description',
        'start_date',
        'color',
        'is_active',
        'lang_code'
    ];
}
