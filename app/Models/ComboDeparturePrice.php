<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboDeparturePrice extends Model
{
    protected $fillable = [
        'departure_id',
        'passenger_type',
        'base_price',
    ];

    protected $casts = [
        'base_price' => 'integer',
    ];

    public function departure()
    {
        return $this->belongsTo(\App\Models\ComboDeparture::class, 'departure_id');
    }
}
