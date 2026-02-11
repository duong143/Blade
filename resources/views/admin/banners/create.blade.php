@extends('admin.layout')

@section('content')
<h2>Thêm Banner</h2>

<form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label>Ảnh banner</label>
        <input type="file" name="image" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Tiêu đề</label>
        <input type="text" name="title" class="form-control">
    </div>

    <div class="mb-3">
        <label>Mô tả</label>
        <input type="text" name="subtitle" class="form-control">
    </div>

    <div class="mb-3">
        <label>Loại banner</label>
        <select name="type" class="form-control" required>
            <option value="main">Banner lớn (slider)</option>
            <option value="small">Banner nhỏ (ưu đãi)</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Thứ tự</label>
        <input type="number" name="position" class="form-control" value="0">
    </div>

    <div class="mb-3">
        <label>
            <input type="checkbox" name="is_active" checked>
            Hiển thị
        </label>
    </div>

    <button class="btn btn-success">Lưu</button>
</form>
@endsection