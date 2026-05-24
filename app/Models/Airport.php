<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Airport extends Model
{
    protected $fillable = [
        'code',
        'name',
        'city',
        'country',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function departingFlights(): HasMany
    {
        return $this->hasMany(Flight::class, 'departure_airport_id');
    }

    public function arrivingFlights(): HasMany
    {
        return $this->hasMany(Flight::class, 'arrival_airport_id');
    }
}
