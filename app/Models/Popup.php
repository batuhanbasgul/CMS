<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'short_description',
        'start_date',
        'end_date',
        'video_url',
        'image_large_screen',
        'image_small_screen',
        'is_mobile_active',
        'is_desktop_active',
        'lang_code',
    ];
}
