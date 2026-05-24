@extends('admin.layout')

@section('title', 'Cập nhật Điểm đến')

@section('content')
<div class="container-fluid">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">Cập nhật thông tin: {{ $destination->name }}</h3>
        </div>
        <form action="{{ route('admin.destinations.update', $destination->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label>Tên địa điểm <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $destination->name) }}" required>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Hình ảnh đại diện</label><br>
                    @if($destination->image)
                    <img src="{{ asset($destination->image) }}" class="img-thumbnail mb-2" width="200" alt="Current Image">
                    @endif
                    <input type="file" name="image" class="form-control-file mt-2 @error('image') is-invalid @enderror">
                    <small class="text-muted">Bỏ trống nếu bạn không muốn thay đổi hình ảnh hiện tại.</small>
                    @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Mô tả ngắn</label>
                    <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $destination->short_description) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Các tháng lý tưởng nhất để đi du lịch</label>
                    <div class="d-flex flex-wrap p-3 border rounded" style="gap: 20px; background: #f8f9fa;">
                        @php
                        $selectedMonths = old('recommended_months', is_array($destination->recommended_months) ? $destination->recommended_months : []);
                        @endphp
                        @for($i = 1; $i <= 12; $i++)
                            <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="recommended_months[]" id="month_{{ $i }}" value="{{ $i }}"
                                {{ in_array($i, $selectedMonths) ? 'checked' : '' }}>
                            <label for="month_{{ $i }}" class="custom-control-label" style="cursor: pointer;">Tháng {{ $i }}</label>
                    </div>
                    @endfor
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="status" name="status" {{ $destination->status ? 'checked' : '' }}>
                    <label class="custom-control-label" for="status" style="cursor: pointer;">Cho phép hiển thị lên web</label>
                </div>
            </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Cập nhật</button>
        <a href="{{ route('admin.destinations.index') }}" class="btn btn-default">Hủy bỏ</a>
    </div>
    </form>
</div>
</div>
@endsection