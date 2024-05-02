<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'logo_large_screen',
        'logo_small_screen',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'pinterest',
        'google',
        'lang_code',
        'linkedin'
    ];
}
