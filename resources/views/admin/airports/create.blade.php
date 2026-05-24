@extends('admin.layout')

@section('title', 'Thêm sân bay')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm sân bay</h3>
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

        <form method="POST" action="{{ route('admin.airports.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Mã sân bay</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                </div>

                <div class="col-md-9 mb-3">
                    <label class="form-label">Tên sân bay</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Thành phố</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Quốc gia</label>
                    <input type="text" name="country" class="form-control" value="{{ old('country', 'Việt Nam') }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="is_active" class="form-control">
                    <option value="1" @selected(old('is_active', 1) == 1)>Hiển thị</option>
                    <option value="0" @selected(old('is_active') === '0')>Ẩn</option>
                </select>
            </div>

            <button class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.airports.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection@extends('admin.layout')

@section('title', 'Thêm sân bay')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm sân bay</h3>
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

        <form method="POST" action="{{ route('admin.airports.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Mã sân bay</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                </div>

                <div class="col-md-9 mb-3">
                    <label class="form-label">Tên sân bay</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Thành phố</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Quốc gia</label>
                    <input type="text" name="country" class="form-control" value="{{ old('country', 'Việt Nam') }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="is_active" class="form-control">
                    <option value="1" @selected(old('is_active', 1) == 1)>Hiển thị</option>
                    <option value="0" @selected(old('is_active') === '0')>Ẩn</option>
                </select>
            </div>

            <button class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.airports.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection