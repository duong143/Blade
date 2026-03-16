<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboDepartureSale extends Model
{
    protected $fillable = [
        'departure_id',
        'start_date',
        'end_date',
        'sale_percent',
        'sale_label',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'sale_percent' => 'integer',
    ];

    public function departure()
    {
        return $this->belongsTo(\App\Models\ComboDeparture::class, 'departure_id');
    }
}
