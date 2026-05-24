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
            'phone' => ['required', 'string', 'max:50', 'unique:users,phone,' . $userId],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,' . $userId],
            'password' => ['nullable', 'string', 'min:6'],
        ];

        if (Gate::allows('roles.edit')) {
            $rules['role'] = ['nullable', 'string', 'exists:roles,name'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'role.exists' => 'Role không tồn tại.',
        ];
    }
}
