<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateComboBookingBuyerInfoRequest;
use App\Http\Requests\Admin\UpdateComboBookingStatusRequest;
use App\Models\ComboBooking;
use App\Services\Admin\ComboBookingService;
use Illuminate\Http\Request;

class ComboBookingController extends Controller
{
    public function __construct(
        protected ComboBookingService $comboBookingService
    ) {}

    public function index(Request $request)
    {
        $data = $this->comboBookingService->getPaginatedBookings($request);

        return view('admin.combo-bookings.index', $data);
    }

    public function show(ComboBooking $booking)
    {
        $booking = $this->comboBookingService->getBookingForShow($booking);

        return view('admin.combo-bookings.show', compact('booking'));
    }

    public function updateStatus(UpdateComboBookingStatusRequest $request, ComboBooking $booking)
    {
        $this->comboBookingService->updateStatus($booking, $request->validated());

        return redirect()
            ->route('admin.combo-bookings.show', $booking->id)
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }

    public function updateBuyerInfo(UpdateComboBookingBuyerInfoRequest $request, ComboBooking $booking)
    {
        $changed = $this->comboBookingService->updateBuyerInfo($booking, $request->validated(), $request);

        if (!$changed) {
            return redirect()
                ->route('admin.combo-bookings.show', $booking->id)
                ->with('success', 'Không có thay đổi nào ở thông tin người mua.');
        }

        return redirect()
            ->route('admin.combo-bookings.show', $booking->id)
            ->with('success', 'Cập nhật thông tin người mua thành công.');
    }
}