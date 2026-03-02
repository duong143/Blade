<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('id', 'desc')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissionGroups = $this->permissionGroups();
        return view('admin.roles.create', compact('permissionGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Tạo role thành công');
    }

    public function edit(Role $role)
    {
        $permissionGroups = $this->permissionGroups();
        $rolePermissionNames = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissionGroups', 'rolePermissionNames'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        $role->syncPermissions($request->permissions ?? []);

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Cập nhật role thành công');
    }

    public function destroy(Role $role)
    {
        // tránh xóa role admin nếu bạn muốn giữ
        if ($role->name === 'admin') {
            return back()->with('error', 'Không được xoá role admin');
        }

        $role->delete();
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return back()->with('success', 'Đã xoá role');
    }

    private function permissionGroups(): array
    {
        // Lấy permission theo prefix module
        $all = Permission::orderBy('name')->get()->pluck('name')->toArray();

        $modules = ['users', 'news', 'banners', 'roles', 'footer'];

        $groups = [];
        foreach ($modules as $module) {
            $groups[$module] = [
                'view'   => in_array("$module.view", $all) ? "$module.view" : null,
                'create' => in_array("$module.create", $all) ? "$module.create" : null,
                'edit'   => in_array("$module.edit", $all) ? "$module.edit" : null,
                'delete' => in_array("$module.delete", $all) ? "$module.delete" : null,
            ];
        }

        return $groups;
    }
}
