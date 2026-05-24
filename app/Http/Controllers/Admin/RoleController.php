<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Services\Admin\RoleService;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService
    ) {}

    public function index()
    {
        $roles = $this->roleService->getAllRoles();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissionGroups = $this->roleService->getPermissionGroups();

        return view('admin.roles.create', compact('permissionGroups'));
    }

    public function store(StoreRoleRequest $request)
    {
        $this->roleService->createRole($request->validated());

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Tạo role thành công');
    }

    public function edit(Role $role)
    {
        $permissionGroups = $this->roleService->getPermissionGroups();
        $rolePermissionNames = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissionGroups', 'rolePermissionNames'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->roleService->updateRole($role, $request->validated());

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Cập nhật role thành công');
    }

    public function destroy(Role $role)
    {
        $deleted = $this->roleService->deleteRole($role);

        if (!$deleted) {
            return back()->with('error', 'Không được xoá role admin');
        }

        return back()->with('success', 'Đã xoá role');
    }
}
