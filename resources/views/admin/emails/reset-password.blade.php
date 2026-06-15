<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu</title>
</head>

<body>
    <h2>Đặt lại mật khẩu quản trị Tour Link</h2>

    <p>Xin chào {{ $user->name ?? 'Quản trị viên' }},</p>

    <p>Bạn vừa yêu cầu đặt lại mật khẩu cho tài khoản quản trị.</p>

    <p>
        <a href="{{ $resetUrl }}"
            style="display:inline-block;padding:10px 16px;background:#007bff;color:#ffffff;text-decoration:none;border-radius:4px;">
            Đặt lại mật khẩu
        </a>
    </p>

    <p>Liên kết này có hiệu lực trong 30 phút.</p>

    <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>

    <p>Trân trọng,<br>Tour Link</p>
</body>

</html>