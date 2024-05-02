<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'fav_icon',
        'hero',
        'keywords',
        'description',
        'google_analytic',
        'yandex_verification_code',
        'lang_code',
    ];
}
