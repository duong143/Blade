@extends('admin.layout')

@section('title', 'Thêm Attraction')

@section('content')
<div class="container-fluid">
    <div class="card card-primary">
        <div class="card-header font-weight-bold">
            <h3 class="card-title">Thêm mới Điểm check-in / Ăn uống</h3>
        </div>
        <form action="{{ route('admin.attractions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Tên địa điểm <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="VD: Tiệm bánh cối xay gió, Nhà hàng sen..." required>
                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Thuộc vùng địa điểm (Destination) <span class="text-danger">*</span></label>
                    <select name="destination_id" class="form-control @error('destination_id') is-invalid @enderror" required>
                        <option value="">-- Chọn vùng --</option>
                        @foreach($destinations as $d)
                            <option value="{{ $d->id }}" {{ old('destination_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>
                    @error('destination_id') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Hình ảnh đại diện <span class="text-danger">*</span></label>
                    <input type="file" name="image" class="form-control-file @error('image') is-invalid @enderror">
                    @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Mô tả chi tiết</label>
                    <textarea name="description" class="form-control" rows="6" placeholder="Giới thiệu đôi nét về địa điểm này...">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="card-footer bg-white border-top">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Lưu dữ liệu</button>
                <a href="{{ route('admin.attractions.index') }}" class="btn btn-default">Hủy bỏ</a>
            </div>
        </form>
    </div>
</div>
@endsection