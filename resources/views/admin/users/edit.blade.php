@extends('admin.layout')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Sửa user</h3>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="card-body">
            {{-- Hiển thị lỗi --}}
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
                    value="{{ $user->phone }}"
                    required>
            </div>

            <div class="form-group">
                <label>Tên</label>
                <input type="text"
                    name="name"
                    class="form-control"
                    value="{{ $user->name }}"
                    required>
            </div>

            <div class="form-group">
                <label>Mật khẩu mới</label>
                <input type="password"
                    name="password"
                    class="form-control"
                    placeholder="Bỏ trống nếu không đổi">
            </div>

            <div class="form-group">
                <label>Quyền</label>
                <select name="is_admin" class="form-control">
                    <option value="0" {{ $user->is_admin == 0 ? 'selected' : '' }}>
                        User
                    </option>
                    <option value="1" {{ $user->is_admin == 1 ? 'selected' : '' }}>
                        Admin
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label>Phân quyền admin</label>

                <select name="roles[]" class="form-control" multiple>
                    <option value="news_admin"
                        {{ in_array('news_admin', $user->roles ?? []) ? 'selected' : '' }}>
                        Admin tin tức
                    </option>

                    <option value="banner_admin"
                        {{ in_array('banner_admin', $user->roles ?? []) ? 'selected' : '' }}>
                        Admin banner
                    </option>

                    <option value="config_admin"
                        {{ in_array('config_admin', $user->roles ?? []) ? 'selected' : '' }}>
                        Admin cấu hình footer
                    </option>
                </select>

                <small class="text-muted">
                    Giữ Ctrl (Windows) hoặc Cmd (Mac) để chọn nhiều quyền
                </small>
            </div>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.users.index') }}"
                class="btn btn-secondary">
                Quay lại
            </a>
        </div>
    </form>
</div>
@endsection