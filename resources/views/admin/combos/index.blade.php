@extends('admin.layout')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title mb-0">Danh sách Combo</h3>
    <a href="{{ route('admin.combos.create') }}" class="btn btn-primary btn-sm">+ Thêm combo</a>
  </div>

  <div class="card-body">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th style="width:80px">#</th>
          <th style="width:140px">Ảnh</th>
          <th>Tên</th>
          <th>Ngày/Đêm</th>
          <th style="width:120px">Trạng thái</th>
          <th style="width:160px">Hành động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($combos as $c)
        <tr>
          <td>{{ $c->id }}</td>
          <td>
            @if(!empty($c->image))
            <img src="{{ asset('storage/' . $c->image) }}"
              style="height:60px;width:120px;object-fit:cover;border-radius:6px;">
            @else
            <span class="text-muted">No image</span>
            @endif
          </td>
          <td>{{ $c->title }}</td>
          <td>{{ $c->duration_days }} ngày {{ $c->duration_nights }} đêm</td>
          <td>
            @if($c->status)
            <span class="badge bg-success">Hiển thị</span>
            @else
            <span class="badge bg-secondary">Ẩn</span>
            @endif
          </td>
          <td>
            <a class="btn btn-sm btn-warning" href="{{ route('admin.combos.edit', $c->id) }}">Sửa</a>

            <form action="{{ route('admin.combos.destroy', $c->id) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Xóa combo này?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-danger">Xóa</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center">Chưa có combo</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    {{ $combos->links() }}
  </div>
</div>
@endsection