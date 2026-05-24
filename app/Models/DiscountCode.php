<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class DiscountCode extends Model
{
    protected $fillable = [
        'combo_id',
        'code',
        'discount_percent',
        'valid_from',
        'valid_to',
        'checkin_from',
        'checkin_to',
        'status',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date',
        'checkin_from' => 'date',
        'checkin_to' => 'date',
        'status' => 'boolean',
        'discount_percent' => 'integer',
    ];

    public function combo()
    {
        return $this->belongsTo(\App\Models\Combo::class);
    }

    public function isExpired(): bool
    {
        return $this->valid_to && now()->gt($this->valid_to->copy()->endOfDay());
    }

    public function isInValidTime(): bool
    {
        if (!$this->status) {
            return false;
        }

        $today = now()->startOfDay();

        if ($this->valid_from && $today->lt($this->valid_from->copy()->startOfDay())) {
            return false;
        }

        if ($this->valid_to && $today->gt($this->valid_to->copy()->endOfDay())) {
            return false;
        }

        return true;
    }

    public function isApplicableForTravelDate($travelDate): bool
    {
        if (!$this->isInValidTime()) {
            return false;
        }

        $travelDate = $travelDate instanceof Carbon
            ? $travelDate->copy()->startOfDay()
            : Carbon::parse($travelDate)->startOfDay();

        if ($this->checkin_from && $travelDate->lt($this->checkin_from->copy()->startOfDay())) {
            return false;
        }

        if ($this->checkin_to && $travelDate->gt($this->checkin_to->copy()->endOfDay())) {
            return false;
        }

        return true;
    }

    public function calculateDiscountAmount(int $amount): int
    {
        if ($amount <= 0) {
            return 0;
        }

        return (int) round($amount * $this->discount_percent / 100);
    }
}
