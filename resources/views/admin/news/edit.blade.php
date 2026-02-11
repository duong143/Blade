@extends('admin.layout')

@section('content')
<h2>Sửa tin tức</h2>

<form method="POST"
    action="{{ route('admin.news.update', $news) }}"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Tiêu đề</label>
        <input type="text" name="title"
            class="form-control"
            value="{{ $news->title }}" required>
    </div>

    @if($news->images->count())
    <div class="mb-3">
        <label>Ảnh hiện tại</label>
        <div class="d-flex flex-wrap">
            @foreach($news->images as $img)
            <div class="position-relative mr-2 mb-2 image-item" data-id="{{ $img->id }}">
                <img
                    src="{{ asset('storage/' . $img->image) }}"
                    width="450"
                    class="img-thumbnail">
                <button
                    type="button"
                    class="btn btn-danger btn-sm delete-image"
                    data-id="{{ $img->id }}"
                    style="position:absolute;top:5px;right:5px">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            @endforeach
        </div>
    </div>
    @endif

    <div class="mb-3">
        <label>Thêm ảnh mới</label>
        <input
            id="edit-images"
            type="file"
            name="images[]"
            class="file"
            multiple
            data-theme="fas">
    </div>

    <div class="mb-3">
        <label>Mô tả ngắn</label>
        <textarea name="excerpt" class="form-control">{{ $news->excerpt }}</textarea>
    </div>

    <div class="mb-3">
        <label>Nội dung</label>
        <textarea name="content" class="form-control" rows="5">{{ $news->content }}</textarea>
    </div>

    <div class="mb-3">
        <label>
            <input type="checkbox" name="is_active" {{ $news->is_active ? 'checked' : '' }}>
            Hiển thị
        </label>
    </div>

    <button class="btn btn-primary">Cập nhật</button>
</form>
@push('scripts')
<script>
    $(document).on('click', '.delete-image', function() {
        if (!confirm('Xóa ảnh này?')) return;

        let btn = $(this);
        let imageId = btn.data('id');

        $.ajax({
            url: "/admin/news-images/" + imageId,
            type: 'DELETE',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {
                if (res.success) {
                    btn.closest('.image-item').remove();
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert('Xóa ảnh thất bại');
            }
        });
    });
</script>
@endpush


<script>
    $(document).on('click', '.delete-image', function() {
        if (!confirm('Xóa ảnh này?')) return;

        let btn = $(this);
        let imageId = btn.data('id');

        $.ajax({
            url: "{{ url('/admin/news-images') }}/" + imageId,
            type: 'DELETE',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {
                if (res.success) {
                    btn.closest('.image-item').remove();
                }
            },
            error: function() {
                alert('Xóa ảnh thất bại');
            }
        });
    });
</script>


@endsection