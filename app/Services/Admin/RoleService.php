<?php

namespace App\Services\Admin;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleService
{
    public function getAllRoles()
    {
        return Role::orderBy('id', 'desc')->get();
    }

    public function getPermissionGroups(): array
    {
        $all = Permission::orderBy('name')->pluck('name')->toArray();

        $modules = ['users', 'news', 'banners', 'roles', 'footer'];

        $groups = [];

        foreach ($modules as $module) {
            $groups[$module] = [
                'view'   => in_array("{$module}.view", $all) ? "{$module}.view" : null,
                'create' => in_array("{$module}.create", $all) ? "{$module}.create" : null,
                'edit'   => in_array("{$module}.edit", $all) ? "{$module}.edit" : null,
                'delete' => in_array("{$module}.delete", $all) ? "{$module}.delete" : null,
            ];
        }

        return $groups;
    }

    public function createRole(array $data): Role
    {
        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        $this->clearPermissionCache();

        return $role;
    }

    public function updateRole(Role $role, array $data): Role
    {
        $role->update([
            'name' => $data['name'],
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        $this->clearPermissionCache();

        return $role;
    }

    public function deleteRole(Role $role): bool
    {
        if ($role->name === 'admin') {
            return false;
        }

        $role->delete();

        $this->clearPermissionCache();

        return true;
    }

    protected function clearPermissionCache(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}