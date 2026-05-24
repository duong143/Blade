@extends('layout')

@push('styles')
{{-- Đổi sang dùng push vì layout của bạn dùng @stack --}}
<link rel="stylesheet" href="{{ asset('css/destination.css') }}?v={{ time() }}">
@endpush

@section('info-strip')
{{-- Ẩn info-strip cho trang combo --}}
@endsection

@section('content')
<div class="dest-hero shadow-sm">
    <div class="container text-center text-white">
        <h1 class="display-3 fw-bold">Điểm Đến Hot Theo Tháng</h1>
        <p class="lead opacity-75">Khám phá những vùng đất tuyệt vời nhất cùng Tour Link</p>
    </div>
</div>

<div class="container pb-5">
    <div class="text-center mb-5">
        <div class="month-pill-box border">
            @for($i = 1; $i <= 12; $i++)
                <a href="{{ route('frontend.destinations', ['month' => $i]) }}"
                class="month-link {{ $monthFilter == $i ? 'active' : '' }}">
                Tháng {{ $i }}
                </a>
                @endfor
        </div>
    </div>

    <div class="row g-4"> {{-- Class g-4 của Bootstrap 5 để dãn khoảng cách card --}}
        @forelse($destinations as $dest)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <a href="{{ route('frontend.destinations.show', $dest->slug) }}" class="text-decoration-none">
                <div class="dest-item-card">
                    <div class="dest-img-wrap">
                        <img src="{{ asset($dest->image ?? 'images/default-dest.jpg') }}" alt="{{ $dest->name }}">
                    </div>
                    <div class="dest-info">
                        <h3 class="dest-name-label">{{ $dest->name }}</h3>
                        <p class="dest-short-txt">
                            {{ $dest->short_description ?? 'Mời bạn khám phá vẻ đẹp độc đáo và những trải nghiệm khó quên tại ' . $dest->name }}
                        </p>
                        <hr class="my-3 opacity-25">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="dest-more-btn">Khám phá ngay</span>
                            <i class="fas fa-arrow-right text-primary small"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="py-5 bg-white rounded-4 shadow-sm border border-light">
                <i class="fas fa-map-marked-alt fa-3x text-light mb-3"></i>
                <h4 class="text-muted">Đang cập nhật thêm các địa điểm cho Tháng {{ $monthFilter }}</h4>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection