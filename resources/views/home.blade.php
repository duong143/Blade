@extends('layout')

@section('title', 'Home')

@section('content')

<!-- header -->


<!-- BANNER -->
<section class="banner">
    <div class="container">
        <div class="banner-wrapper">

            <button class="banner-btn prev"><img src="{{ asset('images/mũi tên.png') }}" alt=""></button>

            <div class="banner-slider">
                <div class="banner-track">

                    <div class="banner-slide">
                        <img src="{{ asset('images/anh1.png') }}" alt="">
                    </div>

                    <div class="banner-slide">
                        <img src="{{ asset('images/anh1.png') }}" alt="">
                    </div>

                    <div class="banner-slide">
                        <img src="{{ asset('images/anh1.png') }}" alt="">
                    </div>

                </div>
            </div>

            <button class="banner-btn next"><img src="{{ asset('images/mũi tên1.png') }}" alt=""></button>

        </div>
    </div>
</section>


<!-- tab -->
<section class="search-box">
    <div class="container">
        <div class="search-wrapper">

            <div class="search-tabs">
                <button class="tab active flight"><img src="{{ asset('images/ic_18px_Flight.png') }}" alt=""> Vé máy bay</button>
                <button class="tab hotel">
                    <img src="{{ asset('images/ic_18px_Hotel.png') }}" alt=""> Khách sạn
                </button>
                <button class="tab airport">
                    <img src="{{ asset('images/car.png') }}" alt=""> Xe sân bay
                </button>
            </div>

            <!-- FORM chuyến bay -->
            <div class="search-form-box flight-form active">
                <div class="search-form">

                    <div class="flight-col col-1">
                        <div class="form-group">
                            <label><img src="{{ asset('images/định vị.png') }}" alt=""> Điểm đi</label>
                            <input type="text" id="from" value="Hà Nội (HAN)">
                        </div>

                        <div class="swap-box">
                            <button type="button" id="swapBtn"><img src="{{ asset('images/maybayyyy.png') }}" alt=""></button>
                        </div>

                        <div class="form-group">
                            <label><img src="{{ asset('images/định vị.png') }}" alt=""> Điểm đến</label>
                            <input type="text" id="to" value="Hồ Chí Minh (SGN)">
                        </div>
                    </div>
                    <div class="flight-col col-2">
                        <div class="date-error" id="dateError">
                            Ngày về phải sau ngày đi
                        </div>

                        <div class="date-row">
                            <div class="form-group">
                                <label><img src="{{ asset('images/lịch.png') }}" alt=""> Ngày đi</label>
                                <input type="date" id="departDate">
                            </div>
                            <label class="roundtrip-inline">
                                <input type="checkbox" id="roundTrip" checked>
                                Khứ hồi
                            </label>
                        </div>

                        <div class="form-group return-date">
                            <label><img src="{{ asset('images/lịch.png') }}" alt=""> Ngày về</label>
                            <input type="date" id="returnDate">
                        </div>
                    </div>
                    <div class="flight-col col-3">

                        <div class="form-group">
                            <label><img src="{{ asset('images/hangh ghế.png') }}" alt=""> Hạng ghế</label>
                            <select>
                                <option>Phổ thông</option>
                                <option>Thương gia</option>
                                <option>Hạng nhất</option>
                            </select>
                        </div>

                        <div class="passenger-row">
                            <div class="form-group small">
                                <label><img src="{{ asset('images/nglon.png') }}" alt=""> Người lớn</label>
                                <select>
                                    <option>01</option>
                                    <option>02</option>
                                    <option>03</option>
                                    <option>04</option>
                                </select>
                            </div>

                            <div class="form-group small">
                                <label><img src="{{ asset('images/trẻ em.png') }}" alt=""> Trẻ em</label>
                                <select>
                                    <option>00</option>
                                    <option>01</option>
                                    <option>02</option>
                                </select>
                            </div>

                            <div class="form-group small">
                                <label><img src="{{ asset('images/em bé.png') }}" alt=""> Em bé</label>
                                <select>
                                    <option>00</option>
                                    <option>01</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flight-col col-4">

                        <!-- <div class="form-group">
                                <label>Mã khuyến mãi</label>
                                <input type="text" placeholder="Mã khuyến mãi">
                            </div> -->

                        <button class="btn-search">Tìm chuyến bay</button>
                    </div>

                </div>

            </div>


            <!-- FORM KHÁCH SẠN -->
            <div class="search-form-box hotel-form" style="display:none">
                <div class="search-form">

                    <div class="form-group">
                        <label>Địa điểm / Tên khách sạn</label>
                        <input type="text" placeholder="VD: Đà Nẵng">
                    </div>

                    <div class="form-group">
                        <label>Ngày nhận phòng</label>
                        <input type="date">
                    </div>

                    <div class="form-group">
                        <label>Ngày trả phòng</label>
                        <input type="date">
                    </div>

                    <div class="form-group">
                        <label>Số phòng</label>
                        <select>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Người lớn</label>
                        <select>
                            <option>1</option>
                            <option>2</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Trẻ em</label>
                        <select>
                            <option>0</option>
                            <option>1</option>
                        </select>
                    </div>

                    <div class="form-group star-filter">
                        <span class="star-title">Hạng sao:</span>

                        <div class="star-buttons">
                            <button type="button" class="star-btn">2 ★</button>
                            <button type="button" class="star-btn">3 ★</button>
                            <button type="button" class="star-btn">4 ★</button>
                            <button type="button" class="star-btn">5 ★</button>
                        </div>
                    </div>
                    <button class="btn-search">
                        Tìm khách sạn
                    </button>
                </div>
            </div>
            <!-- FORM XE SÂN BAY -->
            <div class="search-form-box airport-form" style="display:none">
                <div class="search-form airport-search">

                    <div class="form-group">
                        <label><img src="{{ asset('images/định vị.png') }}" alt=""> Điểm đón</label>
                        <input type="text" value="185 Hoàng Đạo Thúy">
                    </div>
                    <div class="swap-box airport-swap">
                        <button type="button" id="swapAirport">
                            <img src="{{ asset('images/Input.png') }}" alt="">
                        </button>
                    </div>
                    <div class="form-group">
                        <label><img src="{{ asset('images/định vị.png') }}" alt=""> Điểm đi</label>
                        <input type="text" value="Sân bay quốc tế Nội Bài">
                    </div>
                    <div class="form-group">
                        <label><img src="{{ asset('images/lịch.png') }}" alt=""> Ngày đón</label>
                        <input type="date" value="2020-10-15">
                    </div>
                    <div class="form-group">
                        <label><img src="{{ asset('images/lịch.png') }}" alt=""> Giờ đón</label>
                        <input type="time" value="08:00">
                    </div>
                    <button class="btn-search btn-airport">
                        Tìm kiếm xe
                    </button>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- ƯU ĐÃI -->
