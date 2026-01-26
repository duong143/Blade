<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|unique:users,phone',
            'password' => 'required|min:6'
        ]);

        User::create([
            'phone' => $request->phone,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Tạo user thành công');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'phone' => 'required|unique:users,phone,' . $user->id,
            'name' => 'required',
            'password' => 'nullable|min:6'
        ]);

        $data = ['phone' => $request->phone, 'name' => $request->name];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Cập nhật thành công');
            
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Đã xoá user');
    }
}
