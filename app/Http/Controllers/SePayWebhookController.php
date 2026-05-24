<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComboBooking;
use Illuminate\Support\Facades\Log;

class SePayWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // 1. Lấy toàn bộ dữ liệu do SePay gửi sang
        $data = $request->all();

        // Log dữ liệu lại để sau này bạn dễ debug trong file storage/logs/laravel.log
        Log::info('SePay Webhook Data Received:', $data);

        // 2. Lấy nội dung chuyển khoản (Transaction Content)
        $transactionContent = $data['code'] ?? $data['content'] ?? '';
        if (empty($transactionContent)) {
            return response()->json(['success' => false, 'message' => 'Nội dung chuyển khoản trống'], 400);
        }

        // 3. Sử dụng Regex để tìm mã đơn hàng (Ví dụ mã đơn của bạn có dạng: CB000005)
        // Thuật toán này quét tìm chữ CB đi kèm với các chữ số liền kề
        if (preg_match('/(CB\d{6})/i', $transactionContent, $matches)) {
            $bookingCode = strtoupper($matches[1]); // Chuyển thành chữ hoa chuẩn: CB000005

            // 4. Tìm đơn đặt tour tương ứng trong Database
            $booking = ComboBooking::where('booking_code', $bookingCode)->first();

            if ($booking) {
                // Nếu đơn hàng đã thanh toán rồi thì bỏ qua
                if ($booking->payment_status === 'paid') {
                    return response()->json(['success' => true, 'message' => 'Đơn hàng này đã được xử lý trước đó']);
                }

                // Lấy số tiền thực tế khách chuyển khoản thành công
                $transferAmount = (int)($data['transferAmount'] ?? $data['amount'] ?? 0);

                // Cập nhật trạng thái đơn hàng thành Đã thanh toán
                $booking->update([
                    'payment_status' => 'paid',
                    'booking_status' => 'confirmed',
                    'payment_method' => 'atm', // Đánh dấu thanh toán ngân hàng chuyển khoản
                    'paid_at' => now(),
                    'note' => ($booking->note ? $booking->note . ' | ' : '') . 'Thanh toán tự động qua SePay. Số tiền nhận: ' . number_format($transferAmount) . 'đ'
                ]);

                // Ghi nhận lịch sử đơn hàng (khớp với cấu trúc Model ComboBookingHistory hiện tại của bạn)
                \App\Models\ComboBookingHistory::create([
                    'combo_booking_id' => $booking->id,
                    'action' => 'paid_via_sepay',
                    'field_name' => 'payment_status',
                    'old_value' => 'pending',
                    'new_value' => 'paid',
                    'changed_by_type' => 'system',
                    'changed_by_name' => 'SePay System',
                    'note' => 'Hệ thống tự động kích hoạt trạng thái đơn hàng thành công qua cổng thanh toán SePay.',
                ]);

                return response()->json(['success' => true, 'message' => 'Xử lý đơn hàng thành công']);
            }

            return response()->json(['success' => false, 'message' => 'Không tìm thấy đơn hàng tương ứng với mã ' . $bookingCode], 442);
        }

        return response()->json(['success' => false, 'message' => 'Nội dung chuyển khoản không chứa mã đơn hàng hợp lệ'], 400);
    }
}