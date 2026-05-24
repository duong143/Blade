@extends('admin.layout')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Thêm combo</h3>
  </div>

  <div class="card-body">
    @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $err)
        <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.combos.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Mã combo</label>
          <input name="code" class="form-control" value="{{ old('code') }}" placeholder="VD: CBPHUQUOC01">
          <small class="text-muted">Bỏ trống sẽ tự sinh</small>
        </div>

        <div class="col-md-8 mb-3">
          <label class="form-label">Tên combo</label>
          <input name="title" class="form-control" value="{{ old('title') }}" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Ảnh đại diện combo</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        <small class="text-muted">Ảnh này dùng làm ảnh đại diện ở danh sách combo và làm ảnh dự phòng nếu chưa có ảnh slider</small>
      </div>

      <div class="mb-3">
        <label class="form-label">Ảnh nội dung chi tiết</label>
        <input type="file" name="content_images[]" multiple class="form-control" accept="image/*">
        <small class="text-muted">Ảnh này hiển thị ở giữa phần nội dung chi tiết combo</small>
      </div>

      <div class="mb-3">
        <label class="form-label">Ảnh slider đầu trang</label>
        <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
        <small class="text-muted">Có thể chọn nhiều ảnh để hiển thị ở banner đầu trang chi tiết combo</small>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Điểm đi</label>
          <input name="from_location" class="form-control" value="{{ old('from_location') }}" placeholder="Hà Nội">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Điểm đến</label>
          <input name="to_location" class="form-control" value="{{ old('to_location') }}" placeholder="Phú Quốc">
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Số ngày</label>
          <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days', 3) }}" min="1">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Số đêm</label>
          <input type="number" name="duration_nights" class="form-control" value="{{ old('duration_nights', 2) }}" min="0">
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Đặt trước (ngày)</label>
          <input type="number" name="preorder_days" class="form-control" value="{{ old('preorder_days', 7) }}" min="0">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Mô tả ngắn</label>
        <textarea name="short_desc" class="form-control" rows="2">{{ old('short_desc') }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Tiện nghi khách sạn</label>
        <textarea name="hotel_amenities" class="form-control" rows="6">{{ old('hotel_amenities') }}</textarea>
        <small class="text-muted">Nhập nội dung phần hiển thị dưới tiêu đề "Tiện nghi khách sạn"</small>
      </div>

      <div class="mb-3">
        <label class="form-label">Mô tả chi tiết</label>
        <textarea name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Lịch trình chi tiết</label>
        <textarea name="itinerary_detail" class="form-control" rows="8">{{ old('itinerary_detail') }}</textarea>
        <small class="text-muted">Nội dung phần hiển thị dưới tiêu đề "Lịch trình chi tiết"</small>
      </div>

      <div class="mb-3">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-select">
          <option value="1" selected>Hiển thị</option>
          <option value="0">Ẩn</option>
        </select>
      </div>

      @php
      $oldDepartures = old('departures', [
      [
      'start_date' => '',
      'end_date' => '',
      'capacity' => 1,
      'sold' => 0,
      'status' => 1,
      'prices' => [
      'adult' => 0,
      'child' => 0,
      'infant' => 0,
      ],
      'sales' => [],
      ]
      ]);
      @endphp

      <hr>
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Khoảng ngày khách được phép chọn</h4>
        <button type="button" class="btn btn-sm btn-primary" id="add-departure-btn">+ Thêm đợt khởi hành</button>
      </div>

      <div id="departures-wrapper">
        @foreach($oldDepartures as $departureIndex => $departure)
        <div class="card mb-4 departure-item">
          <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Đợt khởi hành <span class="departure-number">{{ $departureIndex + 1 }}</span></strong>
            <button type="button" class="btn btn-sm btn-danger remove-departure-btn">Xóa đợt này</button>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-md-3 mb-3">
                <label class="form-label">Khách được chọn đi từ ngày</label>
                <input type="date" name="departures[{{ $departureIndex }}][start_date]" class="form-control"
                  value="{{ $departure['start_date'] ?? '' }}" required>
              </div>

              <div class="col-md-3 mb-3">
                <label class="form-label">Khách được chọn đi đến ngày</label>
                <input type="date" name="departures[{{ $departureIndex }}][end_date]" class="form-control"
                  value="{{ $departure['end_date'] ?? '' }}">
              </div>

              <div class="col-md-2 mb-3">
                <label class="form-label">Số lượng</label>
                <input type="number" name="departures[{{ $departureIndex }}][capacity]" class="form-control"
                  value="{{ $departure['capacity'] ?? 1 }}" min="1" required>
              </div>

              <div class="col-md-2 mb-3">
                <label class="form-label">Đã bán</label>
                <input type="number" name="departures[{{ $departureIndex }}][sold]" class="form-control"
                  value="{{ $departure['sold'] ?? 0 }}" min="0">
              </div>

              <div class="col-md-2 mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="departures[{{ $departureIndex }}][status]" class="form-select">
                  <option value="1" {{ (string)($departure['status'] ?? 1) === '1' ? 'selected' : '' }}>Hiển thị</option>
                  <option value="0" {{ (string)($departure['status'] ?? 1) === '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
              </div>
            </div>

            <div class="border rounded p-3 mb-3">
              <h5>Giá theo loại khách</h5>
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label class="form-label">Người lớn</label>
                  <input type="number" name="departures[{{ $departureIndex }}][prices][adult]" class="form-control"
                    value="{{ $departure['prices']['adult'] ?? 0 }}" min="0" required>
                </div>

                <div class="col-md-4 mb-3">
                  <label class="form-label">Trẻ em</label>
                  <input type="number" name="departures[{{ $departureIndex }}][prices][child]" class="form-control"
                    value="{{ $departure['prices']['child'] ?? 0 }}" min="0" required>
                </div>

                <div class="col-md-4 mb-3">
                  <label class="form-label">Em bé</label>
                  <input type="number" name="departures[{{ $departureIndex }}][prices][infant]" class="form-control"
                    value="{{ $departure['prices']['infant'] ?? 0 }}" min="0" required>
                </div>
              </div>
            </div>

            <div class="border rounded p-3">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Sale theo khoảng ngày</h5>
                <button type="button" class="btn btn-sm btn-outline-primary add-sale-btn">+ Thêm sale</button>
              </div>

              <div class="sales-wrapper">
                @foreach(($departure['sales'] ?? []) as $saleIndex => $sale)
                <div class="border rounded p-3 mb-3 sale-item">
                  <div class="row">
                    <div class="col-md-3 mb-3">
                      <label class="form-label">Từ ngày</label>
                      <input type="date"
                        name="departures[{{ $departureIndex }}][sales][{{ $saleIndex }}][start_date]"
                        class="form-control"
                        value="{{ $sale['start_date'] ?? '' }}">
                    </div>

                    <div class="col-md-3 mb-3">
                      <label class="form-label">Đến ngày</label>
                      <input type="date"
                        name="departures[{{ $departureIndex }}][sales][{{ $saleIndex }}][end_date]"
                        class="form-control"
                        value="{{ $sale['end_date'] ?? '' }}">
                    </div>

                    <div class="col-md-2 mb-3">
                      <label class="form-label">% Sale</label>
                      <input type="number"
                        name="departures[{{ $departureIndex }}][sales][{{ $saleIndex }}][sale_percent]"
                        class="form-control"
                        value="{{ $sale['sale_percent'] ?? 0 }}"
                        min="0"
                        max="100">
                    </div>

                    <div class="col-md-3 mb-3">
                      <label class="form-label">Nhãn sale</label>
                      <input type="text"
                        name="departures[{{ $departureIndex }}][sales][{{ $saleIndex }}][sale_label]"
                        class="form-control"
                        value="{{ $sale['sale_label'] ?? '' }}"
                        placeholder="VD: Flash Sale">
                    </div>

                    <div class="col-md-1 mb-3 d-flex align-items-end">
                      <button type="button" class="btn btn-sm btn-danger remove-sale-btn w-100">Xóa</button>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      <button class="btn btn-primary">Lưu</button>
      <a href="{{ route('admin.combos.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
  </div>
</div>

<script>
  (function() {
    const departuresWrapper = document.getElementById('departures-wrapper');
    const addDepartureBtn = document.getElementById('add-departure-btn');

    function reindexDepartures() {
      const departureItems = departuresWrapper.querySelectorAll('.departure-item');

      departureItems.forEach((departureItem, departureIndex) => {
        const numberEl = departureItem.querySelector('.departure-number');
        if (numberEl) {
          numberEl.textContent = departureIndex + 1;
        }

        departureItem.querySelectorAll('input, select, textarea').forEach((field) => {
          if (!field.name) return;

          field.name = field.name.replace(/departures\[\d+\]/, `departures[${departureIndex}]`);
        });

        const saleItems = departureItem.querySelectorAll('.sale-item');
        saleItems.forEach((saleItem, saleIndex) => {
          saleItem.querySelectorAll('input, select, textarea').forEach((field) => {
            if (!field.name) return;

            field.name = field.name.replace(/sales\[\d+\]/, `sales[${saleIndex}]`);
          });
        });
      });
    }

    function createSaleHtml(departureIndex, saleIndex) {
      return `
        <div class="border rounded p-3 mb-3 sale-item">
          <div class="row">
            <div class="col-md-3 mb-3">
              <label class="form-label">Từ ngày</label>
              <input type="date"
                name="departures[${departureIndex}][sales][${saleIndex}][start_date]"
                class="form-control">
            </div>

            <div class="col-md-3 mb-3">
              <label class="form-label">Đến ngày</label>
              <input type="date"
                name="departures[${departureIndex}][sales][${saleIndex}][end_date]"
                class="form-control">
            </div>

            <div class="col-md-2 mb-3">
              <label class="form-label">% Sale</label>
              <input type="number"
                name="departures[${departureIndex}][sales][${saleIndex}][sale_percent]"
                class="form-control"
                value="0"
                min="0"
                max="100">
            </div>

            <div class="col-md-3 mb-3">
              <label class="form-label">Nhãn sale</label>
              <input type="text"
                name="departures[${departureIndex}][sales][${saleIndex}][sale_label]"
                class="form-control"
                placeholder="VD: Flash Sale">
            </div>

            <div class="col-md-1 mb-3 d-flex align-items-end">
              <button type="button" class="btn btn-sm btn-danger remove-sale-btn w-100">Xóa</button>
            </div>
          </div>
        </div>
      `;
    }

    function createDepartureHtml(departureIndex) {
      return `
        <div class="card mb-4 departure-item">
          <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Đợt khởi hành <span class="departure-number">${departureIndex + 1}</span></strong>
            <button type="button" class="btn btn-sm btn-danger remove-departure-btn">Xóa đợt này</button>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-md-3 mb-3">
                <label class="form-label">Ngày đi</label>
                <input type="date" name="departures[${departureIndex}][start_date]" class="form-control" required>
              </div>

              <div class="col-md-3 mb-3">
                <label class="form-label">Ngày về</label>
                <input type="date" name="departures[${departureIndex}][end_date]" class="form-control">
              </div>

              <div class="col-md-2 mb-3">
                <label class="form-label">Số lượng</label>
                <input type="number" name="departures[${departureIndex}][capacity]" class="form-control" value="1" min="1" required>
              </div>

              <div class="col-md-2 mb-3">
                <label class="form-label">Đã bán</label>
                <input type="number" name="departures[${departureIndex}][sold]" class="form-control" value="0" min="0">
              </div>

              <div class="col-md-2 mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="departures[${departureIndex}][status]" class="form-select">
                  <option value="1" selected>Hiển thị</option>
                  <option value="0">Ẩn</option>
                </select>
              </div>
            </div>

            <div class="border rounded p-3 mb-3">
              <h5>Giá theo loại khách</h5>
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label class="form-label">Người lớn</label>
                  <input type="number" name="departures[${departureIndex}][prices][adult]" class="form-control" value="0" min="0" required>
                </div>

                <div class="col-md-4 mb-3">
                  <label class="form-label">Trẻ em</label>
                  <input type="number" name="departures[${departureIndex}][prices][child]" class="form-control" value="0" min="0" required>
                </div>

                <div class="col-md-4 mb-3">
                  <label class="form-label">Em bé</label>
                  <input type="number" name="departures[${departureIndex}][prices][infant]" class="form-control" value="0" min="0" required>
                </div>
              </div>
            </div>

            <div class="border rounded p-3">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Sale theo khoảng ngày</h5>
                <button type="button" class="btn btn-sm btn-outline-primary add-sale-btn">+ Thêm sale</button>
              </div>

              <div class="sales-wrapper"></div>
            </div>
          </div>
        </div>
      `;
    }

    addDepartureBtn.addEventListener('click', function() {
      const departureIndex = departuresWrapper.querySelectorAll('.departure-item').length;
      departuresWrapper.insertAdjacentHTML('beforeend', createDepartureHtml(departureIndex));
    });

    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('remove-departure-btn')) {
        const departureItems = departuresWrapper.querySelectorAll('.departure-item');

        if (departureItems.length <= 1) {
          alert('Combo phải có ít nhất 1 đợt khởi hành.');
          return;
        }

        e.target.closest('.departure-item').remove();
        reindexDepartures();
      }

      if (e.target.classList.contains('add-sale-btn')) {
        const departureItem = e.target.closest('.departure-item');
        const departureIndex = Array.from(departuresWrapper.querySelectorAll('.departure-item')).indexOf(departureItem);
        const salesWrapper = departureItem.querySelector('.sales-wrapper');
        const saleIndex = salesWrapper.querySelectorAll('.sale-item').length;

        salesWrapper.insertAdjacentHTML('beforeend', createSaleHtml(departureIndex, saleIndex));
      }

      if (e.target.classList.contains('remove-sale-btn')) {
        e.target.closest('.sale-item').remove();
        reindexDepartures();
      }
    });
  })();
</script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
  function initEditor(selector) {
    ClassicEditor
      .create(document.querySelector(selector))
      .catch(error => {
        console.error(error);
      });
  }

  initEditor('textarea[name="short_desc"]');
  initEditor('textarea[name="description"]');
  initEditor('textarea[name="itinerary_detail"]');
  initEditor('textarea[name="hotel_amenities"]');
</script>
@endsection