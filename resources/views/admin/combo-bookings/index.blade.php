@extends('admin.layout')

@section('title', 'Đơn hàng combo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Đơn hàng combo</h1>

    <a href="{{ route('admin.combo-bookings.export') }}" class="btn btn-success shadow-sm">
        <i class="fas fa-file-excel me-1"></i> Xuất Báo Cáo Excel
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.combo-bookings.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Từ khóa</label>
                    <input
                        type="text"
                        name="keyword"
                        value="{{ $keyword }}"
                        class="form-control"
                        placeholder="Mã đơn, người mua, SĐT, email, mã giảm giá">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Trạng thái thanh toán</label>
                    <select name="payment_status" class="form-control">
                        <option value="">-- Tất cả --</option>
                        <option value="pending" {{ $paymentStatus === 'pending' ? 'selected' : '' }}>Hoãn thanh toán</option>
                        <option value="expired" {{ $paymentStatus === 'expired' ? 'selected' : '' }}>Hết hạn</option>
                        <option value="paid" {{ $paymentStatus === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Trạng thái đơn</label>
                    <select name="booking_status" class="form-control">
                        <option value="">-- Tất cả --</option>
                        <option value="draft" {{ $bookingStatus === 'draft' ? 'selected' : '' }}>Nháp</option>
                        <option value="pending_payment" {{ $bookingStatus === 'pending_payment' ? 'selected' : '' }}>Chờ thanh toán</option>
                        <option value="expired" {{ $bookingStatus === 'expired' ? 'selected' : '' }}>Hết hạn</option>
                        <option value="confirmed" {{ $bookingStatus === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="cancelled" {{ $bookingStatus === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Từ ngày</label>
                    <input
                        type="date"
                        name="created_from"
                        value="{{ $createdFrom }}"
                        class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Đến ngày</label>
                    <input
                        type="date"
                        name="created_to"
                        value="{{ $createdTo }}"
                        class="form-control">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Lọc</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-hover mb-0">
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Người mua</th>
                    <th>Combo</th>
                    <th>Ngày đi</th>
                    <th>Số khách</th>
                    <th>Mã giảm giá</th>
                    <th>Tổng thanh toán</th>
                    <th>TT thanh toán</th>
                    <th>TT đơn</th>
                    <th>Ngày tạo</th>
                    <th width="100">Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->booking_code ?: '#' . $booking->id }}</td>
                    <td>
                        <div><strong>{{ $booking->contact_name }}</strong></div>
                        <div>{{ $booking->contact_phone }}</div>
                        <div>{{ $booking->contact_email ?: '--' }}</div>
                    </td>
                    <td>{{ $booking->combo?->title ?: '--' }}</td>
                    <td>{{ $booking->travel_start_date ? $booking->travel_start_date->format('d/m/Y') : '--' }}</td>
                    <td>{{ $booking->adult }} NL - {{ $booking->child }} TE - {{ $booking->infant }} EB</td>
                    <td>{{ $booking->discount_code ?: '--' }}</td>
                    <td>{{ number_format($booking->final_amount, 0, ',', '.') }} đ</td>
                    <td>
                        <span class="badge {{ $booking->payment_status_badge_class }}">
                            {{ $booking->payment_status_label }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $booking->booking_status_badge_class }}">
                            {{ $booking->booking_status_label }}
                        </span>
                    </td>
                    <td>{{ $booking->created_at ? $booking->created_at->format('d/m/Y H:i') : '--' }}</td>
                    <td>
                        <a href="{{ route('admin.combo-bookings.show', $booking->id) }}" class="btn btn-sm btn-info">
                            Xem
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center text-muted">Chưa có đơn hàng.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $bookings->links() }}
    </div>
</div>
@endsection