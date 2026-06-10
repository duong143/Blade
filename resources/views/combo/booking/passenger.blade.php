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

$startDateText = $travelStartDate ? $travelStartDate->format('d/m/Y') : '--';
$endDateText = $travelEndDate ? $travelEndDate->format('d/m/Y') : '--';

$saleDiscountAmount =
($adult * max(0, (int) $adultBasePrice - (int) $adultFinalPrice)) +
($child * max(0, (int) $childBasePrice - (int) $childFinalPrice)) +
($infant * max(0, (int) $infantBasePrice - (int) $infantFinalPrice));

$couponDiscountAmount = 0;
$payableAmount = $totalAmount;
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

                <form method="POST" action="{{ route('combo.passenger.store') }}" id="passengerForm">
                    @csrf

                    <input type="hidden" name="combo_id" value="{{ $combo->id }}">
                    <input type="hidden" name="departure_id" value="{{ $departure->id }}">

                    <input type="hidden" name="adult" value="{{ $adult }}">
                    <input type="hidden" name="child" value="{{ $child }}">
                    <input type="hidden" name="infant" value="{{ $infant }}">

                    <input type="hidden" name="travel_start_date" value="{{ $travelStartDate->format('Y-m-d') }}">
                    <input type="hidden" name="travel_end_date" value="{{ $travelEndDate->format('Y-m-d') }}">

                    <input type="hidden" name="coupon_code" id="couponCodeHidden" value="{{ old('coupon_code') }}">
                    <input type="hidden" name="discount_code_id" id="discountCodeIdHidden">
                    <input type="hidden" name="discount_code_amount" id="discountCodeAmountHidden" value="0">

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

                    {{-- PASSENGER NOTE --}}
                    <div class="bg-white rounded-3 shadow-sm p-4 mt-3">

                        <h6 class="fw-semibold mb-3">Thông tin bổ sung</h6>

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label small text-muted">
                                    Giới tính
                                </label>

                                <select name="contact_gender" class="form-control">
                                    <option value="">Chọn giới tính</option>
                                    <option value="male">Nam</option>
                                    <option value="female">Nữ</option>
                                    <option value="other">Khác</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small text-muted">
                                    Ngày sinh
                                </label>

                                <input
                                    type="date"
                                    name="contact_birthdate"
                                    class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small text-muted">
                                    Thời gian liên hệ phù hợp
                                </label>

                                <input
                                    type="text"
                                    name="preferred_contact_time"
                                    class="form-control"
                                    placeholder="VD: 8h - 17h">
                            </div>

                            <div class="col-12">
                                <label class="form-label small text-muted">
                                    Ghi chú yêu cầu
                                </label>

                                <textarea
                                    name="customer_note"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Nhập yêu cầu đặc biệt nếu có, ví dụ: phòng gần nhau, ăn chay, có trẻ nhỏ, người lớn tuổi..."></textarea>
                            </div>

                        </div>
                    </div>

                    


                    <!-- {{-- INVOICE --}}
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

                    </div> -->




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

                        <!-- <div class="booking-summary-row">
                            <span>Giảm sale:</span>
                            <span class="booking-summary-discount" id="saleDiscountText">
                                {{ $saleDiscountAmount > 0 ? '-' . number_format($saleDiscountAmount, 0, ',', '.') . 'đ' : '0đ' }}
                            </span>
                        </div> -->

                        <div class="booking-summary-row">
                            <span>Mã giảm giá:</span>
                            <span class="booking-summary-discount" id="couponDiscountText">0đ</span>
                        </div>

                        <div class="booking-summary-total-row">

                            <span class="booking-summary-total-label">
                                Tổng tiền:
                            </span>

                            <div class="booking-summary-total-right">

                                <div class="booking-summary-total-price" id="payableAmountText">
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
                            placeholder="Nhập mã"
                            id="couponCodeInput"
                            name="coupon_code_visible"
                            value="{{ old('coupon_code') }}"
                            form="passengerForm">

                        <button
                            type="button"
                            class="booking-coupon-btn"
                            id="applyCouponBtn">
                            Áp dụng
                        </button>
                    </div>

                    <div class="booking-coupon-note" id="couponMessageBox">
                        @error('coupon_code')
                        {{ $message }}
                        @else
                        Nhập mã giảm giá để áp dụng ưu đãi.
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const applyBtn = document.getElementById('applyCouponBtn');
        const couponInput = document.getElementById('couponCodeInput');
        const couponCodeHidden = document.getElementById('couponCodeHidden');
        const discountCodeIdHidden = document.getElementById('discountCodeIdHidden');
        const discountCodeAmountHidden = document.getElementById('discountCodeAmountHidden');
        const couponMessageBox = document.getElementById('couponMessageBox');
        const couponDiscountText = document.getElementById('couponDiscountText');
        const payableAmountText = document.getElementById('payableAmountText');

        function formatMoney(value) {
            return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
        }

        if (!applyBtn) return;

        applyBtn.addEventListener('click', function() {
            const couponCode = (couponInput.value || '').trim();

            couponCodeHidden.value = couponCode;
            discountCodeIdHidden.value = '';
            discountCodeAmountHidden.value = '0';

            fetch("{{ route('combo.discount-code.validate') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        combo_id: "{{ $combo->id }}",
                        departure_id: "{{ $departure->id }}",
                        travel_start_date: "{{ $travelStartDate->format('Y-m-d') }}",
                        adult: "{{ $adult }}",
                        child: "{{ $child }}",
                        infant: "{{ $infant }}",
                        coupon_code: couponCode
                    })
                })
                .then(async function(response) {
                    const data = await response.json();

                    if (!response.ok) {
                        throw data;
                    }

                    return data;
                })
                .then(function(data) {
                    couponCodeHidden.value = data.discount_code;
                    discountCodeIdHidden.value = data.discount_code_id;
                    discountCodeAmountHidden.value = data.discount_amount;

                    couponDiscountText.textContent = '-' + formatMoney(data.discount_amount);
                    payableAmountText.textContent = formatMoney(data.payable_amount);
                    couponMessageBox.textContent = data.message;
                    couponMessageBox.classList.remove('text-danger');
                    couponMessageBox.classList.add('text-success');
                })
                .catch(function(error) {
                    couponDiscountText.textContent = '0đ';
                    payableAmountText.textContent = "{{ number_format($payableAmount, 0, ',', '.') }}đ";

                    const message = error?.message || 'Mã đã sai, vui lòng thực hiện lại.';
                    couponMessageBox.textContent = message;
                    couponMessageBox.classList.remove('text-success');
                    couponMessageBox.classList.add('text-danger');
                });
        });
    });
</script>
@endsection