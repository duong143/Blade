<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm User</title>
</head>
<body>

<h2>THÊM USER</h2>

<a href="{{ route('admin.users.index') }}">⬅ Quay lại</a>

@if ($errors->any())
    <ul style="color:red;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf

    <p>
        <label>Số điện thoại</label><br>
        <input type="text" name="phone">
    </p>

    <p>
        <label>Mật khẩu</label><br>
        <input type="password" name="password">
    </p>

    <button>Tạo user</button>
</form>

</body>
</html>
