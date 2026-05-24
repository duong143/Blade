<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAirportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:10', 'unique:airports,code'],
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Vui lòng nhập mã sân bay.',
            'code.unique' => 'Mã sân bay đã tồn tại.',
            'name.required' => 'Vui lòng nhập tên sân bay.',
            'city.required' => 'Vui lòng nhập thành phố.',
            'is_active.required' => 'Vui lòng chọn trạng thái.',
            'is_active.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
