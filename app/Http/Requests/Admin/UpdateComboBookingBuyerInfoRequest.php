<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComboBookingBuyerInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],

            'invoice_required' => ['nullable', 'in:1'],
            'invoice_tax' => ['nullable', 'string', 'max:255'],
            'invoice_company' => ['nullable', 'string', 'max:255'],
            'invoice_address' => ['nullable', 'string', 'max:255'],
            'invoice_email' => ['nullable', 'email', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'contact_name.required' => 'Vui lòng nhập họ và tên.',
            'contact_phone.required' => 'Vui lòng nhập số điện thoại.',
            'contact_email.email' => 'Email không đúng định dạng.',
            'invoice_email.email' => 'Email hóa đơn không đúng định dạng.',
        ];
    }
}
