<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementInfo extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'uid',
        'title',
        'description',
        'author',
        'short_description',
        'image_large_screen',
        'image_small_screen',
        'hero',
        'keywords',
        'lang_code',
        'video_embed_code',
        'page_limit',
        'menu_name',
        'menu_code',
        'slug',
        'is_active',
        'subtitle',
        'announcement_date',
    ];
}
