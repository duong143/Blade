<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFlightBookingStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_status' => ['required', 'in:pending,paid,expired,cancelled'],
            'booking_status' => ['required', 'in:draft,pending_payment,confirmed,expired,cancelled'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'admin_note' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_status.required' => 'Vui lòng chọn trạng thái thanh toán.',
            'payment_status.in' => 'Trạng thái thanh toán không hợp lệ.',
            'booking_status.required' => 'Vui lòng chọn trạng thái đơn.',
            'booking_status.in' => 'Trạng thái đơn không hợp lệ.',
            'payment_method.max' => 'Phương thức thanh toán không được vượt quá 50 ký tự.',
        ];
    }
}
