@extends('admin.layout')

@section('title', 'Cập nhật Attraction')

@section('content')
<div class="container-fluid">
    <div class="card card-warning shadow-sm">
        <div class="card-header font-weight-bold">
            <h3 class="card-title text-dark">Cập nhật thông tin: {{ $attraction->name }}</h3>
        </div>
        <form action="{{ route('admin.attractions.update', $attraction->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label>Tên địa điểm <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $attraction->name) }}" required>
                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Thuộc vùng địa điểm (Destination) <span class="text-danger">*</span></label>
                    <select name="destination_id" class="form-control @error('destination_id') is-invalid @enderror" required>
                        @foreach($destinations as $d)
                            <option value="{{ $d->id }}" {{ old('destination_id', $attraction->destination_id) == $d->id ? 'selected' : '' }}>
                                {{ $d->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('destination_id') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Hình ảnh hiện tại</label><br>
                    @if($attraction->image)
                    <img src="{{ asset($attraction->image) }}" class="img-thumbnail mb-3 shadow-sm" width="200" alt="Current Image">
                    @endif
                    <input type="file" name="image" class="form-control-file @error('image') is-invalid @enderror">
                    <small class="text-muted d-block mt-1">Bỏ trống nếu không muốn thay đổi hình ảnh.</small>
                    @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Mô tả chi tiết</label>
                    <textarea name="description" class="form-control" rows="6">{{ old('description', $attraction->description) }}</textarea>
                </div>
            </div>
            <div class="card-footer bg-white border-top">
                <button type="submit" class="btn btn-warning font-weight-bold text-dark"><i class="fas fa-save mr-1"></i> Cập nhật ngay</button>
                <a href="{{ route('admin.attractions.index') }}" class="btn btn-default">Hủy bỏ</a>
            </div>
        </form>
    </div>
</div>
@endsection