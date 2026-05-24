@extends('layout')

@section('title', 'Flight Search')

@section('content')

@php
$displayFrom = $fromAirport
? $fromAirport->city . ' (' . $fromAirport->code . ')'
: ($fromText !== '' ? $fromText : 'Nơi đi');

$displayTo = $toAirport
? $toAirport->city . ' (' . $toAirport->code . ')'
: ($toText !== '' ? $toText : 'Nơi đến');

$displayHeaderDate = $departureDate
    ? \Carbon\Carbon::parse($departureDate)->format('d/m/Y')
    : 'Ngày đi';
$departureRouteText = $displayFrom . ' - ' . $displayTo . ' | ' . ($departureDate ? \Carbon\Carbon::parse($departureDate)->locale('vi')->translatedFormat('d/m/Y') : 'Ngày đi');

$returnFromText = $displayTo;
$returnToText = $displayFrom;
$returnRouteText = $returnFromText . ' - ' . $returnToText . ' | ' . ($returnDate ? \Carbon\Carbon::parse($returnDate)->format('d/m/Y') : 'Ngày về');
@endphp

<section class="banner">
    <div class="container">
        <div class="banner-wrapper">
            <div class="banner-slider">
                <div class="banner-track">
                    <div class="banner-slide">
                        <img src="{{ asset('images/anhTimKiem.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="search-result-header">
    <div class="search-result-bg">
        <div class="result-header-inner">
            <div class="result-header-left">
                <div class="result-route">
                    <strong>Chuyến bay {{ $displayFrom }} tới {{ $displayTo }}</strong>
                </div>
                <div class="result-date">
                    {{ $displayHeaderDate }}
                </div>
            </div>

            <div class="result-header-right">
                <a href="{{ route('home') }}" class="btn-change-search">Đổi tìm kiếm</a>
            </div>
        </div>
    </div>
</section>

<section class="flight-result">
    <div class="container">
        <div class="flight-layout">

            <div class="flight-main">
                <div class="departure-section">
                    <div class="departure-top">
                        <div class="departure-info">
                            <div class="departure-title">
                                <img src="{{ asset('images/icon depart.svg') }}" alt="">
                                Chọn chiều đi
                            </div>
                            <div class="departure-route">
                                {{ $departureRouteText }}
                            </div>
                        </div>
                        <button class="btn-other-day">Vé ngày khác</button>
                    </div>

                    <div class="departure-dates">
                        <button class="nav-arrow" type="button">‹</button>

                        <div class="date-track">
                            @forelse($departureDateOptions as $dateItem)
                            <a href="{{ $dateItem['url'] }}" class="date-item {{ $dateItem['active'] ? 'active' : '' }}">
                                <div class="date">{{ $dateItem['label'] }}</div>

                                @if(!empty($dateItem['price_text']))
                                <div class="price">{{ $dateItem['price_text'] }}</div>
                                @else
                                <div class="price empty-price"></div>
                                @endif
                            </a>
                            @empty
                            <div class="date-item active">
                                <div class="date">Ngày đi</div>
                                <div class="price empty-price"></div>
                            </div>
                            @endforelse
                        </div>

                        <button class="nav-arrow" type="button">›</button>
                    </div>
                </div>

                <div class="flight-toolbar">
                    <div class="flight-sort">
                        <button class="sort-trigger" type="button" data-sort="price" data-order="asc">
                            <span class="sort-label">Giá vé tăng dần</span>
                            <span class="sort-icon">
                                <img src="{{ asset('images/mũi tên đi xuống.png') }}" alt="">
                            </span>
                        </button>
                        <ul class="sort-dropdown" hidden>
                            <li data-sort="price" data-order="asc">Giá vé tăng dần</li>
                            <li data-sort="price" data-order="desc">Giá vé giảm dần</li>
                            <li data-sort="time" data-order="asc">Giờ bay sớm nhất</li>
                            <li data-sort="time" data-order="desc">Giờ bay muộn nhất</li>
                        </ul>
                    </div>

                    <div class="flight-filter">
                        <button class="filter-trigger" type="button" data-action="toggle-filter">
                            <span class="filter-label">Hiển thị bộ lọc</span>
                            <span class="filter-icon">
                                <img src="{{ asset('images/phễu .png') }}" alt="">
                            </span>
                        </button>
                    </div>
                </div>

                <template id="flight-card-template">
                    <div class="flight-card">
                        <div class="flight-card-header">
                            <div class="flight-airline">
                                <img src="" alt="">
                                <span class="flight-code"></span>
                                <span class="flight-class"></span>
                            </div>

                            <div class="flight-time">
                                <div class="time-block">
                                    <strong class="time time-depart"></strong>
                                    <span class="place place-depart"></span>
                                </div>
                                <div class="time-block">
                                    <strong class="time time-arrive"></strong>
                                    <span class="place place-arrive"></span>
                                </div>
                                <div class="time-duration">
                                    <span class="duration"></span>
                                    <span class="direct"></span>
                                </div>
                            </div>

                            <div class="flight-price">
                                <div class="price"></div>
                                <div class="point"></div>
                            </div>

                            <div class="flight-action">
                                <button class="btn-book">Đặt vé</button>
                            </div>
                        </div>

                        <div class="flight-card-tabs">
                            <button class="tab-link active" data-tab="info">Thông tin chuyến bay</button>
                            <button class="tab-link" data-tab="detail">Chi tiết vé</button>
                        </div>

                        <div class="flight-card-body">
                            <div class="tab-content active" data-content="info">
                                <div class="flight-info-row">
                                    <div class="info-left">
                                        <div class="info-time with-icon">
                                            <span class="time-icon">
                                                <img src="{{ asset('images/Frame depart.png') }}" alt="">
                                            </span>
                                            <div class="time-content">
                                                <strong class="info-depart"></strong>
                                                <span class="info-depart-airport"></span>
                                            </div>
                                        </div>

                                        <div class="flight-number vertical">
                                            <span class="badge"></span>
                                            <div class="fly-time">
                                                <img src="{{ asset('images/icon-time.png') }}" alt="">
                                                <span></span>
                                            </div>
                                        </div>

                                        <div class="info-time with-icon">
                                            <span class="time-icon">
                                                <img src="{{ asset('images/Frame depart.png') }}" alt="">
                                            </span>
                                            <div class="time-content">
                                                <strong class="info-arrive"></strong>
                                                <span class="info-arrive-airport"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-right">
                                        <div class="info-item">
                                            <span>Máy bay</span>
                                            <strong class="aircraft"></strong>
                                        </div>
                                        <div class="info-item">
                                            <span>Hạng ghế</span>
                                            <strong class="seat-class"></strong>
                                        </div>
                                        <div class="info-item">
                                            <span>Hành lý xách tay</span>
                                            <strong class="carry-on"></strong>
                                        </div>
                                        <div class="info-item">
                                            <span>Hành lý ký gửi</span>
                                            <strong class="checked-bag"></strong>
                                        </div>
                                        <div class="info-item">
                                            <span>Tiện ích khác</span>
                                            <strong class="convinient"></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-content" data-content="detail">
                                <div class="ticket-detail">
                                    <div class="ticket-price-wrap">
                                        <div class="ticket-section-title">Chi tiết giá</div>
                                        <div class="ticket-price"></div>
                                    </div>

                                    <div class="ticket-condition-wrap">
                                        <div class="ticket-section-title">Điều kiện vé</div>
                                        <div class="ticket-condition"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <div id="flight-cards-container"></div>
            </div>

            <div class="flight-sidebar" @if($tripType !=='roundtrip' ) style="display:none;" @endif>
                <div class="return-section">
                    <div class="return-top">
                        <div class="return-title">
                            <img src="{{ asset('images/icon depart.svg') }}" alt="">
                            Chọn chiều về
                        </div>
                        <div class="return-route">
                            {{ $returnRouteText }}
                        </div>
                    </div>

                    <template id="return-card-template">
                        <div class="return-item-wrapper">
                            <div class="return-item ticket-cut">
                                <div class="return-airline">
                                    <img class="return-logo" src="" alt="">
                                    <span class="return-name"></span>
                                </div>
                                <div class="return-divider"></div>
                                <div class="return-info">
                                    <div class="return-col">
                                        <strong class="return-time"></strong>
                                        <span class="return-place return-from"></span>
                                    </div>
                                    <div class="return-col">
                                        <strong class="return-arrive"></strong>
                                        <span class="return-place return-to"></span>
                                    </div>
                                    <div class="return-col">
                                        <strong class="return-duration"></strong>
                                        <span class="return-direct">Bay thẳng</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div id="return-flight-cards-container"></div>
                </div>
            </div>

        </div>
    </div>
</section>

<link rel="stylesheet" href="{{ asset('css/base.css') }}">
<link rel="stylesheet" href="{{ asset('css/header.css') }}">
<link rel="stylesheet" href="{{ asset('css/StyleFlightSearch.css') }}">
<link rel="stylesheet" href="{{ asset('css/info-strip.css') }}">
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

@php
$jsDepartureFlightCards = $departureFlightCards ?? [];
$jsReturnFlightCards = $returnFlightCards ?? [];
@endphp

<script>
    window.departureFlightCards = {{ Js::from($jsDepartureFlightCards) }};
    window.returnFlightCards = {{ Js::from($jsReturnFlightCards) }};
</script>
<script src="{{ asset('js/FlightSearch.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection