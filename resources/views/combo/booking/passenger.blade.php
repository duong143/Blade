@extends('layout')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/passenger.css') }}">
@endpush

@section('info-strip')
@endsection

@section('content')

@php
$summaryTitle = $combo->title;

$roomCount = 0;

$passengerText = collect([
$adult > 0 ? $adult . ' người lớn' : null,
$child > 0 ? $child . ' trẻ em' : null,
$infant > 0 ? $infant . ' em bé' : null,
])->filter()->implode(', ');

$summarySub = $roomCount . ' phòng';

if ($passengerText !== '') {
$summarySub .= ' | ' . $passengerText;
}

$startDateText = $departure->start_date ? $departure->start_date->format('d/m/Y') : '--';
$endDateText = $departure->end_date ? $departure->end_date->format('d/m/Y') : '--';

$adultDiscount = max(0, (int) $adultBasePrice - (int) $adultFinalPrice);
$childDiscount = max(0, (int) $childBasePrice - (int) $childFinalPrice);
$infantDiscount = max(0, (int) $infantBasePrice - (int) $infantFinalPrice);

$discountAmount =
($adult * $adultDiscount) +
($child * $childDiscount) +
($infant * $infantDiscount);
@endphp

<div class="passenger-page">

    <div class="booking-step-background">
        <div class="container">
            <div class="booking-step-wrap">
                <div class="booking-step-bar">

                    <div class="booking-step-item is-done">
                        <span class="step-icon step-icon-check">✓</span>
                        <span class="step-text">Chọn Combo</span>
                    </div>

                    <span class="booking-step-arrow">
                        <img src="{{ asset('images/airplane_muiten.png') }}">
                    </span>

                    <div class="booking-step-item is-active">
                        <span class="step-icon step-icon-number">02</span>
                        <span class="step-text">Thông tin hành khách</span>
                    </div>

                    <span class="booking-step-arrow">
                        <img src="{{ asset('images/airplane_muiten.png') }}">
                    </span>

                    <div class="booking-step-item is-disabled">
                        <span class="step-icon step-icon-number">03</span>
                        <span class="step-text">Thanh toán</span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="container my-4">
        <div class="row g-4">

            {{-- LEFT --}}
            <div class="col-lg-8">

                <form method="POST" action="{{ route('combo.passenger.store') }}">
                    @csrf

                    <input type="hidden" name="combo_id" value="{{ $combo->id }}">
                    <input type="hidden" name="departure_id" value="{{ $departure->id }}">

                    <input type="hidden" name="adult" value="{{ $adult }}">
                    <input type="hidden" name="child" value="{{ $child }}">
                    <input type="hidden" name="infant" value="{{ $infant }}">

                    {{-- CONTACT --}}
                    <div class="bg-white rounded-3 shadow-sm p-4">

                        <h6 class="fw-semibold mb-3">Thông tin liên hệ</h6>

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label small text-muted">
                                    Họ và tên
                                </label>

                                <input
                                    type="text"
                                    name="contact_name"
                                    class="form-control"
                                    placeholder="Nhập Họ tên"
                                    required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small text-muted">
                                    Số điện thoại *
                                </label>

                                <input
                                    type="text"
                                    name="contact_phone"
                                    class="form-control"
                                    placeholder="Nhập số điện thoại"
                                    required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small text-muted">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="contact_email"
                                    class="form-control"
                                    placeholder="Nhập email">
                            </div>

                        </div>
                    </div>


                    {{-- INVOICE --}}
                    <div class="bg-white rounded-3 shadow-sm p-4 mt-3">

                        <h6 class="fw-bold mb-2">Xuất hóa đơn</h6>

                        <div class="small text-muted mb-3">
                            Khi cần xuất hoá đơn GTGT, Quý khách vui lòng gửi yêu cầu trong 48h.
                        </div>

                        <div class="form-check mb-3">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="invoice_required"
                                value="1">

                            <label class="form-check-label">
                                Tôi muốn xuất hóa đơn
                            </label>

                        </div>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label small text-muted">
                                    Mã số thuế
                                </label>

                                <input
                                    type="text"
                                    name="invoice_tax"
                                    class="form-control"
                                    placeholder="Mã số thuế">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small text-muted">
                                    Tên công ty
                                </label>

                                <input
                                    type="text"
                                    name="invoice_company"
                                    class="form-control"
                                    placeholder="Tên công ty">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small text-muted">
                                    Địa chỉ công ty
                                </label>

                                <input
                                    type="text"
                                    name="invoice_address"
                                    class="form-control"
                                    placeholder="Địa chỉ công ty">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small text-muted">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="invoice_email"
                                    class="form-control"
                                    placeholder="Email">
                            </div>

                        </div>

                    </div>


                    {{-- BUTTON --}}
                    <div class="d-flex justify-content-center gap-3 mt-4">

                        <a
                            href="{{ route('combo.show', ['slug' => $combo->slug]) }}?departure={{ $departure->id }}"
                            class="passenger-btn-back">

                            <span class="back-arrow">
                                <img src="{{ asset('images/vector.png') }}">
                            </span>
                            Quay lại
                        </a>

                        <button
                            type="submit"
                            class="passenger-btn-next">
                            Tiếp tục
                        </button>

                    </div>

                </form>

            </div>


            {{-- RIGHT --}}
            <div class="col-lg-4">

                <div class="booking-summary-card">

                    <div class="booking-summary-head">

                        <div class="booking-summary-title">
                            {{ $summaryTitle }}
                        </div>

                        <div class="booking-summary-sub">
                            {{ $summarySub }}
                        </div>

                    </div>

                    <div class="booking-summary-body">

                        <div class="booking-summary-row">
                            <span>Ngày khởi hành:</span>
                            <span>{{ $startDateText }}</span>
                        </div>

                        <div class="booking-summary-row">
                            <span>Ngày kết thúc:</span>
                            <span>{{ $endDateText }}</span>
                        </div>

                        <div class="booking-summary-row">
                            <span>Số lượng khách:</span>
                            <span>{{ $totalPassengers }}</span>
                        </div>

                        <div class="booking-summary-row">
                            <span>Giảm giá:</span>

                            <span class="booking-summary-discount">

                                {{ $discountAmount > 0
                                ? '-' . number_format($discountAmount,0,',','.') . 'đ'
                                : '0đ' }}

                            </span>

                        </div>

                        <div class="booking-summary-total-row">

                            <span class="booking-summary-total-label">
                                Tổng tiền:
                            </span>

                            <div class="booking-summary-total-right">

                                <div class="booking-summary-total-price">
                                    {{ number_format($totalAmount,0,',','.') }}đ
                                </div>

                                <div class="booking-summary-total-note">
                                    (Giá vé đã bao gồm thuế<br>
                                    và phụ phí đi kèm)
                                </div>

                            </div>

                        </div>

                        <div class="booking-summary-divider"></div>

                        <div class="booking-summary-change">

                            <a href="{{ route('combo.show', ['slug' => $combo->slug]) }}?departure={{ $departure->id }}">
                                Thay đổi lịch trình
                            </a>

                        </div>

                    </div>

                </div>

                {{-- COUPON --}}
                <div class="booking-coupon-card">

                    <div class="booking-coupon-title">
                        Mã giảm giá
                    </div>

                    <div class="booking-coupon-form">

                        <input
                            type="text"
                            class="form-control"
                            placeholder="Nhập mã">

                        <button
                            type="button"
                            class="booking-coupon-btn">

                            Áp dụng

                        </button>

                    </div>

                    <div class="booking-coupon-note">
                        Mã giảm giá hợp lệ. Giảm thêm 1.000.000đ vào tổng giá vé
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

@endsection