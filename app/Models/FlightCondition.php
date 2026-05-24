<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightCondition extends Model
{
    protected $fillable = [
        'flight_id',
        'label',
        'value',
        'is_enabled',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }
}
