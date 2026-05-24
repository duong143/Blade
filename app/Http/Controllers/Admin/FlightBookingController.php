<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateFlightBookingBuyerInfoRequest;
use App\Http\Requests\Admin\UpdateFlightBookingStatusRequest;
use App\Models\FlightBooking;
use App\Services\Admin\FlightBookingService;
use Illuminate\Http\Request;

class FlightBookingController extends Controller
{
    public function __construct(
        protected FlightBookingService $flightBookingService
    ) {}

    public function index(Request $request)
    {
        $bookings = $this->flightBookingService->getPaginatedBookings($request);

        return view('admin.flight-bookings.index', compact('bookings'));
    }

    public function show(FlightBooking $booking)
    {
        $booking = $this->flightBookingService->getBookingForShow($booking);

        return view('admin.flight-bookings.show', compact('booking'));
    }

    public function updateStatus(UpdateFlightBookingStatusRequest $request, FlightBooking $booking)
    {
        $this->flightBookingService->updateStatus($booking, $request->validated());

        return back()->with('success', 'Cập nhật trạng thái đơn vé máy bay thành công.');
    }

    public function updateBuyerInfo(UpdateFlightBookingBuyerInfoRequest $request, FlightBooking $booking)
    {
        $this->flightBookingService->updateBuyerInfo($booking, $request->validated());

        return back()->with('success', 'Cập nhật thông tin người mua thành công.');
    }
}
