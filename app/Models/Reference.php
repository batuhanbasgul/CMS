<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_large_screen',
        'image_small_screen',
        'thumbnail_small_screen',
        'thumbnail_large_screen',
        'order',
        'keywords',
        'is_active',
        'reference_url',
        'lang_code',
        'video_embed_code',
        'slug'
    ];
}
