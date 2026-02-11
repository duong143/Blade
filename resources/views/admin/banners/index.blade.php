@extends('admin.layout')

@section('content')
<h2>Banner & Ưu đãi</h2>
<form method="GET" action="{{ route('admin.banners.index') }}" class="mb-3">
    <div class="row">

        <div class="col-md-4">
            <input type="text"
                name="title"
                class="form-control"
                placeholder="Tìm theo tiêu đề"
                value="{{ request('title') }}">
        </div>

        <div class="col-md-3">
            <select name="type" class="form-control">
                <option value="">-- Loại banner --</option>
                <option value="main" {{ request('type') == 'main' ? 'selected' : '' }}>
                    Banner lớn
                </option>
                <option value="small" {{ request('type') == 'small' ? 'selected' : '' }}>
                    Banner nhỏ
                </option>
            </select>
        </div>

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


<a href="{{ route('admin.banners.create') }}" class="btn btn-primary mb-3">
    + Thêm banner
</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Ảnh</th>
            <th>Tiêu đề</th>
            <th>Loại</th>
            <th>Hiển thị</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($banners as $banner)
        <tr>
            <td>
                <img src="{{ asset('storage/'.$banner->image) }}" width="200">
            </td>
            <td>{{ $banner->title }}</td>
            <td>
                @if($banner->type === 'main')
                <span class="badge badge-primary">Banner lớn</span>
                @else
                <span class="badge badge-info">Banner nhỏ</span>
                @endif
            </td>

            <td>
                {{ $banner->is_active ? 'Bật' : 'Tắt' }}
            </td>
            <td>
                <a href="{{ route('admin.banners.edit', $banner) }}"
                    class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                </a>

                <form action="{{ route('admin.banners.destroy', $banner) }}"
                    method="POST"
                    style="display:inline-block"
                    onsubmit="return confirm('Xoá banner này?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </td>

        </tr>

        @endforeach
    </tbody>

</table>
<div class="d-flex justify-content-between align-items-center mt-3">

    {{-- Chọn số dòng --}}
    <form method="GET" action="{{ route('admin.banners.index') }}">
        {{-- Giữ lại filter cũ --}}
        <input type="hidden" name="title" value="{{ request('title') }}">
        <input type="hidden" name="type" value="{{ request('type') }}">
        <input type="hidden" name="is_active" value="{{ request('is_active') }}">

        <select name="per_page"
            class="form-control"
            onchange="this.form.submit()"
            style="width: 120px;">
            <option value="2" {{ request('per_page', 2) == 2 ? 'selected' : '' }}>2 dòng</option>
            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 dòng</option>
            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 dòng</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 dòng</option>
        </select>
    </form>

</div>


<div class="d-flex justify-content-center mt-3">
    {{ $banners->links() }}
</div>

@endsection