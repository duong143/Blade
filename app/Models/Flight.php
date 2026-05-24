<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flight extends Model
{
    protected $fillable = [
        'airline_id',
        'departure_airport_id',
        'arrival_airport_id',
        'flight_number',
        'aircraft',
        'seat_layout',
        'seat_pitch',
        'departure_date',
        'departure_time',
        'arrival_date',
        'arrival_time',
        'duration_minutes',
        'seat_class',
        'adult_price',
        'child_price',
        'infant_price',
        'tax_fee',
        'carry_on_baggage',
        'checked_baggage',
        'other_benefits',
        'fare_points',
        'display_order',
        'total_seats',
        'available_seats',
        'is_direct',
        'is_active',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'arrival_date' => 'date',
        'is_direct' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class);
    }

    public function departureAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    public function arrivalAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }

    public function priceItems(): HasMany
    {
        return $this->hasMany(FlightPriceItem::class)->orderBy('sort_order')->orderBy('id');
    }

    public function conditions(): HasMany
    {
        return $this->hasMany(FlightCondition::class)->orderBy('sort_order')->orderBy('id');
    }

    public function getDurationTextAttribute(): string
    {
        $hours = intdiv((int) $this->duration_minutes, 60);
        $minutes = (int) $this->duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }

        if ($hours > 0) {
            return $hours . 'h';
        }

        return $minutes . 'm';
    }

    public function getTotalAdultPriceAttribute(): int
    {
        return (int) $this->adult_price + (int) $this->tax_fee;
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(FlightBooking::class);
    }
}
