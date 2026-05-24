<?php

namespace App\Services\Admin;

use App\Models\FlightBooking;
use Illuminate\Http\Request;

class FlightBookingService
{
    public function getPaginatedBookings(Request $request)
    {
        $query = FlightBooking::query()
            ->with(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport']);

        if ($request->filled('keyword')) {
            $keyword = trim((string) $request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->where('booking_code', 'like', '%' . $keyword . '%')
                    ->orWhere('contact_name', 'like', '%' . $keyword . '%')
                    ->orWhere('contact_phone', 'like', '%' . $keyword . '%')
                    ->orWhere('contact_email', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('booking_status')) {
            $query->where('booking_status', $request->booking_status);
        }

        return $query
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function getBookingForShow(FlightBooking $booking): FlightBooking
    {
        $booking->load(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport']);

        return $booking;
    }

    public function updateStatus(FlightBooking $booking, array $validated): FlightBooking
    {
        $oldPaymentStatus = $booking->payment_status;

        $booking->update([
            'payment_status' => $validated['payment_status'],
            'booking_status' => $validated['booking_status'],
            'payment_method' => $validated['payment_method'] ?? $booking->payment_method,
            'admin_note' => $validated['admin_note'] ?? $booking->admin_note,
            'paid_at' => $validated['payment_status'] === 'paid'
                ? ($booking->paid_at ?? now())
                : ($validated['payment_status'] !== $oldPaymentStatus ? null : $booking->paid_at),
        ]);

        if ($oldPaymentStatus !== 'paid' && $validated['payment_status'] === 'paid') {
            $flight = $booking->flight;

            if ($flight) {
                $newAvailableSeats = max(0, (int) $flight->available_seats - (int) $booking->total_passengers);

                $flight->update([
                    'available_seats' => $newAvailableSeats,
                ]);
            }
        }

        return $booking;
    }

    public function updateBuyerInfo(FlightBooking $booking, array $validated): FlightBooking
    {
        $booking->update([
            'contact_name' => $validated['contact_name'],
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'] ?? null,
            'admin_note' => $validated['admin_note'] ?? $booking->admin_note,
        ]);

        return $booking;
    }
}
