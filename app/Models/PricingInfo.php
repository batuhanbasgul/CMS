<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'title',
        'subtitle',
        'description',
        'image_large_screen',
        'image_small_screen',
        'hero',
        'keywords',
        'lang_code',
        'menu_name',
        'menu_code',
        'slug',
        'video_embed_code',
        'is_active',
        'show_button'
    ];
}
