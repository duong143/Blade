@extends('admin.layout')

@section('title', 'Thêm hãng bay')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm hãng bay</h3>
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

        <form method="POST" action="{{ route('admin.airlines.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tên hãng bay</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mã hãng</label>
                <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="VD: VJ, VN, QH">
            </div>

            <div class="mb-3">
                <label class="form-label">Logo</label>
                <input type="file" name="logo" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="is_active" class="form-control">
                    <option value="1" @selected(old('is_active', 1)==1)>Hiển thị</option>
                    <option value="0" @selected(old('is_active')==='0' )>Ẩn</option>
                </select>
            </div>

            <button class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.airlines.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection