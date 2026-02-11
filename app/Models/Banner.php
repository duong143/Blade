<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';

    protected $fillable = [
        'type',
        'title',
        'subtitle',
        'image',
        'link',
        'position',
        'is_active',
    ];
}
