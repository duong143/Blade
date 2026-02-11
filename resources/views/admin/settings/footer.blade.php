@extends('admin.layout')

@section('content')
<div class="container">
    <h2>Cấu hình thông tin Footer</h2>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.footer.update') }}">
        @csrf

        <div class="mb-3">
            <label>Email công ty</label>
            <input
                type="email"
                name="company_email"
                class="form-control"
                value="{{ $settings['company_email'] ?? '' }}">
        </div>

        <div class="mb-3">
            <label>Số điện thoại</label>
            <input
                type="text"
                name="company_phone"
                class="form-control"
                value="{{ $settings['company_phone'] ?? '' }}">
        </div>

        <div class="mb-3">
            <label>Địa chỉ</label>
            <textarea
                name="company_address"
                class="form-control"
                rows="3">{{ $settings['company_address'] ?? '' }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Lưu cấu hình
        </button>
    </form>
</div>
@endsection