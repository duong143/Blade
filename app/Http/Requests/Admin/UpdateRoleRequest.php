<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $role = $this->route('role');
        $roleId = is_object($role) ? $role->id : $role;

        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $roleId],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên role.',
            'name.unique' => 'Tên role đã tồn tại.',
            'permissions.array' => 'Danh sách quyền không hợp lệ.',
            'permissions.*.exists' => 'Có quyền không tồn tại trong hệ thống.',
        ];
    }
}