<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Không bắt buộc khi sửa
            'summary' => 'required|string',
            'content' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề bài viết.',
            'summary.required' => 'Vui lòng nhập đoạn tóm tắt ngắn.',
            'content.required' => 'Vui lòng nhập nội dung chi tiết bài viết.',
        ];
    }
}