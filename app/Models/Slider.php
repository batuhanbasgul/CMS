<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_large_screen',
        'image_small_screen',
        'order',
        'is_active',
        'is_mobile_active',
        'lang_code',
        'link',
        'video_embed_code',
        'logo',
        'logo_title'
    ];
}
