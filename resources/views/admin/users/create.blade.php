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

            {{-- Số điện thoại --}}
            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text"
                    name="phone"
                    class="form-control"
                    value="{{ old('phone') }}"
                    placeholder="Nhập số điện thoại"
                    required>
            </div>

            {{-- Mật khẩu --}}
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password"
                    name="password"
                    class="form-control"
                    placeholder="Nhập mật khẩu"
                    required>
            </div>

            {{-- Quyền admin tổng --}}
            <div class="form-group">
                <label>Quyền hệ thống</label>
                <select name="is_admin" class="form-control">
                    <option value="0" {{ old('is_admin') == '0' ? 'selected' : '' }}>
                        User
                    </option>
                    <option value="1" {{ old('is_admin') == '1' ? 'selected' : '' }}>
                        Admin (toàn quyền)
                    </option>
                </select>
            </div>

            <hr>

            {{-- Phân quyền CMS --}}
            <div class="form-group">
                <label><strong>Phân quyền CMS</strong></label>

                <div class="form-check">
                    <input class="form-check-input"
                        type="checkbox"
                        name="admin_news"
                        value="1"
                        {{ old('admin_news') ? 'checked' : '' }}>
                    <label class="form-check-label">
                        Admin tin tức du lịch
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input"
                        type="checkbox"
                        name="admin_banner"
                        value="1"
                        {{ old('admin_banner') ? 'checked' : '' }}>
                    <label class="form-check-label">
                        Admin ảnh banner & ưu đãi
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input"
                        type="checkbox"
                        name="admin_footer"
                        value="1"
                        {{ old('admin_footer') ? 'checked' : '' }}>
                    <label class="form-check-label">
                        Admin cấu hình footer
                    </label>
                </div>

                <small class="text-muted">
                    Chỉ tick những quyền admin này được phép sử dụng
                </small>
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