<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComboBooking; 
use App\Models\Blog;
use App\Models\Destination;
use App\Models\Attraction;
use App\Models\Contact;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // 🔥 Thêm dòng này để chạy các câu lệnh SQL DB::raw an toàn

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Thống kê Bookings sẵn có của bạn (Giữ nguyên gốc)
        $totalBookings = ComboBooking::count();
        $pendingPaymentBookings = ComboBooking::where('payment_status', 'pending')->count();
        $expiredBookings = ComboBooking::where('payment_status', 'expired')->count();
        
        $totalRevenue = ComboBooking::where('payment_status', 'paid')->sum('final_amount');
        
        $bookingsToday = ComboBooking::whereDate('created_at', Carbon::today())->count();

        // 2. Thống kê số lượng danh mục sẵn có của bạn (Giữ nguyên gốc)
        $totalBlogs = Blog::count();
        $totalDestinations = Destination::count();
        $totalAttractions = Attraction::count();
        $totalUsers = User::count();

        // 3. Thống kê Contacts sẵn có của bạn (Giữ nguyên gốc)
        $totalContacts = Contact::count();
        $unreadContacts = Contact::where('is_read', false)->count();
        $contactsToday = Contact::whereDate('created_at', Carbon::today())->count();

        // 4. Lấy dữ liệu đồ thị contacts 7 ngày sẵn có của bạn (Giữ nguyên gốc)
        $contactLabels = [];
        $contactData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $contactLabels[] = $date->format('d/m');
            $contactData[] = Contact::whereDate('created_at', $date)->count();
        }

        // 5. Lấy danh sách mới nhất sẵn có của bạn (Giữ nguyên gốc)
        $recentContacts = Contact::latest()->take(5)->get();
        $recentBlogs = Blog::latest()->take(5)->get();

        /* |--------------------------------------------------------------------------
           | 🔥 6. THÊM MỚI ĐỘC LẬP: TÍNH TOÁN DỮ LIỆU BIỂU ĐỒ DOANH THU & TOP COMBO
           |--------------------------------------------------------------------------
        */
        // Thống kê doanh thu theo 12 tháng của năm hiện tại (Chỉ tính đơn đã thanh toán 'paid')
        $revenueData = ComboBooking::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(final_amount) as total') 
        )
        ->whereYear('created_at', date('Y'))
        ->where('payment_status', 'paid') 
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->pluck('total', 'month')
        ->toArray();

        // Chuẩn hóa mảng đủ dữ liệu tuần tự từ Tháng 1 -> Tháng 12 để vẽ biểu đồ cột
        $months = [];
        $revenues = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[] = "Tháng " . $m;
            $revenues[] = $revenueData[$m] ?? 0;
        }

        // Thống kê Top 5 Combo được đặt mua nhiều nhất để vẽ biểu đồ tròn (Dùng đúng quan hệ 'combo')
        $topCombos = ComboBooking::select('combo_id', DB::raw('count(*) as total'))
            ->with('combo:id,title') // Lấy chính xác trường id và tên title từ bảng combos
            ->whereNotNull('combo_id')
            ->groupBy('combo_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        $comboLabels = [];
        $comboCounts = [];
        foreach ($topCombos as $item) {
            $comboLabels[] = $item->combo->title ?? 'Combo ẩn/Xóa';
            $comboCounts[] = $item->total;
        }

        // Trả dữ liệu về đúng file view gốc 'admin.dashboard' của bạn kèm các biến mới
        return view('admin.dashboard', compact(
            'totalBookings', 'pendingPaymentBookings', 'expiredBookings', 'totalRevenue', 'bookingsToday',
            'totalBlogs', 'totalDestinations', 'totalAttractions', 'totalUsers',
            'unreadContacts', 'contactsToday', 'recentContacts', 'recentBlogs',
            'contactLabels', 'contactData',
            'months', 'revenues', 'comboLabels', 'comboCounts' // 4 biến biểu đồ mới tích hợp an toàn 100%
        ));
    }
}