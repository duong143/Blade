@extends('admin.layout')

@section('title', 'Thêm chuyến bay')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm chuyến bay</h3>
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

        <form method="POST" action="{{ route('admin.flights.store') }}">
            @csrf

            <div class="border rounded p-3 mb-4">
                <h5 class="mb-3">Thông tin cơ bản</h5>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Hãng bay</label>
                        <select name="airline_id" class="form-control" required>
                            <option value="">-- Chọn hãng bay --</option>
                            @foreach($airlines as $airline)
                            <option value="{{ $airline->id }}" @selected(old('airline_id')==$airline->id)>
                                {{ $airline->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sân bay đi</label>
                        <select name="departure_airport_id" class="form-control" required>
                            <option value="">-- Chọn sân bay đi --</option>
                            @foreach($airports as $airport)
                            <option value="{{ $airport->id }}" @selected(old('departure_airport_id')==$airport->id)>
                                {{ $airport->city }} ({{ $airport->code }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sân bay đến</label>
                        <select name="arrival_airport_id" class="form-control" required>
                            <option value="">-- Chọn sân bay đến --</option>
                            @foreach($airports as $airport)
                            <option value="{{ $airport->id }}" @selected(old('arrival_airport_id')==$airport->id)>
                                {{ $airport->city }} ({{ $airport->code }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Mã chuyến bay</label>
                        <input type="text" name="flight_number" class="form-control" value="{{ old('flight_number') }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Ngày đi</label>
                        <input type="date" name="departure_date" class="form-control" value="{{ old('departure_date') }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Giờ đi</label>
                        <input type="time" name="departure_time" class="form-control" value="{{ old('departure_time') }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Thời lượng (phút)</label>
                        <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', 0) }}" min="0" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Ngày đến</label>
                        <input type="date" name="arrival_date" class="form-control" value="{{ old('arrival_date') }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Giờ đến</label>
                        <input type="time" name="arrival_time" class="form-control" value="{{ old('arrival_time') }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Bay thẳng</label>
                        <select name="is_direct" class="form-control">
                            <option value="1" @selected(old('is_direct', 1)==1)>Bay thẳng</option>
                            <option value="0" @selected(old('is_direct')==='0' )>Có điểm dừng</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="is_active" class="form-control">
                            <option value="1" @selected(old('is_active', 1)==1)>Hiển thị</option>
                            <option value="0" @selected(old('is_active')==='0' )>Ẩn</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="border rounded p-3 mb-4">
                <h5 class="mb-3">Thông tin chuyến bay</h5>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Máy bay</label>
                        <input type="text" name="aircraft" class="form-control" value="{{ old('aircraft') }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sơ đồ ghế</label>
                        <input type="text" name="seat_layout" class="form-control" value="{{ old('seat_layout') }}" placeholder="VD: Sơ đồ ghế 3-3">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Khoảng cách ghế</label>
                        <input type="text" name="seat_pitch" class="form-control" value="{{ old('seat_pitch') }}" placeholder="VD: 32 inch">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Hạng ghế</label>
                        <input type="text" name="seat_class" class="form-control" value="{{ old('seat_class', 'Economy Class') }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Hành lý xách tay</label>
                        <input type="text" name="carry_on_baggage" class="form-control" value="{{ old('carry_on_baggage') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Hành lý ký gửi</label>
                        <input type="text" name="checked_baggage" class="form-control" value="{{ old('checked_baggage') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Điểm thưởng / text phụ</label>
                        <input type="text" name="fare_points" class="form-control" value="{{ old('fare_points') }}" placeholder="VD: +17 điểm">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tiện ích khác</label>
                    <textarea name="other_benefits" class="form-control" rows="3">{{ old('other_benefits') }}</textarea>
                    <small class="text-muted">Có thể nhập bằng dấu phẩy để ngoài frontend tách dòng sau.</small>
                </div>
            </div>

            <div class="border rounded p-3 mb-4">
                <h5 class="mb-3">Giá và số ghế</h5>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Giá người lớn</label>
                        <input type="number" name="adult_price" class="form-control" value="{{ old('adult_price', 0) }}" min="0" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Giá trẻ em</label>
                        <input type="number" name="child_price" class="form-control" value="{{ old('child_price', 0) }}" min="0">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Giá em bé</label>
                        <input type="number" name="infant_price" class="form-control" value="{{ old('infant_price', 0) }}" min="0">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Thuế & phí gốc</label>
                        <input type="number" name="tax_fee" class="form-control" value="{{ old('tax_fee', 0) }}" min="0">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tổng ghế</label>
                        <input type="number" name="total_seats" class="form-control" value="{{ old('total_seats', 0) }}" min="0" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Ghế còn lại</label>
                        <input type="number" name="available_seats" class="form-control" value="{{ old('available_seats', 0) }}" min="0" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Thứ tự hiển thị</label>
                        <input type="number" name="display_order" class="form-control" value="{{ old('display_order', 0) }}" min="0">
                    </div>
                </div>
            </div>

            @php
            $oldPriceItems = old('price_items', [
            ['label' => 'Người lớn', 'amount' => 0, 'sort_order' => 0, 'is_active' => 1],
            ['label' => 'Thuế & phí', 'amount' => 0, 'sort_order' => 1, 'is_active' => 1],
            ]);

            $oldConditions = old('conditions', [
            ['label' => 'Hành lý xách tay', 'value' => '7kg', 'is_enabled' => 1, 'sort_order' => 0, 'is_active' => 1],
            ['label' => 'Hành lý ký gửi', 'value' => 'Trả phí', 'is_enabled' => 1, 'sort_order' => 1, 'is_active' => 1],
            ]);
            @endphp

            <div class="border rounded p-3 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Chi tiết giá vé</h5>
                    <button type="button" class="btn btn-sm btn-primary" id="add-price-item-btn">+ Thêm dòng giá</button>
                </div>

                <div id="price-items-wrapper">
                    @foreach($oldPriceItems as $index => $item)
                    <div class="border rounded p-3 mb-3 price-item">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tên dòng giá</label>
                                <input type="text" name="price_items[{{ $index }}][label]" class="form-control" value="{{ $item['label'] ?? '' }}">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Số tiền</label>
                                <input type="number" name="price_items[{{ $index }}][amount]" class="form-control" value="{{ $item['amount'] ?? 0 }}" min="0">
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="form-label">Thứ tự</label>
                                <input type="number" name="price_items[{{ $index }}][sort_order]" class="form-control" value="{{ $item['sort_order'] ?? $index }}" min="0">
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="form-label">Hiển thị</label>
                                <select name="price_items[{{ $index }}][is_active]" class="form-control">
                                    <option value="1" {{ (string)($item['is_active'] ?? 1) === '1' ? 'selected' : '' }}>Có</option>
                                    <option value="0" {{ (string)($item['is_active'] ?? 1) === '0' ? 'selected' : '' }}>Không</option>
                                </select>
                            </div>

                            <div class="col-md-1 mb-3 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-price-item-btn w-100">Xóa</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @php
            $defaultConditions = [
            'Hành lý xách tay',
            'Hành lý ký gửi',
            'Suất ăn',
            'Thay đổi chuyến bay',
            'Đổi tên',
            'Hoàn vé',
            'Chọn ghế ngồi',
            'Phòng chờ thương gia',
            'Quầy thủ tục ưu tiên',
            ];
            @endphp

            <div class="card mt-3">
                <div class="card-header">
                    <strong>Điều kiện vé</strong>
                </div>
                <div class="card-body">
                    @foreach($defaultConditions as $index => $label)
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tên điều kiện</label>
                            <input type="hidden" name="conditions[{{ $index }}][label]" value="{{ $label }}">
                            <input type="text" class="form-control" value="{{ $label }}" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Giá trị hiển thị</label>
                            <input
                                type="text"
                                name="conditions[{{ $index }}][value]"
                                class="form-control"
                                value="{{ old('conditions.' . $index . '.value') }}"
                                placeholder="VD: 7kg / Trả phí / Không hỗ trợ">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Icon</label>
                            <select name="conditions[{{ $index }}][is_enabled]" class="form-control">
                                <option value="1" {{ old('conditions.' . $index . '.is_enabled', 0) == 1 ? 'selected' : '' }}>Bật</option>
                                <option value="0" {{ old('conditions.' . $index . '.is_enabled', 0) == 0 ? 'selected' : '' }}>Tắt</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Hiển thị</label>
                            <select class="form-control" disabled>
                                <option selected>Có</option>
                            </select>
                            <input type="hidden" name="conditions[{{ $index }}][sort_order]" value="{{ $index }}">
                            <input type="hidden" name="conditions[{{ $index }}][is_active]" value="1">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <button class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.flights.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>

<script>
    (function() {
        const priceItemsWrapper = document.getElementById('price-items-wrapper');
        const addPriceItemBtn = document.getElementById('add-price-item-btn');

        function reindexPriceItems() {
            const items = priceItemsWrapper.querySelectorAll('.price-item');
            items.forEach((item, index) => {
                item.querySelectorAll('input, select').forEach(field => {
                    if (!field.name) return;
                    field.name = field.name.replace(/price_items\[\d+\]/, `price_items[${index}]`);
                });
            });
        }

        function createPriceItemHtml(index) {
            return `
            <div class="border rounded p-3 mb-3 price-item">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tên dòng giá</label>
                        <input type="text" name="price_items[${index}][label]" class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Số tiền</label>
                        <input type="number" name="price_items[${index}][amount]" class="form-control" value="0" min="0">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Thứ tự</label>
                        <input type="number" name="price_items[${index}][sort_order]" class="form-control" value="${index}" min="0">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Hiển thị</label>
                        <select name="price_items[${index}][is_active]" class="form-control">
                            <option value="1" selected>Có</option>
                            <option value="0">Không</option>
                        </select>
                    </div>

                    <div class="col-md-1 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-price-item-btn w-100">Xóa</button>
                    </div>
                </div>
            </div>
        `;
        }

        addPriceItemBtn.addEventListener('click', function() {
            const index = priceItemsWrapper.querySelectorAll('.price-item').length;
            priceItemsWrapper.insertAdjacentHTML('beforeend', createPriceItemHtml(index));
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-price-item-btn')) {
                e.target.closest('.price-item').remove();
                reindexPriceItems();
            }
        });
    })();
</script>
@endsection