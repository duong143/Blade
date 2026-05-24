@extends('admin.layout')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="content-header p-0 mb-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="m-0">Chi tiết đơn hàng combo</h1>
            <small class="text-muted">Mã đơn: {{ $booking->booking_code ?: '#' . $booking->id }}</small>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.combo-bookings.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Quay lại danh sách
            </a>
        </div>
    </div>
</div>

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    {{ $errors->first() }}
</div>
@endif

<div class="row">
    <div class="col-md-4">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Người mua</span>
                <span class="info-box-number">{{ $booking->contact_name ?: '--' }}</span>
                <span>{{ $booking->contact_phone ?: '--' }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-wallet"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Thanh toán</span>
                <span class="info-box-number">{{ number_format($booking->final_amount, 0, ',', '.') }} đ</span>
                <span>{{ match($booking->payment_method) {
    'vnpt' => 'VNPT Pay',
    'atm' => 'ATM / Chuyển khoản',
    'international' => 'Thanh toán quốc tế',
    default => '--'
} }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="info-box">
            <a
                href="{{ $booking->combo ? route('admin.combos.edit', $booking->combo->id) : 'javascript:void(0)' }}"
                class="info-box-icon bg-warning"
                title="Xem combo"
                style="text-decoration: none;">
                <i class="fas fa-suitcase-rolling"></i>
            </a>
            <div class="info-box-content">
                <span class="info-box-text">Combo</span>
                <span class="info-box-number" style="font-size: 16px;">
                    {{ $booking->combo?->title ?: '--' }}
                </span>
                <span>
                    {{ $booking->travel_start_date ? $booking->travel_start_date->format('d/m/Y') : '--' }}
                    -
                    {{ $booking->travel_end_date ? $booking->travel_end_date->format('d/m/Y') : '--' }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <strong>Thông tin chung</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th style="width: 220px;">Mã đơn hàng</th>
                        <td>{{ $booking->booking_code ?: '#' . $booking->id }}</td>
                    </tr>
                    <tr>
                        <th>Người mua</th>
                        <td>{{ $booking->contact_name }}</td>
                    </tr>
                    <tr>
                        <th>Số điện thoại</th>
                        <td>{{ $booking->contact_phone }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $booking->contact_email ?: '--' }}</td>
                    </tr>
                    <tr>
                        <th>Combo</th>
                        <td>{{ $booking->combo?->title ?: '--' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày đi</th>
                        <td>{{ $booking->travel_start_date ? $booking->travel_start_date->format('d/m/Y') : '--' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày về</th>
                        <td>{{ $booking->travel_end_date ? $booking->travel_end_date->format('d/m/Y') : '--' }}</td>
                    </tr>
                    <tr>
                        <th>Phương thức thanh toán</th>
                        <td>{{ match($booking->payment_method) {
    'vnpt' => 'VNPT Pay',
    'atm' => 'ATM / Chuyển khoản',
    'international' => 'Thanh toán quốc tế',
    default => '--'
} }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái thanh toán</th>
                        <td>
                            <span class="badge {{ $booking->payment_status_badge_class }}">
                                {{ $booking->payment_status_label }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Trạng thái đơn</th>
                        <td>
                            <span class="badge {{ $booking->booking_status_badge_class }}">
                                {{ $booking->booking_status_label }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Hạn thanh toán</th>
                        <td>{{ $booking->payment_expired_at ? $booking->payment_expired_at->format('d/m/Y H:i') : '--' }}</td>
                    </tr>
                    <tr>
                        <th>Đã thanh toán lúc</th>
                        <td>{{ $booking->paid_at ? $booking->paid_at->format('d/m/Y H:i') : '--' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td>{{ $booking->created_at ? $booking->created_at->format('d/m/Y H:i') : '--' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <strong>Thay đổi thông tin người mua</strong>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.combo-bookings.update-buyer-info', $booking->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Họ và tên</label>
                            <input
                                type="text"
                                name="contact_name"
                                class="form-control"
                                value="{{ old('contact_name', $booking->contact_name) }}"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Số điện thoại</label>
                            <input
                                type="text"
                                name="contact_phone"
                                class="form-control"
                                value="{{ old('contact_phone', $booking->contact_phone) }}"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input
                                type="email"
                                name="contact_email"
                                class="form-control"
                                value="{{ old('contact_email', $booking->contact_email) }}">
                        </div>

                        <div class="col-md-6 mb-3 d-flex align-items-center">
                            <div class="form-check mt-4">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="invoice_required"
                                    value="1"
                                    id="invoice_required"
                                    {{ old('invoice_required', $booking->invoice_required) ? 'checked' : '' }}>
                                <label class="form-check-label" for="invoice_required">
                                    Xuất hóa đơn
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Mã số thuế</label>
                            <input
                                type="text"
                                name="invoice_tax"
                                class="form-control"
                                value="{{ old('invoice_tax', $booking->invoice_tax) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Tên công ty</label>
                            <input
                                type="text"
                                name="invoice_company"
                                class="form-control"
                                value="{{ old('invoice_company', $booking->invoice_company) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Địa chỉ công ty</label>
                            <input
                                type="text"
                                name="invoice_address"
                                class="form-control"
                                value="{{ old('invoice_address', $booking->invoice_address) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email hóa đơn</label>
                            <input
                                type="email"
                                name="invoice_email"
                                class="form-control"
                                value="{{ old('invoice_email', $booking->invoice_email) }}">
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save mr-1"></i> Lưu thông tin người mua
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <strong>Lịch sử thay đổi</strong>
            </div>
            <div class="card-body">
                @if ($booking->histories->isEmpty())
                <p class="mb-0 text-muted">Chưa có lịch sử thay đổi.</p>
                @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 180px;">Thời gian</th>
                                <th style="width: 160px;">Người sửa</th>
                                <th>Ghi chú</th>
                                <th style="width: 100px;" class="text-center">Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($booking->histories as $history)
                            <tr>
                                <td>
                                    {{ $history->created_at ? $history->created_at->format('d/m/Y H:i:s') : '--' }}
                                </td>
                                <td>{{ $history->changed_by_name ?: '--' }}</td>
                                <td>{{ $history->note ?: '--' }}</td>
                                <td class="text-center">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-primary js-history-detail"
                                        data-toggle="modal"
                                        data-target="#historyDetailModal"
                                        data-time="{{ $history->created_at ? $history->created_at->format('d/m/Y H:i:s') : '--' }}"
                                        data-user="{{ $history->changed_by_name ?: '--' }}"
                                        data-action="{{ $history->action ?: '--' }}"
                                        data-field="{{ $history->field_name ?: '--' }}"
                                        data-old="{{ $history->old_value !== null && $history->old_value !== '' ? $history->old_value : '--' }}"
                                        data-new="{{ $history->new_value !== null && $history->new_value !== '' ? $history->new_value : '--' }}"
                                        data-note="{{ $history->note ?: '--' }}">
                                        Xem
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        <div class="modal fade" id="historyDetailModal" tabindex="-1" aria-labelledby="historyDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chi tiết lịch sử thay đổi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-0">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <th style="width: 220px;">Thời gian</th>
                                    <td id="modal-history-time">--</td>
                                </tr>
                                <tr>
                                    <th>Người sửa</th>
                                    <td id="modal-history-user">--</td>
                                </tr>
                                <tr>
                                    <th>Hành động</th>
                                    <td id="modal-history-action">--</td>
                                </tr>
                                <tr>
                                    <th>Trường</th>
                                    <td id="modal-history-field">--</td>
                                </tr>
                                <tr>
                                    <th>Giá trị cũ</th>
                                    <td id="modal-history-old">--</td>
                                </tr>
                                <tr>
                                    <th>Giá trị mới</th>
                                    <td id="modal-history-new">--</td>
                                </tr>
                                <tr>
                                    <th>Ghi chú</th>
                                    <td id="modal-history-note">--</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const detailButtons = document.querySelectorAll('.js-history-detail');

                detailButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        document.getElementById('modal-history-time').textContent = this.getAttribute('data-time') || '--';
                        document.getElementById('modal-history-user').textContent = this.getAttribute('data-user') || '--';
                        document.getElementById('modal-history-action').textContent = this.getAttribute('data-action') || '--';
                        document.getElementById('modal-history-field').textContent = this.getAttribute('data-field') || '--';
                        document.getElementById('modal-history-old').textContent = this.getAttribute('data-old') || '--';
                        document.getElementById('modal-history-new').textContent = this.getAttribute('data-new') || '--';
                        document.getElementById('modal-history-note').textContent = this.getAttribute('data-note') || '--';
                    });
                });
            });
        </script>
        @endpush
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <strong>Cập nhật trạng thái đơn hàng</strong>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.combo-bookings.update-status', $booking->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Trạng thái thanh toán</label>
                        <select name="payment_status" class="form-control">
                            <option value="pending" {{ $booking->payment_status === 'pending' ? 'selected' : '' }}>Hoãn thanh toán</option>
                            <option value="paid" {{ $booking->payment_status === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="expired" {{ $booking->payment_status === 'expired' ? 'selected' : '' }}>Hết hạn</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Trạng thái đơn hàng</label>
                        <select name="booking_status" class="form-control">
                            <option value="draft" {{ $booking->booking_status === 'draft' ? 'selected' : '' }}>Nháp</option>
                            <option value="pending_payment" {{ $booking->booking_status === 'pending_payment' ? 'selected' : '' }}>Hoãn thanh toán</option>
                            <option value="confirmed" {{ $booking->booking_status === 'confirmed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ $booking->booking_status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            <option value="expired" {{ $booking->booking_status === 'expired' ? 'selected' : '' }}>Hết hạn</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save mr-1"></i> Lưu trạng thái
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <strong>Thông tin hành khách</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th>Người lớn</th>
                        <td>{{ $booking->adult }}</td>
                    </tr>
                    <tr>
                        <th>Trẻ em</th>
                        <td>{{ $booking->child }}</td>
                    </tr>
                    <tr>
                        <th>Em bé</th>
                        <td>{{ $booking->infant }}</td>
                    </tr>
                    <tr>
                        <th>Tổng hành khách</th>
                        <td>{{ $booking->total_passengers }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <strong>Thông tin giá</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th>Giá người lớn</th>
                        <td>{{ number_format($booking->adult_final_price, 0, ',', '.') }} đ</td>
                    </tr>
                    <tr>
                        <th>Giá trẻ em</th>
                        <td>{{ number_format($booking->child_final_price, 0, ',', '.') }} đ</td>
                    </tr>
                    <tr>
                        <th>Giá em bé</th>
                        <td>{{ number_format($booking->infant_final_price, 0, ',', '.') }} đ</td>
                    </tr>
                    <tr>
                        <th>Sale</th>
                        <td>{{ $booking->sale_percent }}%</td>
                    </tr>
                    <tr>
                        <th>Mã giảm giá</th>
                        <td>{{ $booking->discount_code ?: '--' }}</td>
                    </tr>
                    <tr>
                        <th>Giảm mã</th>
                        <td>{{ $booking->discount_code_percent }}%</td>
                    </tr>
                    <tr>
                        <th>Số tiền giảm</th>
                        <td>{{ number_format($booking->discount_code_amount, 0, ',', '.') }} đ</td>
                    </tr>
                    <tr>
                        <th>Tổng tiền gốc</th>
                        <td>{{ number_format($booking->total_amount, 0, ',', '.') }} đ</td>
                    </tr>
                    <tr class="bg-light">
                        <th>Tổng thanh toán</th>
                        <td><strong>{{ number_format($booking->final_amount, 0, ',', '.') }} đ</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <strong>Thông tin hóa đơn</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th>Xuất hóa đơn</th>
                        <td>{{ $booking->invoice_required ? 'Có' : 'Không' }}</td>
                    </tr>
                    <tr>
                        <th>Mã số thuế</th>
                        <td>{{ $booking->invoice_tax ?: '--' }}</td>
                    </tr>
                    <tr>
                        <th>Công ty</th>
                        <td>{{ $booking->invoice_company ?: '--' }}</td>
                    </tr>
                    <tr>
                        <th>Địa chỉ</th>
                        <td>{{ $booking->invoice_address ?: '--' }}</td>
                    </tr>
                    <tr>
                        <th>Email hóa đơn</th>
                        <td>{{ $booking->invoice_email ?: '--' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection