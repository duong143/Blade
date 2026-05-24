<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    protected $fillable = [
        'code',
        'title',
        'slug',
        'image',
        'content_image',
        'from_location',
        'to_location',
        'duration_days',
        'duration_nights',
        'short_desc',
        'hotel_amenities',
        'description',
        'itinerary_detail',
        'preorder_days',
        'status',
    ];

    protected $casts = [
        'content_image' => 'array',
    ];

    public function departures()
    {
        return $this->hasMany(\App\Models\ComboDeparture::class);
    }

    public function images()
    {
        return $this->hasMany(\App\Models\ComboImage::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
    public function discountCodes()
    {
        return $this->hasMany(\App\Models\DiscountCode::class)
            ->orderByDesc('id');
    }
}
