<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\NewsImage;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'is_active',
    ];

    public function images()
    {
        return $this->hasMany(NewsImage::class);
    }
}
