<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'order',
        'menu_name',
        'is_active',
        'lang_code',
        'menu_code',
        'menu_logo',
        'content_slug',
        'content_id'
    ];
}
