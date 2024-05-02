<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'title',
        'description',
        'menu_name',
        'image_large_screen',
        'image_small_screen',
        'hero',
        'keywords',
        'video_embed_code',
        'lang_code',
        'menu_code',
        'card_limit',
        'slug',
        'is_active',
        'subtitle',
        'short_description'
    ];

}
