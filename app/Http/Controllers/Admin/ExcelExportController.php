<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComboBooking;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelExportController extends Controller
{

    public function exportComboBookings()
    {
        $response = new StreamedResponse(function () {

            $handle = fopen('php://output', 'w');


            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));


            fputcsv($handle, [
                'Mã Đơn Hàng',
                'Tên Khách Hàng',
                'Số Điện Thoại',
                'Email',
                'Tên Combo',
                'Số Hành Khách',
                'Tổng Tiền (VND)',
                'Ngày Đi',
                'Trạng Thái Thanh Toán',
                'Ngày Đặt'
            ]);


            $bookings = ComboBooking::with('combo')->latest()->get();


            foreach ($bookings as $b) {

                $paymentStatus = match ($b->payment_status) {
                    'pending' => 'Chờ thanh toán',
                    'paid'    => 'Đã thanh toán',
                    'expired' => 'Hết hạn',
                    default   => $b->payment_status,
                };

                fputcsv($handle, [
                    $b->booking_code ?? 'CB-' . $b->id,
                    $b->contact_name,
                    $b->contact_phone,
                    $b->contact_email ?? 'Không có',
                    $b->combo->title ?? 'Combo ẩn/xóa',
                    $b->total_passengers . ' người',
                    number_format($b->final_amount, 0, ',', '.'), 
                    $b->travel_start_date ? $b->travel_start_date->format('d/m/Y') : '',
                    $paymentStatus,
                    $b->created_at ? $b->created_at->format('d/m/Y H:i') : ''
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="Danh_Sach_Don_Hang_' . date('d_m_Y') . '.csv"',
        ]);

        return $response;
    }
}
