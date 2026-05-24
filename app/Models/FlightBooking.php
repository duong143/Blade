<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightBooking extends Model
{
    protected $fillable = [
        'flight_id',
        'booking_code',
        'adult',
        'child',
        'infant',
        'total_passengers',
        'adult_price',
        'child_price',
        'infant_price',
        'tax_fee',
        'total_amount',
        'final_amount',
        'contact_name',
        'contact_phone',
        'contact_email',
        'payment_method',
        'payment_status',
        'booking_status',
        'payment_expired_at',
        'paid_at',
        'admin_note',
    ];

    protected $casts = [
        'payment_expired_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }
}
