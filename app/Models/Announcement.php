<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'title',
        'subtitle',
        'description',
        'short_description',
        'author',
        'keywords',
        'hero',
        'announcement_date',
        'image_large_screen',
        'image_small_screen',
        'thumbnail_small_screen',
        'thumbnail_large_screen',
        'order',
        'is_active',
        'lang_code',
        'video_embed_code',
        'slug'
    ];
}
