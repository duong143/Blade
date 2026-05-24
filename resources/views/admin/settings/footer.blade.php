@extends('admin.layout')

@section('content')
<div class="container">
    <h2>Cấu hình thông tin Footer</h2>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @cannot('footer.edit')
    <div class="alert alert-warning">
        Bạn không có quyền chỉnh sửa cấu hình này.
    </div>
    @endcannot

    <form method="POST" action="{{ route('admin.settings.footer.update') }}">
        @csrf

        <div class="mb-3">
            <label>Email</label>
            <input
                type="email"
                name="company_email"
                class="form-control"
                value="{{ $settings['company_email'] ?? '' }}"
                @cannot('footer.edit') disabled @endcannot>
        </div>

        <div class="mb-3">
            <label>Số điện thoại</label>
            <input
                type="text"
                name="company_phone"
                class="form-control"
                value="{{ $settings['company_phone'] ?? '' }}"
                @cannot('footer.edit') disabled @endcannot>
        </div>

        <div class="mb-3">
            <label>Địa chỉ</label>
            <textarea
                name="company_address"
                class="form-control"
                rows="3"
                @cannot('footer.edit') disabled @endcannot>{{ $settings['company_address'] ?? '' }}</textarea>
        </div>

        @can('footer.edit')
        <button type="submit" class="btn btn-primary">
            Lưu cấu hình
        </button>
        @endcan

        @cannot('footer.edit')
        <div class="alert alert-warning mt-3 mb-0">
            Bạn chỉ có quyền xem, không có quyền cập nhật footer.
        </div>
        @endcannot
    </form>
</div>
@endsection