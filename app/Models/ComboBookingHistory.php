<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboBookingHistory extends Model
{
    protected $fillable = [
        'combo_booking_id',
        'action',
        'field_name',
        'old_value',
        'new_value',
        'changed_by_type',
        'changed_by_id',
        'changed_by_name',
        'note',
    ];

    public function booking()
    {
        return $this->belongsTo(ComboBooking::class, 'combo_booking_id');
    }
}
