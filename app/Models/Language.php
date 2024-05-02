<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable=[
        'lang_code',
        'lang_name',
        'icon',
        'is_active',
        'is_default',
        'order',
    ];
}