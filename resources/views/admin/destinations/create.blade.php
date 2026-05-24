@extends('admin.layout')

@section('title', 'Thêm Điểm đến')

@section('content')
<div class="container-fluid">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Thêm Điểm đến Hot mới</h3>
        </div>
        <form action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Tên địa điểm <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="VD: Đà Lạt, Phú Quốc..." required>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Hình ảnh đại diện</label>
                    <input type="file" name="image" class="form-control-file @error('image') is-invalid @enderror">
                    @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Mô tả ngắn</label>
                    <textarea name="short_description" class="form-control" rows="3" placeholder="Viết một đoạn ngắn tóm tắt sức hấp dẫn của địa điểm này...">{{ old('short_description') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Chọn các tháng lý tưởng nhất để đi du lịch</label>
                    <div class="d-flex flex-wrap p-3 border rounded" style="gap: 20px; background: #f8f9fa;">
                        @for($i = 1; $i <= 12; $i++)
                            <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="recommended_months[]" id="month_{{ $i }}" value="{{ $i }}"
                                {{ is_array(old('recommended_months')) && in_array($i, old('recommended_months')) ? 'checked' : '' }}>
                            <label for="month_{{ $i }}" class="custom-control-label" style="cursor: pointer;">Tháng {{ $i }}</label>
                    </div>
                    @endfor
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="status" name="status" checked>
                    <label class="custom-control-label" for="status" style="cursor: pointer;">Cho phép hiển thị lên web</label>
                </div>
            </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu dữ liệu</button>
        <a href="{{ route('admin.destinations.index') }}" class="btn btn-default">Hủy bỏ</a>
    </div>
    </form>
</div>
</div>
@endsection