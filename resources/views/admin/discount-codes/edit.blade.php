@extends('admin.layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa mã giảm giá #{{ $discountCode->id }}</h3>
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

        <form method="POST" action="{{ route('admin.discount-codes.update', $discountCode->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Combo áp dụng</label>
                <select name="combo_id" class="form-control" required>
                    <option value="">-- Chọn combo --</option>
                    @foreach($combos as $combo)
                    <option value="{{ $combo->id }}" {{ old('combo_id', $discountCode->combo_id) == $combo->id ? 'selected' : '' }}>
                        {{ $combo->title }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mã giảm giá</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $discountCode->code) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">% giảm</label>
                    <input type="number" name="discount_percent" class="form-control" value="{{ old('discount_percent', $discountCode->discount_percent) }}" min="1" max="100" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Hiệu lực từ ngày</label>
                    <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from', optional($discountCode->valid_from)->format('Y-m-d')) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Hiệu lực đến ngày</label>
                    <input type="date" name="valid_to" class="form-control" value="{{ old('valid_to', optional($discountCode->valid_to)->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Check-in từ ngày</label>
                    <input type="date" name="checkin_from" class="form-control" value="{{ old('checkin_from', optional($discountCode->checkin_from)->format('Y-m-d')) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Check-in đến ngày</label>
                    <input type="date" name="checkin_to" class="form-control" value="{{ old('checkin_to', optional($discountCode->checkin_to)->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="1" {{ (string) old('status', $discountCode->status) === '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ (string) old('status', $discountCode->status) === '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

            <button class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.discount-codes.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
@endsection