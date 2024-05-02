<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'thumbnail',
        'order',
        'announcement_id',
        'is_active'
    ];
}
