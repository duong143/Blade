<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboImage extends Model
{
    protected $fillable = [
        'combo_id',
        'image_path',
        'is_cover',
        'sort_order',
    ];

    protected $casts = [
        'is_cover' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function combo()
    {
        return $this->belongsTo(\App\Models\Combo::class);
    }
}
