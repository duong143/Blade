@extends('layout')
@section('info-strip')
{{-- Ẩn info-strip cho trang combo --}}
@endsection
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<style>
    .attraction-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        padding: 30px 0;
    }

    .attraction-card {
        cursor: pointer;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .attraction-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
    }

    .attraction-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .attraction-card .info {
        padding: 15px;
        text-align: center;
        font-weight: bold;
        font-size: 1.1rem;
        color: #333;
    }

    .modal-xl {
        max-width: 1000px;
    }

    .modal-content {
        border-radius: 20px;
        border: none;
        overflow: hidden;
    }

    .modal-body {
        padding: 0;
    }

    .popup-wrapper {
        display: flex;
        flex-wrap: wrap;
    }

    .popup-left {
        flex: 0 0 50%;
        max-width: 50%;
    }

    .popup-right {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 40px;
        background: #fff;
    }

    #modal-img {
        width: 100%;
        height: 100%;
        min-height: 450px;
        object-fit: cover;
    }

    #modal-name {
        font-weight: 800;
        color: #007bff;
        text-transform: uppercase;
        margin-bottom: 20px;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
    }

    #modal-desc {
        line-height: 1.8;
        color: #555;
        text-align: justify;
        font-size: 1.05rem;
    }

    @media (max-width: 768px) {

        .popup-left,
        .popup-right {
            flex: 0 0 100%;
            max-width: 100%;
        }

        #modal-img {
            min-height: 250px;
        }
    }
</style>

{{-- 1. Banner Hero --}}
<div class="show-hero" style="height: 400px; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset($destination->image) }}') center/cover; display: flex; align-items: center; justify-content: center; color: white;">
    <h1 class="display-3 fw-bold text-uppercase">{{ $destination->name }}</h1>
</div>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">ĐỊA ĐIỂM ĂN CHƠI / CHECK-IN</h2>
        <p class="text-muted">Nhấn vào hình ảnh để xem chi tiết kinh nghiệm</p>
    </div>

    {{-- 2. Danh sách Attraction --}}
    <div class="attraction-grid">
        @forelse($destination->attractions as $spot)
        <div class="attraction-card js-attraction-item"
            data-name="{{ $spot->name }}"
            data-image="{{ asset($spot->image) }}"
            data-description="{{ $spot->description }}">
            <img src="{{ asset($spot->image) }}" alt="{{ $spot->name }}">
            <div class="info">{{ $spot->name }}</div>
        </div>
        @empty
        <div class="w-100 text-center py-5">Chưa có dữ liệu địa điểm.</div>
        @endforelse
    </div>
</div>

{{-- 3. Cấu trúc Modal --}}
<div class="modal fade" id="attractionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" style="position: absolute; right: 20px; top: 15px; z-index: 99; font-size: 2rem;">&times;</button>

                <div class="popup-wrapper">
                    <div class="popup-left">
                        <img id="modal-img" src="">
                    </div>
                    <div class="popup-right">
                        <h2 id="modal-name"></h2>
                        <div id="modal-desc"></div>
                        <div class="mt-5">
                            <button type="button" class="btn btn-secondary px-5" data-dismiss="modal">Đóng lại</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).on('click', '.js-attraction-item', function() {
        // Lấy dữ liệu an toàn từ các thuộc tính data-
        const name = $(this).data('name');
        const img = $(this).data('image');
        const desc = $(this).data('description');

        // Đổ dữ liệu vào Modal
        $('#modal-name').text(name);
        $('#modal-img').attr('src', img);

        if (desc && desc !== 'null' && desc.trim() !== '') {
            // Xử lý thông minh dấu xuống dòng thành thẻ <br>
            $('#modal-desc').html(desc.replace(/\r?\n/g, '<br>'));
        } else {
            $('#modal-desc').text('Hiện chưa có mô tả cho địa điểm này.');
        }

        // Kích hoạt hiển thị Modal
        $('#attractionModal').modal('show');
    });
</script>

@endsection