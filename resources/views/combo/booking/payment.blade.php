@extends('layout')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endpush

@section('info-strip')
{{-- Ẩn info-strip cho trang payment --}}
@endsection

@section('content')
@php
$startDateText = $booking->departure?->start_date ? $booking->departure->start_date->format('d/m/Y') : '--';
$endDateText = $booking->departure?->end_date ? $booking->departure->end_date->format('d/m/Y') : '--';

$routeText = trim(collect([
$booking->combo?->from_location,
$booking->combo?->to_location,
])->filter()->implode(' - '));

$selectedPaymentMethod = old('payment_method', $booking->payment_method ?: 'vnpt');

$paymentExpiredAtTimestamp = $booking->payment_expired_at
? $booking->payment_expired_at->timestamp * 1000
: 0;

$isExpired = $booking->payment_expired_at && now()->greaterThan($booking->payment_expired_at);
@endphp

<div class="payment-page">

    <div class="booking-step-background">
        <div class="container">
            <div class="booking-step-wrap">
                <div class="booking-step-bar">

                    <div class="booking-step-item is-done">
                        <span class="step-icon step-icon-check">✓</span>
                        <span class="step-text">Thông tin đặt vé</span>
                    </div>

                    <span class="booking-step-arrow">
                        <img src="{{ asset('images/airplane_muiten.png') }}" alt="">
                    </span>

                    <div class="booking-step-item is-done">
                        <span class="step-icon step-icon-check">✓</span>
                        <span class="step-text">Thông tin hành khách</span>
                    </div>

                    <span class="booking-step-arrow">
                        <img src="{{ asset('images/airplane_muiten.png') }}" alt="">
                    </span>

                    <div class="booking-step-item is-active">
                        <span class="step-icon step-icon-number">03</span>
                        <span class="step-text">Thanh toán</span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="container my-4">

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
        @endif

        <div class="payment-order-card">
            <div class="payment-order-left">
                <div class="payment-order-illustration">
                    <img src="{{ asset('images/TayTaiLieu.png') }}" alt="">
                </div>

                <div class="payment-order-info">
                    <div class="payment-order-code">
                        Mã đơn hàng:
                        <span>#{{ $booking->booking_code }}</span>
                    </div>

                    <div class="payment-order-route">
                        {{ $routeText !== '' ? $routeText : '--' }}
                    </div>
                </div>
            </div>

            <div class="payment-order-right">
                <div class="payment-order-time">

                    @if($isExpired)

                    (Đơn hàng đã hết thời gian thanh toán)

                    @else

                    (Thanh toán đơn hàng trong
                    <span
                        id="paymentCountdown"
                        data-expired-ts="{{ $paymentExpiredAtTimestamp }}">
                        --
                    </span>)
                    @endif

                </div>


                <div class="payment-order-amount">
                    Cần thanh toán:
                    <span>{{ number_format($booking->total_amount, 0, ',', '.') }} VND</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('combo.payment.store') }}" class="payment-method-card">
            @csrf

            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

            <div class="payment-method-title">1. Chọn hình thức thanh toán</div>
            <div class="payment-method-desc">
                Quý khách vui lòng lựa chọn 1 trong các hình thức thanh toán dưới đây để hoàn tất quá trình đặt vé
            </div>

            <div class="payment-method-box">
                <label class="payment-radio-row">
                    <input
                        type="radio"
                        name="payment_method"
                        value="atm"
                        {{ $selectedPaymentMethod === 'atm' ? 'checked' : '' }}>
                    <span>Thẻ ATM tài khoản ngân hàng</span>
                </label>

                <label class="payment-radio-row">
                    <input
                        type="radio"
                        name="payment_method"
                        value="international"
                        {{ $selectedPaymentMethod === 'international' ? 'checked' : '' }}>
                    <span>Thẻ thanh toán quốc tế</span>
                </label>

                <label class="payment-radio-row">
                    <input
                        type="radio"
                        name="payment_method"
                        value="vnpt"
                        {{ $selectedPaymentMethod === 'vnpt' ? 'checked' : '' }}>
                    <span>Ví VNPT Money</span>
                </label>
            </div>

            <div class="payment-action-wrap">
                <a
                    href="{{ route('combo.passenger', [
                'combo_id' => $booking->combo_id,
                'slug' => $booking->combo?->slug,
                'departure_id' => $booking->departure_id,
                'adult' => $booking->adult,
                'child' => $booking->child,
                'infant' => $booking->infant,
            ]) }}"
                    class="payment-btn-back">
                    <span class="back-arrow"><img src="{{ asset('images/vector.png') }}" alt=""></span>
                    Quay lại Đơn hàng
                </a>

                <button
                    type="submit"
                    class="payment-btn-next"
                    {{ $isExpired ? 'disabled' : '' }}>
                    {{ $isExpired ? 'Đơn hàng đã hết hạn' : 'Thanh toán' }}
                </button>
            </div>
        </form>

    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('paymentCountdown');
        if (!el) return;

        const expiredAt = parseInt(el.dataset.expiredTs || '0', 10);

        if (!expiredAt || Number.isNaN(expiredAt)) {
            el.textContent = '00:00:00';
            return;
        }

        function updateTimer() {
            const now = Date.now();
            let diff = expiredAt - now;

            if (diff <= 0) {
                el.textContent = '00:00:00';

                const btn = document.querySelector('.payment-btn-next');
                if (btn) {
                    btn.disabled = true;
                    btn.innerText = 'Đơn hàng đã hết hạn';
                }

                return;
            }

            const hours = Math.floor(diff / (1000 * 60 * 60));
            diff %= (1000 * 60 * 60);

            const minutes = Math.floor(diff / (1000 * 60));
            diff %= (1000 * 60);

            const seconds = Math.floor(diff / 1000);

            el.textContent =
                String(hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    });
</script>
@endsection