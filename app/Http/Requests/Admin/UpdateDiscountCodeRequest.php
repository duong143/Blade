<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $discountCode = $this->route('discount_code');
        $discountCodeId = is_object($discountCode) ? $discountCode->id : $discountCode;

        return [
            'combo_id' => ['required', 'integer', 'exists:combos,id'],
            'code' => ['required', 'string', 'max:50', 'unique:discount_codes,code,' . $discountCodeId],
            'discount_percent' => ['required', 'integer', 'min:1', 'max:100'],
            'valid_from' => ['nullable', 'date'],
            'valid_to' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'checkin_from' => ['nullable', 'date'],
            'checkin_to' => ['nullable', 'date', 'after_or_equal:checkin_from'],
            'status' => ['required', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'combo_id.required' => 'Vui lòng chọn combo áp dụng.',
            'combo_id.exists' => 'Combo áp dụng không tồn tại.',
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'discount_percent.required' => 'Vui lòng nhập phần trăm giảm.',
            'discount_percent.min' => 'Phần trăm giảm phải từ 1 đến 100.',
            'discount_percent.max' => 'Phần trăm giảm phải từ 1 đến 100.',
            'valid_to.after_or_equal' => 'Ngày hiệu lực kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.',
            'checkin_to.after_or_equal' => 'Ngày check-in kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.',
            'status.required' => 'Vui lòng chọn trạng thái.',
        ];
    }
}
