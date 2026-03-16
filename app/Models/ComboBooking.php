<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboBooking extends Model
{
    protected $fillable = [
        'combo_id',
        'departure_id',
        'adult',
        'child',
        'infant',
        'total_passengers',
        'adult_final_price',
        'child_final_price',
        'infant_final_price',
        'sale_percent',
        'total_amount',
        'contact_name',
        'contact_phone',
        'contact_email',
        'invoice_required',
        'invoice_tax',
        'invoice_company',
        'invoice_address',
        'invoice_email',
        'booking_code',
        'payment_method',
        'payment_status',
        'payment_expired_at',
        'booking_status',
    ];

    protected $casts = [
        'invoice_required' => 'boolean',
        'adult' => 'integer',
        'child' => 'integer',
        'infant' => 'integer',
        'total_passengers' => 'integer',
        'adult_final_price' => 'integer',
        'child_final_price' => 'integer',
        'infant_final_price' => 'integer',
        'sale_percent' => 'integer',
        'total_amount' => 'integer',
        'payment_expired_at' => 'datetime',
    ];

    public function combo()
    {
        return $this->belongsTo(\App\Models\Combo::class);
    }

    public function departure()
    {
        return $this->belongsTo(\App\Models\ComboDeparture::class, 'departure_id');
    }
}
