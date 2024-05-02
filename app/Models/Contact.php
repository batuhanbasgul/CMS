<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'menu_name',
        'title',
        'image_large_screen',
        'image_small_screen',
        'hero',
        'subtitle',
        'keywords',
        'description',
        'short_description',
        'phone1',
        'phone2',
        'gsm1',
        'gsm2',
        'fax',
        'email',
        'city',
        'address',
        'google_maps',
        'lang_code',
        'menu_code',
        'slug',
        'is_active',
        'video_embed_code'
    ];
}
