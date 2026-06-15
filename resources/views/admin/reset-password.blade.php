<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu Admin</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/adminlte.min.css') }}">
</head>

<body class="hold-transition login-page">
    <div class="login-box">

        <div class="login-logo">
            <b>TourLink</b> Admin
        </div>

        <div class="card">
            <div class="card-body login-card-body">

                <p class="login-box-msg">Đặt lại mật khẩu</p>

                @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
                @endif

                <form action="{{ route('admin.password.update') }}" method="POST">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="input-group mb-3">
                        <input type="email"
                            class="form-control"
                            value="{{ $email }}"
                            disabled>

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password"
                            name="password"
                            class="form-control"
                            placeholder="Mật khẩu mới"
                            required>

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="Nhập lại mật khẩu mới"
                            required>

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        Cập nhật mật khẩu
                    </button>
                </form>

                <p class="mt-3 mb-0 text-center">
                    <a href="{{ route('admin.login') }}">
                        Quay lại đăng nhập
                    </a>
                </p>

            </div>
        </div>

    </div>
</body>

</html>