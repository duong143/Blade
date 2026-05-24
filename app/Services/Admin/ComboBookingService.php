<?php

namespace App\Services\Admin;

use App\Models\ComboBooking;
use App\Models\ComboBookingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComboBookingService
{
    public function getPaginatedBookings(Request $request)
    {
        $keyword = trim((string) $request->query('keyword', ''));
        $paymentStatus = trim((string) $request->query('payment_status', ''));
        $bookingStatus = trim((string) $request->query('booking_status', ''));
        $createdFrom = trim((string) $request->query('created_from', ''));
        $createdTo = trim((string) $request->query('created_to', ''));

        $bookings = ComboBooking::query()
            ->with(['combo', 'departure'])
            ->when($keyword !== '', function ($q) use ($keyword) {
                $q->where(function ($sub) use ($keyword) {
                    $sub->where('booking_code', 'like', '%' . $keyword . '%')
                        ->orWhere('contact_name', 'like', '%' . $keyword . '%')
                        ->orWhere('contact_phone', 'like', '%' . $keyword . '%')
                        ->orWhere('contact_email', 'like', '%' . $keyword . '%')
                        ->orWhere('discount_code', 'like', '%' . $keyword . '%');
                });
            })
            ->when($paymentStatus !== '', fn($q) => $q->where('payment_status', $paymentStatus))
            ->when($bookingStatus !== '', fn($q) => $q->where('booking_status', $bookingStatus))
            ->when($createdFrom !== '', fn($q) => $q->whereDate('created_at', '>=', $createdFrom))
            ->when($createdTo !== '', fn($q) => $q->whereDate('created_at', '<=', $createdTo))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return [
            'bookings' => $bookings,
            'keyword' => $keyword,
            'paymentStatus' => $paymentStatus,
            'bookingStatus' => $bookingStatus,
            'createdFrom' => $createdFrom,
            'createdTo' => $createdTo,
        ];
    }

    public function getBookingForShow(ComboBooking $booking): ComboBooking
    {
        $booking->load([
            'combo',
            'departure',
            'discountCodeRelation',
            'histories',
        ]);

        return $booking;
    }

    public function updateStatus(ComboBooking $booking, array $data): void
    {
        $booking->update([
            'payment_status' => $data['payment_status'],
            'booking_status' => $data['booking_status'],
        ]);
    }

    public function updateBuyerInfo(ComboBooking $booking, array $validated, Request $request): bool
    {
        $invoiceRequired = $request->boolean('invoice_required');

        $newData = [
            'contact_name' => $validated['contact_name'],
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'] ?? null,

            'invoice_required' => $invoiceRequired,
            'invoice_tax' => $invoiceRequired ? ($validated['invoice_tax'] ?? null) : null,
            'invoice_company' => $invoiceRequired ? ($validated['invoice_company'] ?? null) : null,
            'invoice_address' => $invoiceRequired ? ($validated['invoice_address'] ?? null) : null,
            'invoice_email' => $invoiceRequired ? ($validated['invoice_email'] ?? null) : null,
        ];

        $fields = [
            'contact_name' => 'Họ và tên',
            'contact_phone' => 'Số điện thoại',
            'contact_email' => 'Email',
            'invoice_required' => 'Yêu cầu xuất hóa đơn',
            'invoice_tax' => 'Mã số thuế',
            'invoice_company' => 'Tên công ty',
            'invoice_address' => 'Địa chỉ công ty',
            'invoice_email' => 'Email hóa đơn',
        ];

        $oldData = $booking->only(array_keys($fields));

        $hasChanges = false;

        foreach (array_keys($fields) as $field) {
            $oldValue = $oldData[$field] ?? null;
            $newValue = $newData[$field] ?? null;

            if ((string) $oldValue !== (string) $newValue) {
                $hasChanges = true;
                break;
            }
        }

        if (!$hasChanges) {
            return false;
        }

        $booking->update($newData);

        $changedById = session('admin_id');
        $user = Auth::user();

        $changedByName = 'Admin';

        if ($user) {
            if (!empty($user->name)) {
                $changedByName = $user->name;
            } elseif (!empty($user->email)) {
                $changedByName = $user->email;
            } elseif (!empty($user->phone)) {
                $changedByName = $user->phone;
            }
        }

        foreach ($fields as $field => $label) {
            $oldValue = $oldData[$field] ?? null;
            $newValue = $newData[$field] ?? null;

            if ((string) $oldValue !== (string) $newValue) {
                ComboBookingHistory::create([
                    'combo_booking_id' => $booking->id,
                    'action' => 'admin_updated_buyer_info',
                    'field_name' => $field,
                    'old_value' => is_bool($oldValue)
                        ? ($oldValue ? '1' : '0')
                        : (is_null($oldValue) ? null : (string) $oldValue),
                    'new_value' => is_bool($newValue)
                        ? ($newValue ? '1' : '0')
                        : (is_null($newValue) ? null : (string) $newValue),
                    'changed_by_type' => 'admin',
                    'changed_by_id' => $changedById,
                    'changed_by_name' => $changedByName,
                    'note' => 'Admin cập nhật thông tin người mua: ' . $label,
                ]);
            }
        }

        return true;
    }
}
