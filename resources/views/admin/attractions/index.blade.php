@extends('admin.layout')

@section('title', 'Danh sách Attraction')

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="icon fas fa-check"></i> {{ session('success') }}
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <h3 class="card-title font-weight-bold">Quản lý Điểm check-in / Ăn uống</h3>
            <a href="{{ route('admin.attractions.create') }}" class="btn btn-primary btn-sm ml-auto">
                <i class="fas fa-plus"></i> Thêm địa điểm mới
            </a>
        </div>
        <div class="card-body">
            <div class="border-bottom bg-light p-3 mb-3 rounded">
                <form action="{{ route('admin.attractions.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="keyword" class="form-control"
                                placeholder="Nhập tên địa điểm cần tìm..."
                                value="{{ request('keyword') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="destination_id" class="form-control">
                                <option value="">-- Chọn vùng địa điểm --</option>
                                @foreach($destinations as $d)
                                    <option value="{{ $d->id }}" {{ request('destination_id') == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-search"></i> Lọc
                            </button>
                            <a href="{{ route('admin.attractions.index') }}" class="btn btn-default">
                                <i class="fas fa-undo"></i> Làm mới
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="bg-light">
                        <tr class="text-center">
                            <th width="5%">#</th>
                            <th width="15%">Hình ảnh</th>
                            <th class="text-left">Tên địa điểm check-in</th>
                            <th width="20%">Thuộc vùng</th>
                            <th width="12%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attractions as $item)
                        <tr class="text-center">
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">
                                @if($item->image)
                                <img src="{{ asset($item->image) }}" class="img-thumbnail" width="100" style="height: 60px; object-fit: cover;">
                                @else
                                <span class="text-muted small">Chưa có ảnh</span>
                                @endif
                            </td>
                            <td class="align-middle text-left font-weight-bold">{{ $item->name }}</td>
                            <td class="align-middle">
                                <span class="badge badge-info px-3 py-2">
                                    <i class="fas fa-map-marker-alt mr-1"></i> {{ $item->destination->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('admin.attractions.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.attractions.destroy', $item->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa điểm này không?');">
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
                            <td colspan="5" class="text-center py-4">Chưa có dữ liệu. Hãy thêm địa điểm đầu tiên!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                {{ $attractions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection