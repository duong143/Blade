<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComboBookingStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_status' => ['required', 'in:pending,paid,expired'],
            'booking_status' => ['required', 'in:draft,pending_payment,confirmed,cancelled,expired'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_status.required' => 'Vui lòng chọn trạng thái thanh toán.',
            'payment_status.in' => 'Trạng thái thanh toán không hợp lệ.',
            'booking_status.required' => 'Vui lòng chọn trạng thái đơn hàng.',
            'booking_status.in' => 'Trạng thái đơn hàng không hợp lệ.',
        ];
    }
}
