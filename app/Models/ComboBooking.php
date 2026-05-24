<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboBooking extends Model
{
    protected $fillable = [
        'combo_id',
        'departure_id',
        'discount_code_id',
        'discount_code',
        'travel_start_date',
        'travel_end_date',
        'adult',
        'child',
        'infant',
        'total_passengers',
        'adult_final_price',
        'child_final_price',
        'infant_final_price',
        'sale_percent',
        'discount_code_percent',
        'discount_code_amount',
        'final_amount',
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
        'paid_at',
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
        'discount_code_percent' => 'integer',
        'discount_code_amount' => 'integer',
        'final_amount' => 'integer',
        'total_amount' => 'integer',
        'travel_start_date' => 'date',
        'travel_end_date' => 'date',
        'payment_expired_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function combo()
    {
        return $this->belongsTo(\App\Models\Combo::class);
    }

    public function departure()
    {
        return $this->belongsTo(\App\Models\ComboDeparture::class, 'departure_id');
    }

    public function discountCodeRelation()
    {
        return $this->belongsTo(\App\Models\DiscountCode::class, 'discount_code_id');
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'Chờ thanh toán',
            'paid' => 'Đã thanh toán',
            'expired' => 'Hết hạn',
            default => $this->payment_status ?? '--',
        };
    }

    public function getBookingStatusLabelAttribute(): string
    {
        return match ($this->booking_status) {
            'draft' => 'Nháp',
            'pending_payment' => 'Chờ thanh toán',
            'confirmed' => 'Đã xác nhận',
            'cancelled' => 'Đã hủy',
            'expired' => 'Hết hạn',
            default => $this->booking_status ?? '--',
        };
    }

    public function getPaymentStatusBadgeClassAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'badge-warning',
            'paid' => 'badge-success',
            'expired' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    public function getBookingStatusBadgeClassAttribute(): string
    {
        return match ($this->booking_status) {
            'draft' => 'badge-secondary',
            'pending_payment' => 'badge-warning',
            'confirmed' => 'badge-primary',
            'cancelled' => 'badge-dark',
            'expired' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    public function histories()
    {
        return $this->hasMany(\App\Models\ComboBookingHistory::class, 'combo_booking_id')
            ->latest();
    }
}
