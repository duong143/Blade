<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttractionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'destination_id' => 'required|exists:destinations,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Bắt buộc khi tạo mới
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên địa điểm.',
            'destination_id.required' => 'Vui lòng chọn vùng/địa điểm thuộc về.',
            'image.required' => 'Vui lòng tải lên hình ảnh của địa điểm.',
        ];
    }
}