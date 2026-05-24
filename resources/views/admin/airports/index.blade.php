@extends('admin.layout')

@section('title', 'Danh sách sân bay')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Danh sách sân bay</h3>
        <a href="{{ route('admin.airports.create') }}" class="btn btn-primary btn-sm">+ Thêm sân bay</a>
    </div>

    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Từ khóa</label>
                    <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}"
                        placeholder="Mã, tên, thành phố, quốc gia...">
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="is_active" class="form-control">
                        <option value="">-- Tất cả --</option>
                        <option value="1" @selected(request('is_active')==='1' )>Hiển thị</option>
                        <option value="0" @selected(request('is_active')==='0' )>Ẩn</option>
                    </select>
                </div>
            </div>

            <button class="btn btn-primary btn-sm">Lọc</button>
            <a href="{{ route('admin.airports.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mã sân bay</th>
                        <th>Tên sân bay</th>
                        <th>Thành phố</th>
                        <th>Quốc gia</th>
                        <th>Trạng thái</th>
                        <th width="210">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($airports as $airport)
                    <tr>
                        <td>{{ $airport->id }}</td>
                        <td><strong>{{ $airport->code }}</strong></td>
                        <td>{{ $airport->name }}</td>
                        <td>{{ $airport->city }}</td>
                        <td>{{ $airport->country }}</td>
                        <td>
                            @if($airport->is_active)
                            <span class="badge badge-success">Hiển thị</span>
                            @else
                            <span class="badge badge-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.airports.edit', $airport->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                            <form action="{{ route('admin.airports.toggle-status', $airport->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-info">
                                    {{ $airport->is_active ? 'Ẩn' : 'Hiện' }}
                                </button>
                            </form>

                            <form action="{{ route('admin.airports.destroy', $airport->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Xóa sân bay này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Chưa có sân bay nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $airports->links() }}
    </div>
</div>
@endsection