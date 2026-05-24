<?php

namespace App\Http\Controllers;

use App\Models\ComboBooking;
use App\Models\FlightBooking;

class AdminController extends Controller
{
    public function index()
    {
        $totalBookings = ComboBooking::count();
        $pendingPaymentBookings = ComboBooking::where('payment_status', 'pending')->count();
        $expiredBookings = ComboBooking::where('payment_status', 'expired')->count();
        $paidBookings = ComboBooking::where('payment_status', 'paid')->count();
        $totalRevenue = ComboBooking::where('payment_status', 'paid')->sum('final_amount');

        $totalFlightBookings = FlightBooking::count();
        $pendingFlightBookings = FlightBooking::where('payment_status', 'pending')->count();
        $paidFlightBookings = FlightBooking::where('payment_status', 'paid')->count();
        $flightRevenue = FlightBooking::where('payment_status', 'paid')->sum('final_amount');

        return view('admin.dashboard', compact(
            'totalBookings',
            'pendingPaymentBookings',
            'expiredBookings',
            'paidBookings',
            'totalRevenue',
            'totalFlightBookings',
            'pendingFlightBookings',
            'paidFlightBookings',
            'flightRevenue'
        ));
    }
}
