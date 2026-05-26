<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $user = $this->route('user');
        $userId = is_object($user) ? $user->id : $user;

        $rules = [
            
            'phone' => ['required', 'digits:10', 'unique:users,phone,' . $userId],
            'name' => ['nullable', 'string', 'max:255'],

            
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $userId],
            'password' => ['nullable', 'string', 'min:6'],
        ];

        if (\Illuminate\Support\Facades\Gate::allows('roles.edit')) {
            $rules['role'] = ['required', 'string', 'exists:roles,name'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.digits' => 'Số điện thoại phải bao gồm đúng 10 chữ số.',
            'phone.unique' => 'Số điện thoại đã được người khác sử dụng.',

            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Email không đúng định dạng (phải có @ và tên miền).',
            'email.unique' => 'Email này đã được đăng ký.',

            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',

            'role.required' => 'Vui lòng chọn vai trò (role) cho người dùng.',
            'role.exists' => 'Role không hợp lệ hoặc không tồn tại.',
        ];
    }
}
