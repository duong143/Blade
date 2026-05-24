@extends('admin.layout')

@section('title', 'Chi tiết đơn vé máy bay')

@section('content')
<div class="row">
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Chi tiết đơn vé máy bay #{{ $booking->id }}</h3>
            </div>

            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <table class="table table-bordered">
                    <tr>
                        <th width="220">Mã đơn</th>
                        <td>{{ $booking->booking_code ?: 'Chưa có mã' }}</td>
                    </tr>
                    <tr>
                        <th>Khách hàng</th>
                        <td>{{ $booking->contact_name }}</td>
                    </tr>
                    <tr>
                        <th>Số điện thoại</th>
                        <td>{{ $booking->contact_phone }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $booking->contact_email ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Chuyến bay</th>
                        <td>
                            <strong>{{ $booking->flight->flight_number ?? '' }}</strong>
                            - {{ $booking->flight->airline->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Tuyến bay</th>
                        <td>
                            {{ $booking->flight->departureAirport->city ?? '' }}
                            ({{ $booking->flight->departureAirport->code ?? '' }})
                            →
                            {{ $booking->flight->arrivalAirport->city ?? '' }}
                            ({{ $booking->flight->arrivalAirport->code ?? '' }})
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày giờ đi</th>
                        <td>
                            {{ optional($booking->flight->departure_date)->format('d/m/Y') }}
                            {{ substr((string)$booking->flight->departure_time, 0, 5) }}
                        </td>
                    </tr>
                    <tr>
                        <th>Hành khách</th>
                        <td>
                            Người lớn: {{ $booking->adult }} |
                            Trẻ em: {{ $booking->child }} |
                            Em bé: {{ $booking->infant }} |
                            <strong>Tổng: {{ $booking->total_passengers }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <th>Giá người lớn</th>
                        <td>{{ number_format($booking->adult_price, 0, ',', '.') }} đ</td>
                    </tr>
                    <tr>
                        <th>Giá trẻ em</th>
                        <td>{{ number_format($booking->child_price, 0, ',', '.') }} đ</td>
                    </tr>
                    <tr>
                        <th>Giá em bé</th>
                        <td>{{ number_format($booking->infant_price, 0, ',', '.') }} đ</td>
                    </tr>
                    <tr>
                        <th>Thuế & phí</th>
                        <td>{{ number_format($booking->tax_fee, 0, ',', '.') }} đ</td>
                    </tr>
                    <tr>
                        <th>Tổng tiền</th>
                        <td>{{ number_format($booking->total_amount, 0, ',', '.') }} đ</td>
                    </tr>
                    <tr>
                        <th>Thành tiền</th>
                        <td><strong>{{ number_format($booking->final_amount, 0, ',', '.') }} đ</strong></td>
                    </tr>
                    <tr>
                        <th>Phương thức thanh toán</th>
                        <td>{{ $booking->payment_method ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái thanh toán</th>
                        <td>{{ $booking->payment_status }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái đơn</th>
                        <td>{{ $booking->booking_status }}</td>
                    </tr>
                    <tr>
                        <th>Hạn thanh toán</th>
                        <td>{{ $booking->payment_expired_at?->format('d/m/Y H:i') ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Đã thanh toán lúc</th>
                        <td>{{ $booking->paid_at?->format('d/m/Y H:i') ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Ghi chú admin</th>
                        <td>{{ $booking->admin_note ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td>{{ $booking->created_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>

                <a href="{{ route('admin.flight-bookings.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Cập nhật trạng thái đơn</h3>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin.flight-bookings.update-status', $booking->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Trạng thái thanh toán</label>
                        <select name="payment_status" class="form-control">
                            <option value="pending" @selected(old('payment_status', $booking->payment_status) === 'pending')>pending</option>
                            <option value="paid" @selected(old('payment_status', $booking->payment_status) === 'paid')>paid</option>
                            <option value="expired" @selected(old('payment_status', $booking->payment_status) === 'expired')>expired</option>
                            <option value="cancelled" @selected(old('payment_status', $booking->payment_status) === 'cancelled')>cancelled</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái đơn</label>
                        <select name="booking_status" class="form-control">
                            <option value="draft" @selected(old('booking_status', $booking->booking_status) === 'draft')>draft</option>
                            <option value="pending_payment" @selected(old('booking_status', $booking->booking_status) === 'pending_payment')>pending_payment</option>
                            <option value="confirmed" @selected(old('booking_status', $booking->booking_status) === 'confirmed')>confirmed</option>
                            <option value="expired" @selected(old('booking_status', $booking->booking_status) === 'expired')>expired</option>
                            <option value="cancelled" @selected(old('booking_status', $booking->booking_status) === 'cancelled')>cancelled</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phương thức thanh toán</label>
                        <input
                            type="text"
                            name="payment_method"
                            class="form-control"
                            value="{{ old('payment_method', $booking->payment_method) }}"
                            placeholder="VD: atm, international, vnpt">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ghi chú admin</label>
                        <textarea name="admin_note" class="form-control" rows="4">{{ old('admin_note', $booking->admin_note) }}</textarea>
                    </div>

                    <button class="btn btn-primary">Cập nhật trạng thái</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cập nhật thông tin người mua</h3>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin.flight-bookings.update-buyer-info', $booking->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Họ và tên</label>
                        <input
                            type="text"
                            name="contact_name"
                            class="form-control"
                            value="{{ old('contact_name', $booking->contact_name) }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input
                            type="text"
                            name="contact_phone"
                            class="form-control"
                            value="{{ old('contact_phone', $booking->contact_phone) }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input
                            type="email"
                            name="contact_email"
                            class="form-control"
                            value="{{ old('contact_email', $booking->contact_email) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ghi chú admin</label>
                        <textarea name="admin_note" class="form-control" rows="4">{{ old('admin_note', $booking->admin_note) }}</textarea>
                    </div>

                    <button class="btn btn-success">Cập nhật thông tin người mua</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection