<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'   => 'required|string|max:255',
            'image'   => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'summary' => 'required|string',
            'content' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'   => 'Vui lòng nhập tiêu đề bài viết.',
            'image.required'   => 'Vui lòng chọn ảnh đại diện cho bài viết.',
            'image.image'      => 'Định dạng file phải là hình ảnh.',
            'summary.required' => 'Vui lòng nhập đoạn tóm tắt ngắn.',
            'content.required' => 'Nội dung bài viết không được để trống.',
        ];
    }
}