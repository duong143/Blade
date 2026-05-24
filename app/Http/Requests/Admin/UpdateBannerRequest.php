<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => ['nullable', 'image'],
            'type' => ['required', 'in:main,small'],
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'link' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'type.required' => 'Vui lòng chọn loại banner.',
            'type.in' => 'Loại banner không hợp lệ.',
        ];
    }
}
