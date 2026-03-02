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
                        <form action="{{ route('admin.users.destroy', $user) }}"
                            method="POST"
                            class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="btn btn-danger btn-sm"
                                title="Xoá"
                                onclick="return confirm('Xoá user này?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">
                        Chưa có user
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection