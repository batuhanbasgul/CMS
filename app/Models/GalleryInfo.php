<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'title',
        'subtitle',
        'menu_name',
        'description',
        'short_description',
        'image_large_screen',
        'image_small_screen',
        'hero',
        'keywords',
        'video_embed_code',
        'lang_code',
        'menu_code',
        'slug',
        'is_active'
    ];
}
