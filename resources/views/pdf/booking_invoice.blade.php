<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Hóa đơn đặt tour</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #222;
            line-height: 1.5;
        }

        .invoice-wrap {
            width: 100%;
            padding: 10px 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 18px;
            border-bottom: 2px solid #222;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
        }

        .header p {
            margin: 4px 0;
            font-size: 12px;
        }

        .invoice-title {
            text-align: center;
            margin: 18px 0;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .section {
            margin-bottom: 16px;
        }

        .section-title {
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 8px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px 4px;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 170px;
            font-weight: bold;
        }

        .detail-table th,
        .detail-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .detail-table th {
            background: #f2f2f2;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
            font-size: 14px;
        }

        .note {
            margin-top: 18px;
            font-size: 12px;
            color: #555;
        }

        .signature {
            margin-top: 35px;
            width: 100%;
        }

        .signature td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            height: 90px;
        }
    </style>
</head>

<body>
    <div class="invoice-wrap">

        <div class="header">
            <h1>Tour Link</h1>
            <p>Website quản lý và bán tour du lịch trực tuyến</p>
            <p>Email: tour-link@example.com | Hotline: 0387542417</p>
        </div>

        <div class="invoice-title">
            Hóa đơn đặt tour
        </div>

        <div class="section">
            <div class="section-title">Thông tin đơn đặt tour</div>

            <table class="info-table">
                <tr>
                    <td>Mã đơn:</td>
                    <td>{{ $booking->booking_code ?? '---' }}</td>
                </tr>
                <tr>
                    <td>Ngày thanh toán:</td>
                    <td>
                        {{ $booking->paid_at ? $booking->paid_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
                    </td>
                </tr>
                <tr>
                    <td>Trạng thái thanh toán:</td>
                    <td>Đã thanh toán</td>
                </tr>
                <tr>
                    <td>Phương thức thanh toán:</td>
                    <td>{{ $booking->payment_method ?? '---' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Thông tin khách hàng</div>

            <table class="info-table">
                <tr>
                    <td>Họ và tên:</td>
                    <td>{{ $booking->contact_name ?? '---' }}</td>
                </tr>
                <tr>
                    <td>Số điện thoại:</td>
                    <td>{{ $booking->contact_phone ?? '---' }}</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>{{ $booking->contact_email ?? '---' }}</td>
                </tr>
                <tr>
                    <td>Ghi chú:</td>
                    <td>{{ $booking->customer_note ?: 'Không có' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Thông tin tour</div>

            <table class="detail-table">
                <thead>
                    <tr>
                        <th>Nội dung</th>
                        <th>Thông tin</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Tên tour/combo</td>
                        <td>{{ $booking->combo->title ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td>Ngày khởi hành</td>
                        <td>
                            {{ $booking->travel_start_date ? $booking->travel_start_date->format('d/m/Y') : '---' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Ngày kết thúc</td>
                        <td>
                            {{ $booking->travel_end_date ? $booking->travel_end_date->format('d/m/Y') : '---' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Số lượng khách</td>
                        <td>
                            {{ (int) $booking->adult }} người lớn,
                            {{ (int) $booking->child }} trẻ em,
                            {{ (int) $booking->infant }} em bé
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Chi tiết thanh toán</div>

            <table class="detail-table">
                <tr>
                    <td>Tổng tiền ban đầu</td>
                    <td class="text-right">
                        {{ number_format((int) $booking->total_amount, 0, ',', '.') }}đ
                    </td>
                </tr>

                <tr>
                    <td>Giảm giá</td>
                    <td class="text-right">
                        {{ number_format((int) $booking->discount_code_amount, 0, ',', '.') }}đ
                    </td>
                </tr>

                <tr class="total-row">
                    <td>Tổng tiền thanh toán</td>
                    <td class="text-right">
                        {{ number_format((int) $booking->final_amount, 0, ',', '.') }}đ
                    </td>
                </tr>
            </table>
        </div>

        <div class="note">
            Hóa đơn này được hệ thống Tour Link tạo tự động sau khi đơn đặt tour được thanh toán thành công.
        </div>

        <table class="signature">
            <tr>
                <td>
                    Khách hàng<br>
                    <em>(Ký, ghi rõ họ tên)</em>
                </td>
                <td>
                    Đại diện Tour Link<br>
                    <em>(Ký, ghi rõ họ tên)</em>
                </td>
            </tr>
        </table>

    </div>
</body>

</html>