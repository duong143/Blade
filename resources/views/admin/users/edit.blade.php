<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa User</title>
</head>

<body>

    <h2>SỬA USER</h2>

    <a href="{{ route('admin.users.index') }}">⬅ Quay lại</a>

    @if ($errors->any())
    <ul style="color:red;">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <p>
            <label>Số điện thoại</label><br>
            <input type="text" name="phone" value="{{ $user->phone }}">
        </p>
        <p>
            <label>Tên</label><br>
            <input type="text"
                name="name"
                value="{{ $user->name }}">
        </p>

        <p>
            <label>Mật khẩu mới (bỏ trống nếu không đổi)</label><br>
            <input type="password" name="password">
        </p>

        <button>Cập nhật</button>
    </form>

</body>

</html>