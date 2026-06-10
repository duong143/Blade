@extends('layout')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/payment.css') }}">
<style>
    .payment-qr-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
    }

    .payment-qr-modal.is-open {
        display: block;
    }

    .payment-qr-modal__overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
    }

    .payment-qr-modal__dialog {
        position: relative;
        width: min(92vw, 520px);
        max-height: 88vh;
        margin: 4vh auto 0;
        background: #fff;
        border-radius: 14px;
        padding: 28px 28px 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        z-index: 2;
        overflow-y: auto;
    }

    .payment-qr-modal__close {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 36px;
        height: 36px;
        border: 0;
        border-radius: 8px;
        background: #2f5fd0;
        color: #fff;
        font-size: 24px;
        line-height: 1;
        cursor: pointer;
    }

    .payment-qr-modal__title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2d3d;
        margin-bottom: 12px;
    }

    .payment-qr-modal__desc {
        font-size: 16px;
        color: #5b6575;
        margin-bottom: 18px;
    }

    .payment-qr-modal__info {
        background: #f8f9fb;
        border: 1px solid #e6e8ee;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 18px;
        line-height: 1.9;
        color: #1f2d3d;
    }

    .payment-qr-modal__qr {
        text-align: center;
        margin-bottom: 18px;
    }

    .payment-qr-modal__qr img {
        max-width: 240px;
        width: 100%;
        height: auto;
    }

    .payment-qr-modal__form {
        text-align: center;
    }

    .payment-qr-modal__submit {
        min-width: 240px;
        border: 0;
        border-radius: 8px;
        background: #4f8df0;
        color: #fff;
        font-weight: 700;
        font-size: 18px;
        padding: 14px 20px;
        cursor: pointer;
    }
</style>
@endpush

@section('info-strip')
{{-- Ẩn info-strip cho trang payment --}}
@endsection

@section('content')
@php
$startDateText = $booking->travel_start_date ? $booking->travel_start_date->format('d/m/Y') : '--';
$endDateText = $booking->travel_end_date ? $booking->travel_end_date->format('d/m/Y') : '--';

$routeText = trim(collect([
$booking->combo?->from_location,
$booking->combo?->to_location,
])->filter()->implode(' - '));

$selectedPaymentMethod = old('payment_method', $booking->payment_method ?: 'vnpt wallet');

$paymentExpiredAtTimestamp = $booking->payment_expired_at
? $booking->payment_expired_at->timestamp * 1000
: 0;

$isExpired = $booking->payment_expired_at && now()->greaterThan($booking->payment_expired_at);
$isPaid = $booking->payment_status === 'paid';
$shouldShowQrModal = !$isExpired && !$isPaid && !empty($booking->payment_method);

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

            <div class="payment-order-card__right payment-order-right">
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
                    <span>{{ number_format($booking->final_amount, 0, ',', '.') }} VND</span>
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
                    <span>Thẻ ATM tài khoản ngân hàng (Quét mã QR SePay)</span>
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
                        'selected_start_date' => optional($booking->travel_start_date)->format('Y-m-d'),
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

