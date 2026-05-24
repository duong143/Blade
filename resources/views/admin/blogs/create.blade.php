@extends('admin.layout')

@section('title', 'Viết bài Cẩm nang mới')

@section('content')
<div class="container-fluid">
    <div class="card card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Soạn thảo bài viết mới</h3>
        </div>
        <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Tiêu đề bài viết <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="VD: Top 5 món ăn ngon tại Đà Lạt" required>
                    @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Ảnh đại diện (Thumbnail) <span class="text-danger">*</span></label>
                    <input type="file" name="image" class="form-control-file @error('image') is-invalid @enderror" required>
                    @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Tóm tắt ngắn <span class="text-danger">*</span></label>
                    <textarea name="summary" class="form-control @error('summary') is-invalid @enderror" rows="3" placeholder="Đoạn văn ngắn hiện ở trang danh sách..." required>{{ old('summary') }}</textarea>
                    @error('summary') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Nội dung bài viết <span class="text-danger">*</span></label>
                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="15" placeholder="Viết nội dung bài viết tại đây..." required>{{ old('content') }}</textarea>
                    @error('content') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="card-footer bg-white border-top">
                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save mr-1"></i> Lưu bài viết</button>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-default">Quay lại</a>
            </div>
        </form>
    </div>
</div>
@endsection