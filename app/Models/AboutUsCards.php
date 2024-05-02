<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsCards extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'title',
        'subtitle',
        'icon',
        'description',
        'image_large_screen',
        'image_small_screen',
        'thumbnail_large_screen',
        'thumbnail_small_screen',
        'order',
        'is_active',
        'lang_code',
    ];
}