<div
    id="paymentQrModal"
    class="payment-qr-modal {{ $shouldShowQrModal ? 'is-open' : '' }}"
    aria-hidden="{{ $shouldShowQrModal ? 'false' : 'true' }}">

    <div class="payment-qr-modal__overlay" id="paymentQrOverlay"></div>

    <div class="payment-qr-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="paymentQrTitle">
        <button type="button" class="payment-qr-modal__close" id="paymentQrClose">×</button>

        <div class="payment-qr-modal__title" id="paymentQrTitle">
            Thanh toán đơn hàng
        </div>

        <div class="payment-qr-modal__desc">
            Quý khách vui lòng mở App ngân hàng quét mã QR dưới đây để thanh toán tự động qua SePay.
        </div>

        <div class="payment-qr-modal__info">
            <div><strong>Mã đơn hàng:</strong> {{ $booking->booking_code }}</div>
            <div><strong>Số tiền:</strong> {{ number_format($booking->final_amount, 0, ',', '.') }} VND</div>
            <div><strong>Nội dung CK:</strong> {{ $booking->booking_code }}</div>
        </div>

        @if(!$isExpired && !$isPaid && $booking->payment_method)
        @php
        // TÍCH HỢP SEPAY VIETQR ĐỘNG
        $BANK_ID = "TPB"; // Mã chuẩn của ngân hàng TPBank
        $ACCOUNT_NO = "20041432004"; // Số tài khoản thật của bạn
        $TEMPLATE = "qr_only";
        $AMOUNT = $booking->final_amount;
        $DESCRIPTION = $booking->booking_code;

        $vietQrUrl = "https://img.vietqr.io/image/{$BANK_ID}-{$ACCOUNT_NO}-{$TEMPLATE}.png?amount={$AMOUNT}&addInfo={$DESCRIPTION}";
        @endphp

        <div class="payment-qr-modal__qr" style="text-align: center;">
            <div style="display: inline-block; padding: 10px; background: #fff; border: 1px solid #e6e8ee; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 12px;">
                <img src="{{ $vietQrUrl }}" alt="QR thanh toán tự động qua SePay">
            </div>

            <div style="background: #fff3cd; border: 1px solid #ffeeba; color: #856404; font-size: 14px; border-radius: 8px; padding: 10px 14px; text-align: left; margin: 0 auto 16px; max-width: 440px; line-height: 1.5;">
                <strong>⚠️ Lưu ý quan trọng khi quét mã:</strong>
                <ul style="margin: 4px 0 0; padding-left: 18px;">
                    <li>Giữ nguyên nội dung chuyển khoản mặc định là: <strong style="color: #c0392b;">{{ $booking->booking_code }}</strong></li>
                    <li>Đơn hàng sẽ tự động chuyển trạng thái ngay khi tài khoản ngân hàng của bạn báo nhận được tiền thành công.</li>
                </ul>
            </div>
        </div>

        {{-- 🔥 Đã XÓA thẻ form chứa nút bấm "Tôi đã thanh toán" theo ý bạn --}}

        @elseif($isPaid)
        <div class="alert alert-success mt-3 mb-0">
            Đơn hàng đã được ghi nhận thanh toán thành công.
        </div>
        @elseif($isExpired)
        <div class="alert alert-danger mt-3 mb-0">
            Đơn hàng đã hết thời gian thanh toán.
        </div>
        @endif
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

        const qrModal = document.getElementById('paymentQrModal');
        const qrClose = document.getElementById('paymentQrClose');
        const qrOverlay = document.getElementById('paymentQrOverlay');

        function closeQrModal() {
            if (!qrModal) return;
            qrModal.classList.remove('is-open');
            qrModal.setAttribute('aria-hidden', 'true');
        }

        if (qrClose) {
            qrClose.addEventListener('click', closeQrModal);
        }

        if (qrOverlay) {
            qrOverlay.addEventListener('click', closeQrModal);
        }

        
        
        //  AUTO-CHECK PAYMENT STATUS (POLLING TỰ ĐỘNG CHUYỂN TRANG)
        
        const bookingId = "{{ $booking->id }}";
        const checkStatusUrl = "{{ url('combo/kiem-tra-trang-thai-thanh-toan') }}/" + bookingId;

       
        const paymentCheckInterval = setInterval(function() {
            fetch(checkStatusUrl)
                .then(response => response.json())
                .then(data => {
                   
                    if (data && data.payment_status === 'paid') {
                      
                        clearInterval(paymentCheckInterval);

                       
                        closeQrModal();

                
                        window.location.href = "{{ route('combo.index') }}";
                    }
                })
                .catch(error => console.error('Lỗi khi kiểm tra thanh toán:', error));
        }, 3000);
    });
</script>
@endsection