<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboDeparture extends Model
{
    protected $fillable = [
        'combo_id',
        'start_date',
        'end_date',
        'capacity',
        'sold',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'status'     => 'boolean',
    ];

    public function combo()
    {
        return $this->belongsTo(\App\Models\Combo::class);
    }

    // remaining = capacity - sold (không cần cột riêng)
    public function getSlotsRemainingAttribute(): int
    {
        $capacity = (int) ($this->capacity ?? 0);
        $sold = (int) ($this->sold ?? 0);
        return max(0, $capacity - $sold);
    }
    public function prices()
    {
        return $this->hasMany(\App\Models\ComboDeparturePrice::class, 'departure_id');
    }
    public function sales()
    {
        return $this->hasMany(\App\Models\ComboDepartureSale::class, 'departure_id');
    }
    public function getSalePercentForDate($date): int
    {
        $d = \Carbon\Carbon::parse($date)->format('Y-m-d');

        $sale = $this->sales->first(function ($s) use ($d) {
            $start = optional($s->start_date)->format('Y-m-d');
            $end = optional($s->end_date)->format('Y-m-d');

            return $start && $end && $d >= $start && $d <= $end;
        });

        return (int) ($sale->sale_percent ?? 0);
    }

    public function getFinalPriceFor($passengerType, $date): int
    {
        $passengerType = strtolower($passengerType);
        $base = (int) ($this->prices->firstWhere('passenger_type', $passengerType)?->base_price ?? 0);

        $salePercent = $this->getSalePercentForDate($date);

        $final = (int) round($base * (100 - $salePercent) / 100);

        return max(0, $final);
    }
}
