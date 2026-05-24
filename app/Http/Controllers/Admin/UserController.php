<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\Admin\UserService;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index()
    {
        $users = $this->userService->getAllUsers();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = $this->userService->getRoleNames();

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $this->userService->createUser($request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Tạo user thành công');
    }

    public function edit(User $user)
    {
        $roles = $this->userService->getRoleNames();
        $currentRole = $user->getRoleNames()->first();

        return view('admin.users.edit', compact('user', 'roles', 'currentRole'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->updateUser($user, $request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);

        if (request()->is('api/*')) {
            return response()->json([
                'data' => null,
                'message' => 'User deleted successfully',
                'status' => 200,
            ], 200);
        }

        return back()->with('success', 'Đã xoá user');
    }
}
