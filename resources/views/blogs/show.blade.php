@extends('layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Cẩm nang</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                </ol>
            </nav>

            <h1 class="fw-bold mb-3" style="line-height: 1.3; color: #2c3e50;">{{ $blog->title }}</h1>
            
            <div class="d-flex align-items-center text-muted mb-4 pb-4 border-bottom">
                <span class="me-3"><i class="fas fa-calendar-alt me-1"></i> Ngày đăng: {{ $blog->created_at->format('d/m/Y') }}</span>
                <span><i class="fas fa-user me-1"></i> Bởi: Ban Biên Tập</span>
            </div>

            <img src="{{ asset($blog->image) }}" class="img-fluid rounded-4 mb-5 shadow-sm w-100" style="max-height: 500px; object-fit: cover;">

            <div class="blog-content fs-5" style="line-height: 1.9; color: #444; text-align: justify;">
                {!! nl2br(e($blog->content)) !!}
            </div>

            <div class="mt-5 pt-4 border-top">
                <h5>Chia sẻ bài viết này:</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm"><i class="fab fa-facebook-f me-2"></i>Facebook</button>
                    <button class="btn btn-info btn-sm text-white"><i class="fab fa-twitter me-2"></i>Twitter</button>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mt-5 mt-lg-0">
            <div>
                <h4 class="fw-bold mb-4 border-start border-primary border-4 ps-3">BÀI VIẾT MỚI NHẤT</h4>
                
                @foreach($relatedBlogs as $r)
                <div class="card mb-3 border-0 shadow-sm overflow-hidden">
                    <div class="row g-0">
                        <div class="col-4">
                            <img src="{{ asset($r->image) }}" class="img-fluid h-100" style="object-fit: cover;">
                        </div>
                        <div class="col-8">
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1 fw-bold" style="font-size: 0.9rem;">
                                    <a href="{{ route('blogs.show', $r->id) }}" class="text-dark text-decoration-none">{{ $r->title }}</a>
                                </h6>
                                <small class="text-muted" style="font-size: 0.8rem;">{{ $r->created_at->format('d/m/Y') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="bg-primary rounded-4 p-4 mt-4 text-white">
                    <h5 class="fw-bold">Bạn cần hỗ trợ?</h5>
                    <p class="small mb-3">Liên hệ ngay với chúng tôi để được tư vấn chuyến đi hoàn hảo nhất.</p>
                    <a href="tel:0123456789" class="btn btn-light w-100 fw-bold">Gọi ngay: 0387542417</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .blog-content p { margin-bottom: 25px; }
    .blog-content img { max-width: 100%; border-radius: 10px; margin: 20px 0; }
</style>
@endsection