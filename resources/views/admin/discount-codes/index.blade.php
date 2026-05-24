@extends('admin.layout')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Danh sách mã giảm giá</h3>
        <a href="{{ route('admin.discount-codes.create') }}" class="btn btn-primary btn-sm">+ Thêm mã giảm giá</a>
    </div>

    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('admin.discount-codes.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm mã hoặc combo..." value="{{ request('keyword') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <select name="status" class="form-control">
                        <option value="">-- Trạng thái --</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>
            </div>

            <div class="mt-2">
                <button type="submit" class="btn btn-primary btn-sm">Lọc</button>
                <a href="{{ route('admin.discount-codes.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            </div>
        </form>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Combo</th>
                    <th>Mã</th>
                    <th>% giảm</th>
                    <th>Hiệu lực</th>
                    <th>Check-in</th>
                    <th>Trạng thái</th>
                    <th style="width:160px">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($discountCodes as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->combo?->title }}</td>
                    <td><strong>{{ $item->code }}</strong></td>
                    <td>{{ $item->discount_percent }}%</td>
                    <td>
                        {{ $item->valid_from ? $item->valid_from->format('d/m/Y') : '--' }}
                        -
                        {{ $item->valid_to ? $item->valid_to->format('d/m/Y') : '--' }}
                    </td>
                    <td>
                        {{ $item->checkin_from ? $item->checkin_from->format('d/m/Y') : '--' }}
                        -
                        {{ $item->checkin_to ? $item->checkin_to->format('d/m/Y') : '--' }}
                    </td>
                    <td>
                        @if(!$item->status)
                        <span class="badge bg-secondary">Ẩn</span>
                        @elseif($item->isExpired())
                        <span class="badge bg-danger">Đã hết hiệu lực</span>
                        @else
                        <span class="badge bg-success">Đang hiệu lực</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.discount-codes.edit', $item->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                        <form action="{{ route('admin.discount-codes.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa mã này?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Chưa có mã giảm giá</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $discountCodes->links() }}
    </div>
</div>
@endsection