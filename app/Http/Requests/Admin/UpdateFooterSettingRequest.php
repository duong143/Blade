<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFooterSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_email' => ['nullable', 'email', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:255'],
            'company_address' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_email.email' => 'Email công ty không đúng định dạng.',
            'company_email.max' => 'Email công ty không được vượt quá 255 ký tự.',
            'company_phone.max' => 'Số điện thoại không được vượt quá 255 ký tự.',
        ];
    }
}