@extends('admin.layout')

@section('title', 'Danh sách hãng bay')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Danh sách hãng bay</h3>
        <a href="{{ route('admin.airlines.create') }}" class="btn btn-primary btn-sm">+ Thêm hãng bay</a>
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
                        placeholder="Tên hãng, mã hãng...">
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
            <a href="{{ route('admin.airlines.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Logo</th>
                        <th>Tên hãng</th>
                        <th>Mã hãng</th>
                        <th>Trạng thái</th>
                        <th width="210">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($airlines as $airline)
                    <tr>
                        <td>{{ $airline->id }}</td>
                        <td>
                            @if(!empty($airline->logo))
                            <img src="{{ asset('storage/' . $airline->logo) }}"
                                style="height:36px; width:120px; object-fit:contain;">
                            @else
                            <span class="text-muted">Chưa có</span>
                            @endif
                        </td>
                        <td>{{ $airline->name }}</td>
                        <td>{{ $airline->code }}</td>
                        <td>
                            @if($airline->is_active)
                            <span class="badge badge-success">Hiển thị</span>
                            @else
                            <span class="badge badge-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.airlines.edit', $airline->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                            <form action="{{ route('admin.airlines.toggle-status', $airline->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-info">
                                    {{ $airline->is_active ? 'Ẩn' : 'Hiện' }}
                                </button>
                            </form>

                            <form action="{{ route('admin.airlines.destroy', $airline->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Xóa hãng bay này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Chưa có hãng bay nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $airlines->links() }}
    </div>
</div>
@endsection