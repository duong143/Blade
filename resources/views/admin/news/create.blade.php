@extends('admin.layout')

@section('content')
<h2>Thêm tin tức</h2>

<form method="POST"
    action="{{ route('admin.news.store') }}"
    enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label>Tiêu đề</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Ảnh tin tức</label>
        <input
            id="news-images"
            type="file"
            name="images[]"
            class="file"
            multiple
            data-theme="fas">
    </div>

    <div class="mb-3">
        <label>Mô tả ngắn</label>
        <textarea name="excerpt" class="form-control"></textarea>
    </div>

    <div class="mb-3">
        <label>Nội dung</label>
        <textarea name="content" class="form-control" rows="5"></textarea>
    </div>

    <div class="mb-3">
        <label>
            <input type="checkbox" name="is_active" checked>
            Hiển thị
        </label>
    </div>

    <button class="btn btn-success">Lưu</button>
</form>
<script>
    $(document).ready(function () {
        $('#news-images').fileinput({
            theme: 'fas',
            showUpload: false,
            showRemove: true,
            browseLabel: 'Chọn ảnh',
            removeLabel: 'Xóa',
            allowedFileTypes: ['image'],
            maxFileCount: 10
        });
    });
</script>

@endsection