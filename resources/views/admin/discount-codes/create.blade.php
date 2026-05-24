@extends('admin.layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm mã giảm giá</h3>
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

        <form method="POST" action="{{ route('admin.discount-codes.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Combo áp dụng</label>
                <select name="combo_id" class="form-control" required>
                    <option value="">-- Chọn combo --</option>
                    @foreach($combos as $combo)
                    <option value="{{ $combo->id }}" {{ old('combo_id') == $combo->id ? 'selected' : '' }}>
                        {{ $combo->title }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mã giảm giá</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="VD: TourLink" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">% giảm</label>
                    <input type="number" name="discount_percent" class="form-control" value="{{ old('discount_percent', 10) }}" min="1" max="100" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Hiệu lực từ ngày</label>
                    <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Hiệu lực đến ngày</label>
                    <input type="date" name="valid_to" class="form-control" value="{{ old('valid_to') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Check-in từ ngày</label>
                    <input type="date" name="checkin_from" class="form-control" value="{{ old('checkin_from') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Check-in đến ngày</label>
                    <input type="date" name="checkin_to" class="form-control" value="{{ old('checkin_to') }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="1" selected>Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>

            <button class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.discount-codes.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection