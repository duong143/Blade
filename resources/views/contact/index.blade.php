@extends('layout')
@section('info-strip')
{{-- Ẩn info-strip cho trang combo --}}
@endsection
@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold text-uppercase">Liên Hệ Với Chúng Tôi</h1>
        <p class="text-muted">Chúng tôi luôn lắng nghe ý kiến của bạn</p>
    </div>

    <div class="row g-5 justify-content-center">
        <div class="col-lg-7">
            @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST" class="bg-white p-4 p-md-5 shadow-sm rounded-4 border">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Họ tên *</label>
                        <input type="text" name="name" class="form-control bg-light border-0 py-3 @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email *</label>
                        <input type="email" name="email" class="form-control bg-light border-0 py-3 @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control bg-light border-0 py-3" value="{{ old('phone') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Chủ đề</label>
                        <input type="text" name="subject" class="form-control bg-light border-0 py-3" value="{{ old('subject') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Lời nhắn *</label>
                        <textarea name="message" class="form-control bg-light border-0 py-3 @error('message') is-invalid @enderror" rows="5" required>{{ old('message') }}</textarea>
                        @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold text-uppercase">Gửi Tin Nhắn Ngay</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection