<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'name',
        'description',
        'keywords',
        'product_url',
        'image_small_screen',
        'image_large_screen',
        'thumbnail_large_screen',
        'thumbnail_small_screen',
        'thumbnail2_large_screen',
        'thumbnail2_small_screen',
        'hero',
        'price',
        'product_no',
        'order',
        'is_active',
        'lang_code',
        'slug'
    ];
}
