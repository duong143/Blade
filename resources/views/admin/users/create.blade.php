@extends('admin.layout')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Thêm user mới</h3>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="card-body">
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
                <input type="text" name="phone" class="form-control"
                    value="{{ old('phone') }}" required>
            </div>

            <div class="form-group">
                <label>Tên</label>
                <input type="text" name="name" class="form-control"
                    value="{{ old('name') }}">
            </div>

            <div class="form-group">
                <label>Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email ?? '') }}" required>

                @error('email')
                <span class="text-danger mt-1 d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            @can('roles.edit')
            <div class="form-group">
                <label>Role <span class="text-danger">*</span></label>

                <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                    <option value="">-- Chọn vai trò (Bắt buộc) --</option>
                    @foreach($roles as $role)
                    <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                        {{ $role }}
                    </option>
                    @endforeach
                </select>

                @error('role')
                <span class="text-danger mt-1 d-block"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>
            @endcan

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Tạo user
            </button>

            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                Quay lại
            </a>
        </div>
    </form>
</div>
@endsection