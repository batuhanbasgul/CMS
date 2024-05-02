<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [      
        'uid',  
        'name',
        'parent_category_uid',
        'description',
        'keywords',
        'image_small_screen',
        'image_large_screen',
        'hero',
        'lang_code',
        'order',
        'is_active',
        'slug'
    ];
}