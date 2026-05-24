<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'images.required' => 'Vui lòng chọn ít nhất 1 ảnh.',
            'images.array' => 'Danh sách ảnh không hợp lệ.',
            'images.min' => 'Vui lòng chọn ít nhất 1 ảnh.',
            'images.*.image' => 'Tệp tải lên phải là hình ảnh.',
            'images.*.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc webp.',
            'images.*.max' => 'Mỗi ảnh không được vượt quá 4MB.',
        ];
    }
}
