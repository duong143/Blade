@extends('admin.layout')

@section('title', 'Chỉnh sửa bài viết')

@section('content')
<div class="container-fluid">
    <div class="card card-warning shadow-sm">
        <div class="card-header">
            <h3 class="card-title text-dark font-weight-bold">Cập nhật bài viết: {{ $blog->title }}</h3>
        </div>
        <form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label>Tiêu đề bài viết <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $blog->title) }}" required>
                    @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Hình ảnh hiện tại</label><br>
                    <img src="{{ asset($blog->image) }}" class="img-thumbnail mb-2" width="250" style="max-height: 150px; object-fit: cover;">
                    <input type="file" name="image" class="form-control-file @error('image') is-invalid @enderror">
                    <small class="text-muted">Bỏ trống nếu không muốn thay đổi ảnh.</small>
                    @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Tóm tắt ngắn <span class="text-danger">*</span></label>
                    <textarea name="summary" class="form-control @error('summary') is-invalid @enderror" rows="3" required>{{ old('summary', $blog->summary) }}</textarea>
                    @error('summary') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Nội dung bài viết <span class="text-danger">*</span></label>
                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="15" required>{{ old('content', $blog->content) }}</textarea>
                    @error('content') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="card-footer bg-white border-top">
                <button type="submit" class="btn btn-warning font-weight-bold text-dark px-4"><i class="fas fa-save mr-1"></i> Cập nhật</button>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-default">Hủy bỏ</a>
            </div>
        </form>
    </div>
</div>
@endsection