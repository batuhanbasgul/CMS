<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageManager extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'height',
        'width',
        'ratio',
        'file_size',
        'quality'
    ];
}
