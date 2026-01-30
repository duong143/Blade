<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/adminlte.min.css') }}">
</head>

<body class="hold-transition login-page">
    <div class="login-box">

        <div class="login-logo">
            <b>TravelLink</b> Admin
        </div>

        <div class="card">
            <div class="card-body login-card-body">

                <p class="login-box-msg">Đăng nhập quản trị</p>

                {{-- Hiển thị lỗi --}}
                @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
                @endif

                <form action="/admin/login" method="POST">
                    @csrf

                    {{-- Tài khoản --}}
                    <div class="input-group mb-3">
                        <input type="text"
                            name="login"
                            class="form-control"
                            placeholder="Email hoặc số điện thoại"
                            value="{{ old('login') }}"
                            required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Mật khẩu --}}
                    <div class="input-group mb-3">
                        <input type="password"
                            name="password"
                            class="form-control"
                            placeholder="Mật khẩu"
                            required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit"
                                class="btn btn-primary btn-block">
                                Đăng nhập
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script src="{{ asset('admin-assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/adminlte.min.js') }}"></script>

</body>

</html>