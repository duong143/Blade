@extends('admin.layout')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Thêm user mới</h3>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="card-body">
            {{-- Hiển thị lỗi validate --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text"
                       name="phone"
                       class="form-control"
                       value="{{ old('phone') }}"
                       placeholder="Nhập số điện thoại"
                       required>
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password"
                       name="password"
                       class="form-control"
                       placeholder="Nhập mật khẩu"
                       required>
            </div>

            <div class="form-group">
                <label>Quyền</label>
                <select name="is_admin" class="form-control">
                    <option value="0" {{ old('is_admin') == '0' ? 'selected' : '' }}>
                        User
                    </option>
                    <option value="1" {{ old('is_admin') == '1' ? 'selected' : '' }}>
                        Admin
                    </option>
                </select>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Tạo user
            </button>

            <a href="{{ route('admin.users.index') }}"
               class="btn btn-secondary">
                Quay lại
            </a>
        </div>
    </form>
</div>
@endsection
