@extends('admin.layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách user</h3>

        <div class="card-tools">
            @can('users.create')
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm user
            </a>
            @endcan
        </div>
    </div>

    <div class="card-body">
        {{-- Thông báo --}}
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('admin.users.index') }}" method="GET" class="mb-4 bg-light p-3 rounded border">
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label font-weight-bold">ID</label>
                    <input type="text" name="id" class="form-control" placeholder="Nhập ID chính xác..." value="{{ $filters['id'] ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label font-weight-bold">Từ khóa tìm kiếm</label>
                    <input type="text" name="keyword" class="form-control" placeholder="Nhập tên, email hoặc số điện thoại..." value="{{ $filters['keyword'] ?? '' }}">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Lọc dữ liệu
                    </button>
                </div>
            </div>

            @if(!empty(array_filter($filters ?? [])))
            <div class="row mt-2">
                <div class="col-12 text-right">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-times"></i> Xóa bộ lọc
                    </a>
                </div>
            </div>
            @endif
        </form>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width:60px">#</th>
                    <th>ID</th>
                    <th>Số điện thoại</th>
                    <th>Tên</th>
                    <th>Ngày tạo</th>
                    <th style="width:100px">Quyền</th>
                    <th style="width:120px">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->name ?? '—' }}</td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>

                    {{-- Quyền (Spatie roles) --}}
                    <td>
                        @php $roleName = $user->getRoleNames()->first(); @endphp
                        @if($roleName)
                        <span class="badge badge-success">{{ $roleName }}</span>
                        @else
                        <span class="badge badge-secondary">No role</span>
                        @endif
                    </td>

                    <td>
                        @can('users.edit')
                        <a href="{{ route('admin.users.edit', $user) }}"
                            class="btn btn-warning btn-sm"
                            title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan

                        @can('users.delete')
                        <div class="position-relative d-inline-block confirm-wrapper">
                            <button type="button" class="btn btn-danger btn-sm" title="Xoá" onclick="showDeleteConfirm(this)">
                                <i class="fas fa-trash"></i>
                            </button>

                            <div class="confirm-box shadow-sm rounded bg-white border p-2"
                                style="display: none; position: absolute; bottom: 100%; right: 0; margin-bottom: 8px; width: 140px; z-index: 1050;">
                                <div class="text-center font-weight-bold mb-2" style="font-size: 13px; color: #333;">Xóa user này?</div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" style="font-size: 12px; padding: 2px 10px;" onclick="hideDeleteConfirm(this)">Hủy</button>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="font-size: 12px; padding: 2px 10px;">OK</button>
                                    </form>
                                </div>
                                <div style="position: absolute; bottom: -6px; right: 12px; width: 10px; height: 10px; background: white; border-bottom: 1px solid #dee2e6; border-right: 1px solid #dee2e6; transform: rotate(45deg);"></div>
                            </div>
                        </div>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">
                        Chưa có user hoặc không tìm thấy kết quả phù hợp.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<script>
    function showDeleteConfirm(btn) {
        document.querySelectorAll('.confirm-box').forEach(box => box.style.display = 'none');
       
        btn.nextElementSibling.style.display = 'block';
    }

    function hideDeleteConfirm(btn) {
        btn.closest('.confirm-box').style.display = 'none';
    }
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.confirm-wrapper')) {
            document.querySelectorAll('.confirm-box').forEach(box => box.style.display = 'none');
        }
    });
</script>
@endsection