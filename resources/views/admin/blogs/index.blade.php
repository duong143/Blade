@extends('admin.layout')

@section('title', 'Quản lý Cẩm nang du lịch')

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
            <h3 class="card-title font-weight-bold">Danh sách bài viết Cẩm nang</h3>
            <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm ml-auto">
                <i class="fas fa-plus"></i> Viết bài mới
            </a>
        </div>
        <div class="card-body">
            <div class="border-bottom bg-light p-3 mb-3 rounded">
                <form action="{{ route('admin.blogs.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-9">
                            <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm theo tiêu đề..." value="{{ request('keyword') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-info px-4">Lọc</button>
                            <a href="{{ route('admin.blogs.index') }}" class="btn btn-default">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light text-center">
                        <tr>
                            <th width="5%">STT</th>
                            <th width="15%">Ảnh</th>
                            <th class="text-left">Tiêu đề</th>
                            <th width="15%">Ngày đăng</th>
                            <th width="12%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($blogs as $item)
                        <tr>
                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                            <td class="text-center align-middle">
                                <img src="{{ asset($item->image) }}" width="120" class="img-thumbnail" style="height: 70px; object-fit: cover;">
                            </td>
                            <td class="align-middle font-weight-bold">{{ $item->title }}</td>
                            <td class="text-center align-middle text-muted">{{ $item->created_at->format('d/m/Y') }}</td>
                            <td class="text-center align-middle">
                                <a href="{{ route('admin.blogs.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.blogs.destroy', $item->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có muốn xóa bài viết này không?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Chưa có bài viết nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3 d-flex justify-content-end">
                {{ $blogs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection