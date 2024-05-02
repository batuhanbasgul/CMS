<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'title',
        'subtitle',
        'description',
        'short_description',
        'menu_name',
        'keywords',
        'image_large_screen',
        'image_small_screen',
        'hero',
        'video_embed_code',
        'page_limit',
        'lang_code',
        'menu_code',
        'slug',
        'is_active'
    ];
}
