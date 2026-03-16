@extends('layout')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/combo.css') }}">
@endpush

@section('title', 'Kết quả tìm kiếm - Combo du lịch')

@section('info-strip')
{{-- Ẩn info-strip cho trang combo --}}
@endsection

@section('content')
{{-- HERO --}}
<section class="combo-banner">
    <div class="container">
        <div class="banner-wrapper">
            <div class="banner-slider">
                <div class="banner-track">
                    <div class="banner-slide">
                        <img src="{{ asset('images/anhTimKiem.png') }}" alt="Banner Combo">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="combo-page">
    <div class="container">
        <div class="combo-search-box">
            <div class="combo-search">
                <input class="combo-search-input" type="text" placeholder="Nhập thông tin tìm kiếm">
                <button class="combo-search-icon" type="button" aria-label="Search">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 11-14 0 7 7 0 0114 0z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="combo-grid">
    @forelse($combos as $c)
    @php
    $today = now()->toDateString();

    $selectedDeparture = null;
    $selectedBasePrice = null;
    $selectedFinalPrice = null;
    $selectedSalePercent = 0;
    $remainingSlots = null;
    $sold = null;

    foreach ($c->departures as $dep) {
    $basePrice = (int) ($dep->prices->firstWhere('passenger_type', 'adult')?->base_price ?? 0);

    if ($basePrice <= 0) {
        continue;
        }

        $depSalePercent=(int) $dep->getSalePercentForDate($today);
        $finalPrice = (int) round($basePrice * (100 - $depSalePercent) / 100);

        if (is_null($selectedDeparture) || $finalPrice < $selectedFinalPrice) {
            $selectedDeparture=$dep;
            $selectedBasePrice=$basePrice;
            $selectedFinalPrice=$finalPrice;
            $selectedSalePercent=$depSalePercent;
            $remainingSlots=$dep->slots_remaining;
            $sold = (int) ($dep->sold ?? 0);
            }
            }

            $salePercent = $selectedSalePercent;
            $oldPrice = $salePercent > 0 ? $selectedBasePrice : null;
            $displayPrice = $selectedFinalPrice;
            @endphp

            <div class="combo-card">
                <div class="thumb">
                    @if(!empty($salePercent) && $salePercent > 0)
                    <span class="combo-badge-sale">-{{ (int)$salePercent }}%</span>
                    @endif

                    {{-- Ảnh --}}
                    @if(!empty($c->image))
                    <img
                        src="{{ asset('storage/' . $c->image) }}"
                        alt="{{ $c->title }}"
                        style="width:100%;height:100%;object-fit:cover;display:block;">
                    @endif

                    <div class="combo-stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>

                <div class="combo-body">
                    <div class="tourName">{{ $c->title }}</div>

                    <div class="d-flex justify-content-between mt-2 small text-muted">
                        <div>
                            <div class="meta-item">
                                <img class="meta-icon" src="{{ asset('images/icon clock.png') }}" alt="">
                                <span>{{ $c->duration_days }} ngày {{ $c->duration_nights }} đêm</span>
                            </div>

                            <div class="meta-item">
                                <img class="meta-icon" src="{{ asset('images/icon_donghocat.png') }}" alt="">
                                <span>Đặt trước {{ (int)($c->preorder_days ?? 0) }} ngày</span>
                            </div>
                        </div>

                        <div class="text-end">
                            <div>Số lượng: {{ $remainingSlots ?? '--' }}</div>
                            <div>Đã mua: {{ $sold ?? '--' }}</div>
                        </div>
                    </div>

                    <div class="combo-bottom">
                        <div class="combo-price-block">
                            @if(!is_null($oldPrice))
                            <span class="combo-price-old">{{ number_format($oldPrice, 0, ',', '.') }} VND</span>
                            @else
                            <span class="combo-price-old">&nbsp;</span>
                            @endif

                            <div class="combo-price-new">
                                @if(!is_null($displayPrice))
                                Từ {{ number_format($displayPrice, 0, ',', '.') }} VND
                                @else
                                Từ --
                                @endif
                            </div>

                            <div class="combo-price-note">Đã bao gồm thuế & phí</div>
                        </div>

                        <div class="combo-action">
                            <a href="{{ route('combo.show', $c->slug) }}"
                                class="btn combo-btn-primary combo-book-btn">
                                Đặt ngay
                            </a>

                            <div class="combo-preorder">
                                Đặt trước {{ (int)($c->preorder_days ?? 0) }} ngày
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @empty
            <div style="grid-column: 1 / -1; text-align:center;" class="text-muted">
                Chưa có combo.
            </div>
            @endforelse
</div>

<div class="mt-4">
    {{ $combos->links('vendor.pagination.combo') }}
</div>
@endsection