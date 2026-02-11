@extends('admin.layout')

@section('content')
<h2>Sửa Banner</h2>

<form method="POST"
    action="{{ route('admin.banners.update', $banner) }}"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Ảnh hiện tại</label><br>
        <img src="{{ asset('storage/'.$banner->image) }}" width="300">
    </div>

    <div class="mb-3">
        <label>Ảnh mới (nếu đổi)</label>
        <input type="file" name="image" class="form-control">
    </div>

    <div class="mb-3">
        <label>Tiêu đề</label>
        <input type="text" name="title" class="form-control" value="{{ $banner->title }}">
    </div>
    <div class="mb-3">
        <label>Loại banner</label>
        <select name="type" class="form-control" required>
            <option value="main" {{ $banner->type === 'main' ? 'selected' : '' }}>
                Banner lớn (slider)
            </option>
            <option value="small" {{ $banner->type === 'small' ? 'selected' : '' }}>
                Banner nhỏ (ưu đãi)
            </option>
        </select>
    </div>
    
    <div class="mb-3">
        <label>Thứ tự</label>
        <input type="number" name="position" class="form-control" value="{{ $banner->position }}">
    </div>

    <div class="mb-3">
        <label>
            <input type="checkbox" name="is_active" {{ $banner->is_active ? 'checked' : '' }}>
            Hiển thị
        </label>
    </div>

    <button class="btn btn-primary">Lưu</button>
</form>
@endsection