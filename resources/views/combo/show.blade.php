@extends('layout')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/combo.css') }}">
<link rel="stylesheet" href="{{ asset('css/combo-detail.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/combo-detail.js') }}"></script>
@endpush

@section('info-strip')
{{-- Ẩn info-strip cho trang combo --}}
@endsection


@section('content')
@php
$today = now()->toDateString();

$defaultDeparture = $combo->departures->first();

if (!empty($selectedDepartureId)) {
$foundDeparture = $combo->departures->firstWhere('id', $selectedDepartureId);
if ($foundDeparture) {
$defaultDeparture = $foundDeparture;
}
}

$remainingSlots = $defaultDeparture?->slots_remaining;
$sold = $defaultDeparture?->sold;

$adultBasePrice = (int) ($defaultDeparture?->prices?->firstWhere('passenger_type', 'adult')?->base_price ?? 0);
$todaySalePercent = (int) ($defaultDeparture?->getSalePercentForDate($today) ?? 0);
$adultFinalPrice = $adultBasePrice > 0
? (int) round($adultBasePrice * (100 - $todaySalePercent) / 100)
: 0;

$childBasePrice = (int) ($defaultDeparture?->prices?->firstWhere('passenger_type', 'child')?->base_price ?? 0);
$childFinalPrice = $childBasePrice > 0
? (int) round($childBasePrice * (100 - $todaySalePercent) / 100)
: 0;

$infantBasePrice = (int) ($defaultDeparture?->prices?->firstWhere('passenger_type', 'infant')?->base_price ?? 0);
$infantFinalPrice = $infantBasePrice > 0
? (int) round($infantBasePrice * (100 - $todaySalePercent) / 100)
: 0;
@endphp

