<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn đặt tour Tour Link</title>
</head>
<body>
    <p>Kính gửi Quý khách <strong>{{ $booking->contact_name }}</strong>,</p>

    <p>
        Tour Link xin xác nhận đơn đặt tour của Quý khách đã được thanh toán thành công.
        Hóa đơn đặt tour được đính kèm trong email này dưới dạng file PDF.
    </p>

    <p><strong>Thông tin đơn đặt tour:</strong></p>

    <ul>
        <li>Mã đơn: <strong>{{ $booking->booking_code ?? '---' }}</strong></li>
        <li>Tên tour: <strong>{{ $booking->combo->title ?? '---' }}</strong></li>
        <li>Số điện thoại: {{ $booking->contact_phone ?? '---' }}</li>
        <li>Email: {{ $booking->contact_email ?? '---' }}</li>
        <li>Tổng tiền thanh toán: <strong>{{ number_format((int) $booking->final_amount, 0, ',', '.') }}đ</strong></li>
    </ul>

    <p>
        Cảm ơn Quý khách đã sử dụng dịch vụ của Tour Link.
    </p>

    <p>
        Trân trọng,<br>
        <strong>Tour Link</strong>
    </p>
</body>
</html>