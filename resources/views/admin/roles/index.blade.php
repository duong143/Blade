@extends('admin.layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách Roles</h3>

        <div class="card-tools">
            @can('roles.create')
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm role
            </a>
            @endcan
        </div>
    </div>

    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width:60px">#</th>
                    <th>Tên role</th>
                    <th style="width:140px">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $i => $role)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        @can('roles.edit')
                        <a class="btn btn-warning btn-sm" href="{{ route('admin.roles.edit', $role) }}" title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan

                        @can('roles.delete')
                        @if($role->name !== 'admin')
                        <form action="{{ route('admin.roles.destroy', $role) }}"
                            method="POST" class="d-inline"
                            onsubmit="return confirm('Xoá role này?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" title="Xoá">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">Chưa có role</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection