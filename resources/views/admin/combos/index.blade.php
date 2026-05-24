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
    <form method="GET" action="{{ route('admin.combos.index') }}" class="mb-3">
      <div class="row">
        <div class="col-md-3 mb-2">
          <input
            type="text"
            name="keyword"
            class="form-control"
            placeholder="Tìm mã hoặc tên combo..."
            value="{{ request('keyword') }}">
        </div>

        <div class="col-md-2 mb-2">
          <select name="status" class="form-control">
            <option value="">-- Trạng thái --</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
          </select>
        </div>

        <div class="col-md-3 mb-2">
          <select name="has_departure" class="form-control">
            <option value="">-- Đợt khởi hành --</option>
            <option value="1" {{ request('has_departure') === '1' ? 'selected' : '' }}>Có đợt khởi hành</option>
            <option value="0" {{ request('has_departure') === '0' ? 'selected' : '' }}>Chưa có đợt khởi hành</option>
          </select>
        </div>
      </div>

      <div class="mt-2">
        <button type="submit" class="btn btn-primary btn-sm">Lọc</button>
        <a href="{{ route('admin.combos.index') }}" class="btn btn-secondary btn-sm">Reset</a>
      </div>
    </form>
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
            <div class="form-check form-switch">
              <input
                class="form-check-input combo-status-toggle"
                type="checkbox"
                data-id="{{ $c->id }}"
                {{ $c->status ? 'checked' : '' }}>

              <span class="status-label ms-2">
                @if($c->status)
                <span class="badge bg-success">Hiển thị</span>
                @else
                <span class="badge bg-secondary">Ẩn</span>
                @endif
              </span>
            </div>
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

<script>
document.querySelectorAll('.combo-status-toggle').forEach(function(checkbox) {
  checkbox.addEventListener('change', function() {
    const comboId = this.dataset.id;
    const label = this.closest('td').querySelector('.status-label');
    const checkboxEl = this;
    const oldChecked = !this.checked;

    checkboxEl.disabled = true;

    fetch("{{ url('admin/combos') }}/" + comboId + "/toggle-status", {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(function(response) {
      if (!response.ok) {
        throw new Error('Request failed');
      }
      return response.json();
    })
    .then(function(data) {
      if (data.success) {
        if (data.status) {
          label.innerHTML = '<span class="badge bg-success">Hiển thị</span>';
          checkboxEl.checked = true;
        } else {
          label.innerHTML = '<span class="badge bg-secondary">Ẩn</span>';
          checkboxEl.checked = false;
        }
      } else {
        checkboxEl.checked = oldChecked;
        alert('Cập nhật trạng thái thất bại.');
      }
    })
    .catch(function() {
      checkboxEl.checked = oldChecked;
      alert('Có lỗi xảy ra khi cập nhật trạng thái.');
    })
    .finally(function() {
      checkboxEl.disabled = false;
    });
  });
});
</script>
@endsection