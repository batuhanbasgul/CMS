<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'logo_large_screen',
        'logo_small_screen',
        'copy_right',
        'lang_code',
    ];
}
