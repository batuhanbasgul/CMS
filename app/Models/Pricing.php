<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image_large_screen',
        'image_small_screen',
        'thumbnail_small_screen',
        'thumbnail_large_screen',
        'order',
        'keywords',
        'is_active',
        'pricing_url',
        'lang_code',
        'video_embed_code',
        'slug',
        'entry1',
        'entry2',
        'entry3',
        'entry4',
        'entry5',
        'entry6',
        'entry7',
        'entry8',
        'entry9',
        'entry10',
    ];
}
