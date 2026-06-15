<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h2>Đặt lại mật khẩu Tour Link</h2>

    <p>Xin chào {{ $user->name ?? 'Quý khách' }},</p>

    <p>
        Bạn vừa yêu cầu đặt lại mật khẩu cho tài khoản Tour Link.
        Vui lòng bấm vào nút bên dưới để tạo mật khẩu mới.
    </p>

    <p>
        <a href="{{ $resetUrl }}"
            style="display:inline-block;padding:12px 18px;background:#4094F7;color:#ffffff;text-decoration:none;border-radius:5px;font-weight:bold;">
            Đặt lại mật khẩu
        </a>
    </p>

    <p>
        Liên kết này có hiệu lực trong 30 phút.
    </p>

    <p>
        Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.
    </p>

    <p>
        Trân trọng,<br>
        Tour Link
    </p>
</body>

</html>