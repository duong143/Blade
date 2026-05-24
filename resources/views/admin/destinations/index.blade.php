@extends('admin.layout')

@section('title', 'Danh sách Điểm đến')

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Danh sách Điểm đến Hot</h3>
            <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary btn-sm ml-auto">
                <i class="fas fa-plus"></i> Thêm địa điểm mới
            </a>
        </div>
        <div class="card-body">
            <div class="card-body border-bottom bg-light">
                <form action="{{ route('admin.destinations.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="keyword" class="form-control"
                                placeholder="Nhập tên địa điểm cần tìm..."
                                value="{{ request('keyword') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="month" class="form-control">
                                <option value="">-- Chọn tháng lý tưởng --</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                    Tháng {{ $i }}
                                    </option>
                                    @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-search"></i> Lọc
                            </button>
                            <a href="{{ route('admin.destinations.index') }}" class="btn btn-default">
                                <i class="fas fa-undo"></i> Làm mới
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="15%">Hình ảnh</th>
                        <th>Tên địa điểm</th>
                        <th>Các tháng lý tưởng</th>
                        <th>Trạng thái</th>
                        <th width="12%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($destinations as $dest)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($dest->image)
                            <img src="{{ asset($dest->image) }}" class="img-thumbnail" width="100" alt="{{ $dest->name }}">
                            @else
                            <span class="text-muted">Chưa có ảnh</span>
                            @endif
                        </td>
                        <td class="font-weight-bold">{{ $dest->name }}</td>
                        <td>
                            @if(is_array($dest->recommended_months))
                            @foreach($dest->recommended_months as $month)
                            <span class="badge badge-info mb-1">Tháng {{ $month }}</span>
                            @endforeach
                            @endif
                        </td>
                        <td>
                            @if($dest->status)
                            <span class="badge badge-success">Hiển thị</span>
                            @else
                            <span class="badge badge-secondary">Đang ẩn</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.destinations.edit', $dest->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.destinations.destroy', $dest->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa điểm này không?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Chưa có dữ liệu. Hãy thêm địa điểm đầu tiên!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $destinations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection