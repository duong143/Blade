@extends('admin.layout')
@section('title', 'Quản lý Liên hệ')
@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold">Danh sách ý kiến khách hàng</h3>
    </div>
    <div class="row mb-3 justify-content-end">
        <div class="col-md-5">
            <form action="{{ route('admin.contacts.index') }}" method="GET">
                <div class="input-group">
                    <input type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="Tìm theo tên, email, số điện thoại..."
                        value="{{ $keyword ?? request('keyword') }}">

                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        @if(!empty(request('keyword')))
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Hủy lọc
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover">
            <thead class="bg-light text-center">
                <tr>
                    <th width="5%">#</th>
                    <th>Khách hàng</th>
                    <th>Email/SĐT</th>
                    <th>Chủ đề</th>
                    <th width="15%">Trạng thái</th>
                    <th width="12%">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $item)
                <tr class="{{ $item->is_read ? 'text-muted' : 'font-weight-bold' }}">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }} <br> <small>{{ $item->phone }}</small></td>
                    <td>{{ $item->subject }}</td>
                    <td class="text-center">
                        <span class="badge {{ $item->is_read ? 'badge-secondary' : 'badge-danger' }}">
                            {{ $item->is_read ? 'Đã xem' : 'Chưa xem' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.contacts.show', $item->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                        <form action="{{ route('admin.contacts.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Xóa tin này?')"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection