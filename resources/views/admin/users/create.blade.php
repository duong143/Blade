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
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                    value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            @can('roles.edit')
            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="">-- Không gán role --</option>
                    @foreach($roles as $role)
                    <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                        {{ $role }}
                    </option>
                    @endforeach
                </select>
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