<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">


    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styleHome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/info-strip.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>

    @include('partials.header')

    @yield('content')

    @include('partials.info-strip')

    @include('partials.footer')

    <!-- LOGIN MODAL -->
    <div class="login-overlay" id="loginModal">
        <div class="login-modal">
            <button class="login-close" id="closeLoginModal">×</button>

            <h3 class="login-title">Đăng nhập</h3>
            <p class="login-desc">
                Bạn vui lòng đăng nhập để lưu lại thông tin và nhận các ưu đãi đặc biệt
                từ Travel Link dành cho thành viên.
            </p>

            <div class="phone-input">
                <div class="phone-prefix">
                    <img src="https://flagcdn.com/w20/vn.png" alt="VN">
                    <span>+84</span>
                </div>
                <input type="text" placeholder="Số điện thoại">
            </div>
            <div class="password-input">
                <input type="password" placeholder="Mật khẩu">
            </div>

            <button type="button" class="login-submit">
                ĐĂNG NHẬP
            </button>

            <p class="login-policy">
                Với việc tiếp tục, tôi đồng ý đã đọc và chấp thuận
                <a href="#">Điều khoản & điều kiện</a> và
                <a href="#">Quyền riêng tư</a> của Travel Link
            </p>

            <div class="login-divider">
                <span>Hoặc</span>
            </div>

            <div class="login-footer">
                Bạn chưa có tài khoản?
                <a href="#" id="switchToRegister">Đăng ký</a>

            </div>
        </div>
    </div>
    <!-- REGISTER MODAL -->
    <div class="login-overlay" id="registerModal">
        <div class="login-modal">
            <button class="login-close" id="closeRegisterModal">×</button>

            <h3 class="login-title">Đăng ký</h3>
            <p class="login-desc">
                Đăng ký tài khoản mới nhận ngay ưu đãi 200K khi đặt vé máy bay
                tại Travel Link hoặc giảm giá 20% khi đặt phòng tại Travel Hotel.
            </p>
            <div class="phone-input">
                <div class="phone-prefix">
                    <img src="https://flagcdn.com/w20/vn.png" alt="VN">
                    <span>+84</span>
                </div>
                <input type="text" placeholder="Số điện thoại">
            </div>
            <div class="name-input">
                <input type="text" placeholder="Họ và tên">
            </div>

            <div class="password-input">
                <input type="password" placeholder="Mật khẩu">
            </div>
            <button type="button" class="login-submit">
                ĐĂNG KÝ
            </button>
            <div class="login-divider">
                <span>Hoặc</span>
            </div>
            <div class="login-footer">
                Bạn đã có tài khoản?
                <a href="#" id="switchToLogin">Đăng nhập</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/login-modal.js') }}"></script>
    <script src="{{ asset('js/header.js') }}"></script>


</body>

</html>