<div class="container-fluid px-0 mt-1">
    @php
    $galleryImages = [];

    if ($combo->images->isNotEmpty()) {
    $galleryImages = $combo->images
    ->map(fn($img) => asset('storage/' . $img->image_path))
    ->values()
    ->all();
    } elseif (!empty($combo->image)) {
    $galleryImages = [
    asset('storage/' . $combo->image),
    ];
    }
    @endphp

    <div class="combo-detail-gallery shadow-sm">
        <button type="button" class="combo-detail-nav combo-detail-prev" aria-label="Ảnh trước">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div class="combo-detail-viewport">
            <div class="combo-detail-track">
                @if(!empty($galleryImages))
                @foreach($galleryImages as $img)
                <div class="combo-detail-slide">
                    <img src="{{ $img }}" alt="{{ $combo->title }}">
                </div>
                @endforeach
                @else
                <div class="combo-detail-slide combo-detail-slide-empty"></div>
                <div class="combo-detail-slide combo-detail-slide-empty"></div>
                <div class="combo-detail-slide combo-detail-slide-empty"></div>
                @endif
            </div>
        </div>

        <button type="button" class="combo-detail-nav combo-detail-next" aria-label="Ảnh tiếp theo">
            <i class="fa-solid fa-chevron-right"></i>
        </button>

        <button type="button" class="combo-detail-explore-btn">
            <i class="fa-solid fa-grip"></i>
            KHÁM PHÁ CHỖ Ở
        </button>
    </div>

    <div class="combo-detail-content-wrap">
        <div class="row mt-0 combo-detail-main-row">
            {{-- LEFT CONTENT --}}
            <div class="col-lg-8">
                <div class="bg-white rounded-3 shadow-sm px-4 pt-2 pb-4">
                    <div class="combo-detail-heading">
                        <div class="combo-detail-breadcrumb">
                            <a href="{{ url('/') }}">Trang chủ</a>
                            <span>/</span>
                            <a href="/combo">Combo</a>
                            <span>/</span>
                            <a href="#">{{ $combo->to_location ?: 'Điểm đến' }}</a>
                            <span>/</span>
                            <span class="combo-detail-breadcrumb-current">{{ $combo->title }}</span>
                        </div>

                        <div class="combo-detail-title-row">
                            <h1 class="combo-detail-title">{{ $combo->title }}</h1>
                            <div class="combo-detail-stars" aria-label="5 sao">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                        </div>

                        <div class="combo-detail-meta">
                            <div class="combo-detail-meta-item">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ $combo->duration_days }} ngày {{ $combo->duration_nights }} đêm</span>
                            </div>

                            <div class="combo-detail-meta-item">
                                <i class="fa-solid fa-hourglass-half"></i>
                                @if($todaySalePercent > 0)
                                <span id="comboSaleCountdown" data-sale-end="{{ now()->endOfDay()->format('Y-m-d H:i:s') }}">
                                    --
                                </span>
                                @else
                                <span>Hết sale hôm nay</span>
                                @endif
                            </div>

                            <div class="combo-detail-meta-item">
                                <span id="comboRemainingSlots">Số lượng: {{ $remainingSlots ?? '--' }}</span>
                            </div>

                            <div class="combo-detail-meta-item">
                                <span id="comboSoldCount">Đã mua: {{ $sold ?? '--' }}</span>
                            </div>
                        </div>
                    </div>

                    <hr class="combo-detail-divider">

                    <h6 class="fw-bold text-primary">Tiện nghi khách sạn</h6>
                    <div class="small text-muted">
                        @if(!empty($combo->hotel_amenities))
                        {!! nl2br(e($combo->hotel_amenities)) !!}
                        @else
                        <div>
                            Lịch trình: {{ $combo->duration_days }} ngày / {{ $combo->duration_nights }} đêm
                        </div>
                        <div>
                            Đặt trước: {{ (int) ($combo->preorder_days ?? 0) }} ngày
                        </div>
                        <div class="mt-2">
                            {{ $combo->short_desc ?: ($combo->short_description ?: 'Chưa có mô tả ngắn.') }}
                        </div>
                        @endif
                    </div>

                    @if(!empty($combo->content_image))
                    <div class="mt-3">
                        <img
                            src="{{ asset('storage/' . $combo->content_image) }}"
                            alt="{{ $combo->title }}"
                            style="width:100%; height:auto; max-height:520px; object-fit:cover; border-radius:12px; display:block;">
                    </div>
                    @else
                    <div class="mt-3" style="height:340px;background:#ddd;border-radius:12px;"></div>
                    @endif

                    <h6 class="fw-bold text-primary mt-4">Lịch trình chi tiết</h6>
                    <div class="small text-muted">
                        @if(!empty($combo->itinerary_detail))
                        {!! nl2br(e($combo->itinerary_detail)) !!}
                        @elseif(!empty($combo->description))
                        {!! nl2br(e($combo->description)) !!}
                        @else
                        Chưa có mô tả chi tiết.
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDEBAR (price + date + qty + coupon + button) --}}
            <div class="col-lg-4 combo-detail-sidebar-col">
                <div class="bg-white rounded-3 shadow-sm p-4" style="position:sticky;top:16px;">
                    @if($adultBasePrice > 0 && $todaySalePercent > 0)
                    <div class="combo-price-old" id="comboPriceOld">{{ number_format($adultBasePrice, 0, ',', '.') }} VND</div>
                    @else
                    <div class="combo-price-old" id="comboPriceOld">&nbsp;</div>
                    @endif

                    <div class="fs-4 fw-bold text-danger" id="comboMainPrice">
                        @if($adultFinalPrice > 0)
                        {{ number_format($adultFinalPrice, 0, ',', '.') }} VND
                        @else
                        --
                        @endif
                    </div>

                    <div
                        class="combo-detail-sale-badge d-inline-block bg-danger text-white px-3 py-2 rounded small {{ $todaySalePercent > 0 ? '' : 'd-none' }}"
                        id="comboSaleBadge">
                        GIẢM {{ $todaySalePercent }}% HÔM NAY
                    </div>

                    <div class="combo-departure-label small text-muted">
                        Xin chọn ngày khởi hành
                    </div>
                    @php
                    $defaultTriggerDate = $defaultDeparture && $defaultDeparture->start_date
                    ? $defaultDeparture->start_date->format('d/m/Y')
                    : 'Chưa có ngày khởi hành';
                    @endphp

                    <div class="combo-departure-select">
                        <button
                            type="button"
                            class="btn btn-primary text-start combo-departure-trigger"
                            id="comboDepartureTrigger">
                            <img src="{{ asset('images/icon_lich.png') }}" class="combo-calendar-icon">
                            <span id="comboDepartureTriggerText">{{ $defaultTriggerDate }}</span>
                        </button>

                        <div class="combo-departure-menu" id="comboDepartureMenu">
                            <div class="combo-departure-menu-title">Chọn ngày khởi hành</div>

                            @php
                            $displayRangeCount = 6; // muốn hiện nhiều hơn thì tăng số này
                            @endphp

                            @forelse($combo->departures as $dep)
                            @php
                            $depAdultBasePrice = (int) ($dep->prices->firstWhere('passenger_type', 'adult')?->base_price ?? 0);
                            $depSalePercent = (int) ($dep->getSalePercentForDate(optional($dep->start_date)->format('Y-m-d')) ?? 0);
                            $depAdultFinalPrice = $depAdultBasePrice > 0 ? (int) round($depAdultBasePrice * (100 - $depSalePercent) / 100) : 0;

                            $depChildBasePrice = (int) ($dep->prices->firstWhere('passenger_type', 'child')?->base_price ?? 0);
                            $depChildFinalPrice = $depChildBasePrice > 0 ? (int) round($depChildBasePrice * (100 - $depSalePercent) / 100) : 0;

                            $depInfantBasePrice = (int) ($dep->prices->firstWhere('passenger_type', 'infant')?->base_price ?? 0);
                            $depInfantFinalPrice = $depInfantBasePrice > 0 ? (int) round($depInfantBasePrice * (100 - $depSalePercent) / 100) : 0;
                            @endphp

                            @for($i = 0; $i < $displayRangeCount; $i++)
                                @php
                                $rangeStart=$dep->start_date ? $dep->start_date->copy()->addDays($i) : null;
                                $rangeEnd = $rangeStart ? $rangeStart->copy()->addDays((int) ($combo->duration_days ?? 0)) : null;

                                $rangeStartText = $rangeStart ? $rangeStart->format('d/m/Y') : '--';
                                $rangeEndText = $rangeEnd ? $rangeEnd->format('d/m/Y') : '--';
                                $depRangeLabel = $rangeStartText . ' - ' . $rangeEndText;

                                $isDefaultActive = $defaultDeparture && $defaultDeparture->id === $dep->id && $i === 0;
                                @endphp

                                <button
                                    type="button"
                                    class="combo-departure-item {{ $isDefaultActive ? 'active' : '' }}"
                                    data-departure-id="{{ $dep->id }}"
                                    data-date="{{ $rangeStartText }}"
                                    data-range-label="{{ $depRangeLabel }}"
                                    data-remaining-slots="{{ $dep->slots_remaining }}"
                                    data-sold="{{ $dep->sold }}"
                                    data-sale-percent="{{ $depSalePercent }}"
                                    data-adult-base-price="{{ $depAdultBasePrice }}"
                                    data-adult-final-price="{{ $depAdultFinalPrice }}"
                                    data-child-final-price="{{ $depChildFinalPrice }}"
                                    data-infant-final-price="{{ $depInfantFinalPrice }}">
                                    <span class="combo-departure-radio-wrap">
                                        <span class="combo-departure-radio"></span>
                                    </span>
                                    <span class="combo-departure-item-text">{{ $depRangeLabel }}</span>
                                </button>
                                @endfor
                                @empty
                                <div class="text-muted small">Chưa có ngày khởi hành</div>
                                @endforelse
                        </div>
                    </div>



                    <div class="combo-qty-section">
                        <div class="combo-qty-heading">Số lượng</div>

                        <div class="combo-qty-list">
                            {{-- Người lớn --}}
                            <div class="combo-qty-card combo-qty-card-active" data-type="adult" data-price="{{ $adultFinalPrice }}">
                                <div class="combo-qty-info">
                                    <div class="combo-qty-label">Người lớn</div>

                                    <div class="combo-qty-price-pill" id="comboAdultPricePill">
                                        @if($adultFinalPrice > 0)
                                        {{ number_format($adultFinalPrice / 1000, 0, ',', '.') }}K
                                        @else
                                        --
                                        @endif
                                    </div>
                                </div>

                                <div class="combo-qty-stepper">
                                    <button type="button" class="combo-step-btn combo-step-btn-minus">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>

                                    <span class="combo-step-count">01</span>

                                    <button type="button" class="combo-step-btn combo-step-btn-plus">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Trẻ em --}}
                            <div class="combo-qty-card" data-type="child" data-price="{{ $childFinalPrice }}">
                                <div class="combo-qty-info">
                                    <div class="combo-qty-label">Trẻ em <span class="combo-qty-note">(2-11 tuổi)</span></div>

                                    <div class="combo-qty-price-pill" id="comboChildPricePill">
                                        @if($childFinalPrice > 0)
                                        {{ number_format($childFinalPrice / 1000, 0, ',', '.') }}K
                                        @else
                                        --
                                        @endif
                                    </div>
                                </div>

                                <div class="combo-qty-stepper">
                                    <button type="button" class="combo-step-btn combo-step-btn-minus combo-step-btn-disabled">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>

                                    <span class="combo-step-count">00</span>

                                    <button type="button" class="combo-step-btn combo-step-btn-plus">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Em bé --}}
                            <div class="combo-qty-card" data-type="infant" data-price="{{ $infantFinalPrice }}">
                                <div class="combo-qty-info">
                                    <div class="combo-qty-label">Em bé <span class="combo-qty-note">(Dưới 02 tuổi)</span></div>

                                    <div class="combo-qty-price-pill" id="comboInfantPricePill">
                                        @if($infantFinalPrice > 0)
                                        {{ number_format($infantFinalPrice / 1000, 0, ',', '.') }}K
                                        @else
                                        --
                                        @endif
                                    </div>
                                </div>

                                <div class="combo-qty-stepper">
                                    <button type="button" class="combo-step-btn combo-step-btn-minus combo-step-btn-disabled">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>

                                    <span class="combo-step-count">00</span>

                                    <button type="button" class="combo-step-btn combo-step-btn-plus">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="combo-total-row">
                            <div class="combo-total-label">Tổng tiền</div>
                            <div class="combo-total-value" id="comboTotalValue">
                                @if($adultFinalPrice > 0)
                                {{ number_format($adultFinalPrice, 0, ',', '.') }} VND
                                @else
                                --
                                @endif
                            </div>
                        </div>

                        <div class="combo-discount-row">
                            <div class="combo-discount-text">
                                <div>Giảm giá <span class="combo-discount-highlight">30%</span> - Mã: <span class="combo-discount-highlight">TRAVELLINK</span></div>
                                <div>Ngày check-in: <strong>10/10 - 30/10</strong></div>
                            </div>

                            <button type="button" class="combo-use-btn">Sử dụng</button>
                        </div>
                        <div class="combo-book-now-wrap">
                            <a
                                href="{{ route('combo.passenger', [
            'combo_id' => $combo->id,
            'slug' => $combo->slug,
            'departure_id' => $defaultDeparture?->id,
            'adult' => 1,
            'child' => 0,
            'infant' => 0,
        ]) }}"
                                class="btn btn-primary combo-book-now-btn"
                                id="comboBookNowBtn">
                                Đặt ngay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection