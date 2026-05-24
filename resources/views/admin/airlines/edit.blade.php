@extends('admin.layout')

@section('title', 'Sửa hãng bay')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa hãng bay #{{ $airline->id }}</h3>
    </div>

    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.airlines.update', $airline->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Tên hãng bay</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $airline->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mã hãng</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $airline->code) }}">
            </div>

            @if(!empty($airline->logo))
            <div class="mb-3">
                <label class="form-label d-block">Logo hiện tại</label>
                <img src="{{ asset('storage/' . $airline->logo) }}"
                    style="height:48px; width:160px; object-fit:contain;">
            </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Đổi logo</label>
                <input type="file" name="logo" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="is_active" class="form-control">
                    <option value="1" @selected(old('is_active', $airline->is_active ? 1 : 0) == 1)>Hiển thị</option>
                    <option value="0" @selected(old('is_active', $airline->is_active ? 1 : 0) == 0)>Ẩn</option>
                </select>
            </div>

            <button class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.airlines.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection