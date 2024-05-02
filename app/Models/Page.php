<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'title',
        'description',
        'short_description',
        'author',
        'keywords',
        'hero',
        'page_date',
        'image_large_screen',
        'image_small_screen',
        'thumbnail_small_screen',
        'thumbnail_large_screen',
        'is_active',
        'lang_code',
        'video_embed_code',
        'slug',
        'menu_name',
        'menu_code',
        'subtitle'
    ];
}
