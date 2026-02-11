@extends('admin.layout')

@section('content')
<h2>Tin tức du lịch</h2>
<form method="GET" action="{{ route('admin.news.index') }}" class="mb-3">
    <div class="row">

        {{-- Tìm theo tiêu đề --}}
        <div class="col-md-5">
            <input type="text"
                name="title"
                class="form-control"
                placeholder="Tìm theo tiêu đề"
                value="{{ request('title') }}">
        </div>

        {{-- Lọc theo hiển thị --}}
        <div class="col-md-3">
            <select name="is_active" class="form-control">
                <option value="">-- Hiển thị --</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>
                    Bật
                </option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>
                    Tắt
                </option>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">
                Lọc
            </button>
        </div>

    </div>
</form>

<a href="{{ route('admin.news.create') }}" class="btn btn-primary mb-3">
    + Thêm tin
</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Ảnh</th>
            <th>Tiêu đề</th>
            <th>Hiển thị</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($news as $item)
        <tr>
            <td>
                @if ($item->images && $item->images->count() > 0)
                <img
                    src="{{ asset('storage/' . $item->images->first()->image) }}"
                    width="300">
                @else
                <span>Không có ảnh</span>
                @endif
            </td>

            <td>{{ $item->title }}</td>
            <td>{{ $item->is_active ? 'Bật' : 'Tắt' }}</td>
            <td>
                <a href="{{ route('admin.news.edit', $item) }}"
                    class="btn btn-sm btn-warning">
                    ✏️
                </a>

                <form action="{{ route('admin.news.destroy', $item) }}"
                    method="POST"
                    style="display:inline-block"
                    onsubmit="return confirm('Xoá tin này?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">🗑</button>
                </form>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-between align-items-center mt-3">

    {{-- Chọn số dòng --}}
    <form method="GET" action="{{ route('admin.news.index') }}">
        {{-- Giữ lại filter --}}
        <input type="hidden" name="title" value="{{ request('title') }}">
        <input type="hidden" name="is_active" value="{{ request('is_active') }}">

        <select name="per_page"
            class="form-control"
            onchange="this.form.submit()"
            style="width: 120px;">
            <option value="2" {{ request('per_page', 5) == 2 ? 'selected' : '' }}>2 dòng</option>
            <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5 dòng</option>
            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 dòng</option>
            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 dòng</option>
        </select>
    </form>

    {{-- Phân trang --}}
    <div>
        {{ $news->links('pagination::bootstrap-5') }}
    </div>

</div>

@endsection