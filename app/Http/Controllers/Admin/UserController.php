<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // LIST
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    // FORM CREATE
    public function create()
    {
        return view('admin.users.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|unique:users,phone',
            'password' => 'required|min:6',
        ]);

        User::create([
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'is_admin' => $request->is_admin ?? 0,

            'admin_news' => $request->has('admin_news') ? 1 : 0,
            'admin_banner' => $request->has('admin_banner') ? 1 : 0,
            'admin_footer' => $request->has('admin_footer') ? 1 : 0,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Tạo user thành công');
    }


    // UPDATE
    public function update(Request $request, User $user)
    {
        $request->validate([
            'phone' => 'required|unique:users,phone,' . $user->id,
            'name' => 'required',
            'password' => 'nullable|min:6'
        ]);

        $roles = $request->roles ?? [];

        $data = [
            'phone' => $request->phone,
            'name' => $request->name,
            'is_admin' => $request->is_admin ?? 0,

            // Update đúng cột permission
            'admin_news' => in_array('news_admin', $roles) ? 1 : 0,
            'admin_banner' => in_array('banner_admin', $roles) ? 1 : 0,
            'admin_footer' => in_array('config_admin', $roles) ? 1 : 0,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Cập nhật thành công');
    }
    // FORM EDIT
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }




    // DELETE
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Đã xoá user');
    }
}
