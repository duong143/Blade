<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAirlineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:10'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'is_active' => ['required', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên hãng bay.',
            'logo.image' => 'Logo phải là hình ảnh hợp lệ.',
            'logo.mimes' => 'Logo phải có định dạng jpg, jpeg, png, webp hoặc svg.',
            'logo.max' => 'Logo không được vượt quá 4MB.',
            'is_active.required' => 'Vui lòng chọn trạng thái.',
            'is_active.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}