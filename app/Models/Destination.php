<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use HasFactory;

    protected $table = 'destinations';

    protected $fillable = [
        'name',
        'slug',
        'image',
        'short_description',
        'recommended_months',
        'status',
    ];

    protected $casts = [
        'recommended_months' => 'array',
        'status' => 'boolean',
    ];

    public function attractions(): HasMany
    {
        return $this->hasMany(Attraction::class, 'destination_id', 'id');
    }
}
