@extends('layout')
@section('info-strip')
{{-- Ẩn info-strip cho trang combo --}}
@endsection
@section('content')
<div class="container py-5">
    <div class="text-center mb-5 mt-4">
        <h1 class="fw-bold text-uppercase">Cẩm Nang Du Lịch</h1>
        <p class="text-muted">Tổng hợp kinh nghiệm, mẹo vặt và những hành trình đầy cảm hứng</p>
        <div style="width: 60px; height: 3px; background: #007bff; margin: 20px auto;"></div>
    </div>

    <div class="row g-4">
        @forelse($blogs as $item)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                    <a href="{{ route('blogs.show', $item->id) }}">
                        <img src="{{ asset($item->image) }}" class="card-img-top" style="height: 230px; object-fit: cover;">
                    </a>
                    <div class="card-body">
                        <small class="text-primary fw-bold text-uppercase">{{ $item->created_at->format('d/m/Y') }}</small>
                        <h5 class="card-title fw-bold mt-2 mb-3">
                            <a href="{{ route('blogs.show', $item->id) }}" class="text-dark text-decoration-none">
                                {{ $item->title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted small" style="line-height: 1.6;">
                            {{ Str::limit($item->summary, 120) }}
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0 pb-4">
                        <a href="{{ route('blogs.show', $item->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">Đọc thêm</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Dữ liệu cẩm nang đang được cập nhật...</p>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $blogs->links() }}
    </div>
</div>
@endsection
