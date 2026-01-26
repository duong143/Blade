@extends('layout')

@section('title', 'Flight Search')

@section('content')

<!-- HEADER -->

<!-- BANNER -->
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
<!-- SEARCH RESULT HEADER -->
<section class="search-result-header">
    <div class="search-result-bg">
        <div class="result-header-inner">

            <div class="result-header-left">
                <div class="result-route">
                    <strong>01. Chuyến bay Hà Nội (HAN) tới Hồ Chí Minh (SGN)</strong>
                </div>
                <div class="result-date">
                    Thứ 5, ngày 15 tháng 10 năm 2020
                </div>
            </div>

            <div class="result-header-right">
                <button class="btn-change-search">Đổi tìm kiếm</button>
            </div>

        </div>
    </div>
</section>

<!-- FLIGHT RESULT LAYOUT -->
<section class="flight-result">
    <div class="container">
        <div class="flight-layout">

            <!-- LEFT: CHIỀU ĐI -->
            <div class="flight-main">

                <!-- CHỌN CHIỀU ĐI -->
                <div class="departure-section">
                    <!-- TOP BLUE BAR -->
                    <div class="departure-top">
                        <div class="departure-info">
                            <div class="departure-title">
                                <img src="{{ asset('images/icon depart.svg') }}" alt="">
                                Chọn chiều đi
                            </div>
                            <div class="departure-route">
                                Hà Nội - Sài Gòn | 15/10/2020
                            </div>
                        </div>
                        <button class="btn-other-day">Vé ngày khác</button>
                    </div>
                    <div class="departure-dates">
                        <button class="nav-arrow">‹</button>

                        <div class="date-item">
                            <div class="date">11 Th 10</div>
                            <div class="price">299.000 VND</div>
                        </div>

                        <div class="date-item active">
                            <div class="date">14 Th 03</div>
                            <div class="price">299.000 VND</div>
                        </div>

                        <div class="date-item">
                            <div class="date">17 Th 03</div>
                            <div class="price">299.000 VND</div>
                        </div>

                        <button class="nav-arrow">›</button>
                    </div>
                </div>
                <!-- SORT & FILTER BAR -->
                <div class="flight-toolbar">

                    <!-- SORT -->
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
                                        <div class="info-item"><span>Máy bay</span><strong
                                                class="aircraft"></strong></div>
                                        <div class="info-item"><span>Hạng ghế</span><strong
                                                class="seat-class"></strong></div>
                                        <div class="info-item"><span>Hành lý xách tay</span><strong
                                                class="carry-on"></strong></div>
                                        <div class="info-item"><span>Hành lý ký gửi</span><strong
                                                class="checked-bag"></strong></div>
                                        <div class="info-item"><span>Tiện ích khác</span><strong
                                                class="convinient"></strong></div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-content" data-content="detail">
                                <div class="ticket-detail">
                                    <div class="ticket-price">
                                        <div class="price-row"><span>Người lớn</span><span
                                                class="adult-price"></span></div>
                                        <div class="price-row"><span>Thuế & phí</span><span
                                                class="tax-price"></span></div>
                                        <div class="price-total"><span>Tổng</span><strong
                                                class="total-price"></strong></div>
                                    </div>
                                    <div class="ticket-condition">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- RIGHT: CHIỀU VỀ -->
            <div class="flight-sidebar">

                <div class="return-section">
                    <div class="return-top">
                        <div class="return-title">
                            <img src="{{ asset('images/icon depart.svg') }}" alt="">
                            Chọn chiều về
                        </div>
                        <div class="return-route">
                            Sài Gòn - Hà Nội | 30/10/2020
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
                </div>
            </div>
        </div>
    </div>
</section>

<!-- info strip -->

<!-- footer -->


<!-- CSS -->
<link rel="stylesheet" href="{{ asset('css/base.css') }}">
<link rel="stylesheet" href="{{ asset('css/header.css') }}">
<link rel="stylesheet" href="{{ asset('css/StyleFlightSearch.css') }}">
<link rel="stylesheet" href="{{ asset('css/info-strip.css') }}">
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">



<!-- JS -->
<script src="{{ asset('js/FlightSearch.js') }}"></script>
<script src="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js') }}"></script>


@endsection