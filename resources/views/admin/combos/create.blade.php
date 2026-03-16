@extends('admin.layout')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Thêm combo</h3>
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

    <form method="POST" action="{{ route('admin.combos.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Mã combo</label>
          <input name="code" class="form-control" value="{{ old('code') }}" placeholder="VD: CBPHUQUOC01">
          <small class="text-muted">Bỏ trống sẽ tự sinh</small>
        </div>

        <div class="col-md-8 mb-3">
          <label class="form-label">Tên combo</label>
          <input name="title" class="form-control" value="{{ old('title') }}" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Ảnh đại diện combo</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        <small class="text-muted">Ảnh này dùng làm ảnh đại diện ở danh sách combo và làm ảnh dự phòng nếu chưa có ảnh slider</small>
      </div>

      <div class="mb-3">
        <label class="form-label">Ảnh nội dung chi tiết</label>
        <input type="file" name="content_image" class="form-control" accept="image/*">
        <small class="text-muted">Ảnh này hiển thị ở giữa phần nội dung chi tiết combo</small>
      </div>

      <div class="mb-3">
        <label class="form-label">Ảnh slider đầu trang</label>
        <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
        <small class="text-muted">Có thể chọn nhiều ảnh để hiển thị ở banner đầu trang chi tiết combo</small>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Điểm đi</label>
          <input name="from_location" class="form-control" value="{{ old('from_location') }}" placeholder="Hà Nội">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Điểm đến</label>
          <input name="to_location" class="form-control" value="{{ old('to_location') }}" placeholder="Phú Quốc">
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Số ngày</label>
          <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days', 3) }}" min="1">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Số đêm</label>
          <input type="number" name="duration_nights" class="form-control" value="{{ old('duration_nights', 2) }}" min="0">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Đặt trước (ngày)</label>
          <input type="number" name="preorder_days" class="form-control" value="{{ old('preorder_days', 7) }}" min="0">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Mô tả ngắn</label>
        <textarea name="short_desc" class="form-control" rows="2">{{ old('short_desc') }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Tiện nghi khách sạn</label>
        <textarea name="hotel_amenities" class="form-control" rows="6">{{ old('hotel_amenities') }}</textarea>
        <small class="text-muted">Nhập nội dung phần hiển thị dưới tiêu đề "Tiện nghi khách sạn"</small>
      </div>

      <div class="mb-3">
        <label class="form-label">Mô tả chi tiết</label>
        <textarea name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Lịch trình chi tiết</label>
        <textarea name="itinerary_detail" class="form-control" rows="8">{{ old('itinerary_detail') }}</textarea>
        <small class="text-muted">Nội dung phần hiển thị dưới tiêu đề "Lịch trình chi tiết"</small>
      </div>

      <div class="mb-3">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-select">
          <option value="1" selected>Hiển thị</option>
          <option value="0">Ẩn</option>
        </select>
      </div>

      <button class="btn btn-primary">Lưu</button>
      <a href="{{ route('admin.combos.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
  </div>
</div>
@endsection