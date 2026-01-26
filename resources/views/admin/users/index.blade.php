<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý User</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

</head>

<body>
    <div class="admin-container">
        <h2>DANH SÁCH USER</h2>

        <p>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                + Thêm user
            </a>
        </p>

        <!-- thông báo -->
        @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
        @endif

        <table class="admin-table" border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Số điện thoại</th>
                    <th>Tên</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>
                        {{ $user->name ?? '—' }}
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-edit">
                            Sửa
                        </a>
                        <form action="{{ route('admin.users.destroy', $user) }}"
                            method="POST"
                            style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-delete"
                                onclick="return confirm('Xoá user này?')">
                                Xoá
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach

                @if ($users->isEmpty())
                <tr>
                    <td colspan="5" align="center">Chưa có user</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>

</html>