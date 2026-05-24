<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/user', function (Request $request) {
    $page = max(1, (int) $request->query('page', 1));
    $limit = max(1, (int) $request->query('pagenumb', 10));
    $keyword = trim((string) $request->query('keyword', ''));
    $id = trim((string) $request->query('id', ''));
    $status = trim((string) $request->query('status', ''));

    $query = User::query();

    if ($id !== '') {
        $query->where('id', (int) $id);
    }

    if ($keyword !== '') {
        $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%')
                ->orWhere('phone', 'like', '%' . $keyword . '%');
        });
    }

    if ($status !== '') {
        if (in_array($status, ['0', '1'], true)) {
            $query->where('status', (int) $status);
        } elseif (in_array(strtolower($status), ['active', 'inactive'], true)) {
            $query->where('status', strtolower($status) === 'active' ? 1 : 0);
        }
    }

    $paginator = $query
        ->orderByDesc('id')
        ->paginate($limit, ['*'], 'page', $page);

    return response()->json([
        'data' => [
            'items' => $paginator->items(),
            'page' => $paginator->currentPage(),
            'limit' => $paginator->perPage(),
            'totalPage' => $paginator->lastPage(),
            'totalResult' => $paginator->total(),
        ],
        'message' => 'Search successfully',
        'status' => 200,
    ], 200);
});

Route::get('/user/{id}', function ($id) {
    $user = User::query()->find($id);

    if (!$user) {
        return response()->json([
            'data' => null,
            'message' => 'User not found',
            'status' => 404,
        ], 404);
    }

    return response()->json([
        'data' => $user,
        'message' => 'Search successfully',
        'status' => 200,
    ], 200);
});


Route::post('/user', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'phone' => 'nullable|string|max:50',
        'password' => 'required|string|min:6',
        'status' => 'nullable|boolean',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'password' => Hash::make($validated['password']),
        'status' => $validated['status'] ?? 1,
    ]);

    return response()->json([
        'data' => $user,
        'message' => 'User created successfully',
        'status' => 201,
    ], 201);
});

Route::put('/user/{id}', function (Request $request, $id) {
    $user = User::query()->find($id);

    if (!$user) {
        return response()->json([
            'data' => null,
            'message' => 'User not found',
            'status' => 404,
        ], 404);
    }

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $id],
        'phone' => ['nullable', 'string', 'max:50'],
        'password' => ['nullable', 'string', 'min:6'],
    ]);

    $updateData = [
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
    ];

    if (!empty($validated['password'])) {
        $updateData['password'] = Hash::make($validated['password']);
    }

    $user->update($updateData);

    return response()->json([
        'data' => $user->fresh(),
        'message' => 'User updated successfully',
        'status' => 200,
    ], 200);
});


Route::delete('user/{id}', [UserController::class, 'destroy']);