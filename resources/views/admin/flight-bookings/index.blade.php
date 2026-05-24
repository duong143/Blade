@extends('admin.layout')

@section('title', 'Đơn vé máy bay')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách đơn vé máy bay</h3>
    </div>

    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Từ khóa</label>
                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        value="{{ request('keyword') }}"
                        placeholder="Mã đơn, khách hàng, SĐT, email">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Trạng thái thanh toán</label>
                    <select name="payment_status" class="form-control">
                        <option value="">-- Tất cả --</option>
                        <option value="pending" @selected(request('payment_status')==='pending' )>Chờ thanh toán</option>
                        <option value="paid" @selected(request('payment_status')==='paid' )>Đã thanh toán</option>
                        <option value="expired" @selected(request('payment_status')==='expired' )>Hết hạn</option>
                        <option value="cancelled" @selected(request('payment_status')==='cancelled' )>Đã hủy</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Trạng thái đơn</label>
                    <select name="booking_status" class="form-control">
                        <option value="">-- Tất cả --</option>
                        <option value="draft" @selected(request('booking_status')==='draft' )>Nháp</option>
                        <option value="pending_payment" @selected(request('booking_status')==='pending_payment' )>Chờ thanh toán</option>
                        <option value="confirmed" @selected(request('booking_status')==='confirmed' )>Đã xác nhận</option>
                        <option value="expired" @selected(request('booking_status')==='expired' )>Hết hạn</option>
                        <option value="cancelled" @selected(request('booking_status')==='cancelled' )>Đã hủy</option>
                    </select>
                </div>
            </div>

            <button class="btn btn-primary btn-sm">Lọc</button>
            <a href="{{ route('admin.flight-bookings.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Chuyến bay</th>
                        <th>Hành khách</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái đơn</th>
                        <th>Ngày tạo</th>
                        <th width="110">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>

                        <td>
                            <strong>{{ $booking->booking_code ?: 'Chưa có mã' }}</strong>
                        </td>

                        <td>
                            <strong>{{ $booking->contact_name }}</strong><br>
                            <small>{{ $booking->contact_phone }}</small><br>
                            @if($booking->contact_email)
                            <small>{{ $booking->contact_email }}</small>
                            @endif
                        </td>

                        <td>
                            <strong>{{ $booking->flight->flight_number ?? '' }}</strong><br>
                            <small>{{ $booking->flight->airline->name ?? '' }}</small><br>
                            <small>
                                {{ $booking->flight->departureAirport->city ?? '' }}
                                ({{ $booking->flight->departureAirport->code ?? '' }})
                                →
                                {{ $booking->flight->arrivalAirport->city ?? '' }}
                                ({{ $booking->flight->arrivalAirport->code ?? '' }})
                            </small>
                        </td>

                        <td>
                            NL: {{ $booking->adult }}<br>
                            TE: {{ $booking->child }}<br>
                            EB: {{ $booking->infant }}<br>
                            <strong>Tổng: {{ $booking->total_passengers }}</strong>
                        </td>

                        <td>
                            <strong>{{ number_format($booking->final_amount, 0, ',', '.') }} đ</strong>
                        </td>

                        <td>
                            @php
                            $paymentBadgeClass = match($booking->payment_status) {
                            'paid' => 'success',
                            'expired' => 'secondary',
                            'cancelled' => 'danger',
                            default => 'warning',
                            };
                            @endphp
                            <span class="badge badge-{{ $paymentBadgeClass }}">
                                {{ $booking->payment_status }}
                            </span>
                            @if($booking->payment_method)
                            <br><small>{{ $booking->payment_method }}</small>
                            @endif
                        </td>

                        <td>
                            @php
                            $bookingBadgeClass = match($booking->booking_status) {
                            'confirmed' => 'success',
                            'expired' => 'secondary',
                            'cancelled' => 'danger',
                            'draft' => 'dark',
                            default => 'warning',
                            };
                            @endphp
                            <span class="badge badge-{{ $bookingBadgeClass }}">
                                {{ $booking->booking_status }}
                            </span>
                        </td>

                        <td>
                            {{ $booking->created_at?->format('d/m/Y') }}<br>
                            <small>{{ $booking->created_at?->format('H:i') }}</small>
                        </td>

                        <td>
                            <a href="{{ route('admin.flight-bookings.show', $booking->id) }}" class="btn btn-sm btn-primary">
                                Xem
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">Chưa có đơn vé máy bay nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $bookings->links() }}
    </div>
</div>
@endsection