<section class="promo">
    <div class="container">
        <h2>Ưu đãi đặc biệt</h2>
        <p>Chúng tôi luôn cập nhật những ưu đãi mới nhất phù hợp với bạn. Thoả sức vi vu đến các địa điểm nổi tiếng
            khắp nơi trên thế giới.</p>
        <div class="promo-grid">
            <div class="promo-item">
                <img src="{{ asset('images/anh1.jpg') }}">
            </div>
            <div class="promo-item">
                <img src="{{ asset('images/anh1.jpg') }}">
            </div>
            <div class="promo-item">
                <img src="{{ asset('images/anh1.jpg') }}">
            </div>
            <div class="promo-item">
                <img src="{{ asset('images/anh1.jpg') }}">
            </div>
            <div class="promo-item">
                <img src="{{ asset('images/anh1.jpg') }}">
            </div>
            <div class="promo-item">
                <img src="{{ asset('images/anh1.jpg') }}">
            </div>
            <div class="promo-btn-wrap">
                <button class="btn-promo">Xem tất cả</button>
            </div>
        </div>
    </div>
</section>

<!-- info strip -->
<!-- TIN TỨC -->
<section class="news">
    <div class="container">
        <h2>Tin tức du lịch</h2>

        <div class="news-grid">
            <div class="news-item">
                <img src="{{ asset('images/anh1.jpg') }}">
                <p>Khám phá địa điểm du lịch mới</p>
            </div>
            <div class="news-item">
                <img src="{{ asset('images/anh1.jpg') }}">
                <p>Kinh nghiệm săn vé giá rẻ</p>
            </div>
            <div class="news-item">
                <img src="{{ asset('images/anh1.jpg') }}">
                <p>Top resort đẹp nhất 2025</p>
            </div>
            <div class="news-btn-wrap">
                <button class="btn-news">Xem tất cả</button>
            </div>
        </div>
    </div>
</section>
<!-- FLASH SALE BANNER -->
<section class="flash-sale">
    <div class="container">
        <div class="flash-sale-banner">
            <img src="{{ asset('images/anh1.png') }}" alt="Flash Sale">
        </div>
    </div>
</section>



<!-- footer -->
 
<!-- JS RIÊNG CỦA HOME -->
<script src="{{ asset('js/home.js') }}" defer></script>
<script src="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js') }}" defer></script>

@endsection