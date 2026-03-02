<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->pluck('name');
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $rules = [
            'phone' => 'required|unique:users,phone',
            'password' => 'required|min:6',
            'name' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email',
        ];

        // Chỉ admin (hoặc người có quyền roles.edit) mới được gán role
        if (Auth::check() && Gate::allows('roles.edit')) {
            $rules['role'] = 'nullable|string|exists:roles,name';
        }

        $request->validate($rules);

        $user = User::create([
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'name' => $request->name ?? null,
            'email' => $request->email ?? null,
        ]);

        if (Auth::check() && Gate::allows('roles.edit') && $request->filled('role')) {
            $user->assignRole($request->role);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('admin.users.index')
            ->with('success', 'Tạo user thành công');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->pluck('name');
        $currentRole = $user->getRoleNames()->first();
        return view('admin.users.edit', compact('user', 'roles', 'currentRole'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'phone' => 'required|unique:users,phone,' . $user->id,
            'name' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
        ];

        // Chỉ admin (hoặc người có quyền roles.edit) mới được đổi role
        if (Auth::check() && Gate::allows('roles.edit')) {
            $rules['role'] = 'nullable|string|exists:roles,name';
        }

        $request->validate($rules);

        $data = [
            'phone' => $request->phone,
            'name'  => $request->name ?? null,
            'email' => $request->email ?? null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Chỉ người có quyền roles.edit mới được đổi role
        if (Auth::check() && Gate::allows('roles.edit')) {
            // nếu chọn role => syncRoles, nếu bỏ trống => xoá role
            if ($request->filled('role')) {
                $user->syncRoles([$request->role]);
            } else {
                $user->syncRoles([]);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('admin.users.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Đã xoá user');
    }
}
