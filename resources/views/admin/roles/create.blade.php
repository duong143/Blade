@extends('admin.layout')

@section('content')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Thêm Role</h3>
    </div>

    <form method="POST" action="{{ route('admin.roles.store') }}">
        @csrf

        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="form-group">
                <label>Tên role</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <hr>

            <label><strong>Phân quyền</strong></label>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Module</th>
                        <th style="width:120px">View</th>
                        <th style="width:120px">Create</th>
                        <th style="width:120px">Edit</th>
                        <th style="width:120px">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissionGroups as $module => $actions)
                    <tr>
                        <td class="text-capitalize">{{ $module }}</td>
                        @foreach(['view','create','edit','delete'] as $act)
                        <td>
                            @if($actions[$act])
                            <div class="form-check">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $actions[$act] }}"
                                    {{ in_array($actions[$act], old('permissions', [])) ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $act }}</label>
                            </div>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <div class="card-footer">
            <button class="btn btn-primary">
                <i class="fas fa-save"></i> Lưu
            </button>
            <a class="btn btn-secondary" href="{{ route('admin.roles.index') }}">Quay lại</a>
        </div>
    </form>
</div>
@endsection