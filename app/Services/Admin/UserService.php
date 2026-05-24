<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserService
{
    public function getAllUsers()
    {
        return User::latest()->get();
    }

    public function getRoleNames()
    {
        return Role::orderBy('name')->pluck('name');
    }

    public function createUser(array $data): User
    {
        $user = User::create([
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
        ]);

        if (Gate::allows('roles.edit') && !empty($data['role'])) {
            $user->assignRole($data['role']);
        }

        $this->clearPermissionCache();

        return $user;
    }

    public function updateUser(User $user, array $data): User
    {
        $updateData = [
            'phone' => $data['phone'],
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        if (Gate::allows('roles.edit')) {
            if (!empty($data['role'])) {
                $user->syncRoles([$data['role']]);
            } else {
                $user->syncRoles([]);
            }
        }

        $this->clearPermissionCache();

        return $user;
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    protected function clearPermissionCache(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
