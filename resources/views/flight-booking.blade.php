@extends('layout')

@section('title', 'Đặt vé máy bay')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Đặt vé máy bay</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h4>{{ $flight->airline->name ?? '' }} - {{ $flight->flight_number }}</h4>
            <p class="mb-1">
                {{ $flight->departureAirport->city ?? '' }} ({{ $flight->departureAirport->code ?? '' }})
                →
                {{ $flight->arrivalAirport->city ?? '' }} ({{ $flight->arrivalAirport->code ?? '' }})
            </p>
            <p class="mb-1">Ngày đi: {{ optional($flight->departure_date)->format('d/m/Y') }}</p>
            <p class="mb-1">Giờ đi: {{ substr((string)$flight->departure_time, 0, 5) }}</p>
            <p class="mb-0">Giá tạm tính: <strong>{{ number_format($finalAmount, 0, ',', '.') }} đ</strong></p>
        </div>
    </div>

    <form method="POST" action="{{ route('flight.booking.store') }}">
        @csrf
        <input type="hidden" name="flight_id" value="{{ $flight->id }}">

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Người lớn</label>
                <input type="number" name="adult" class="form-control" value="{{ old('adult', $adult) }}" min="1">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Trẻ em</label>
                <input type="number" name="child" class="form-control" value="{{ old('child', $child) }}" min="0">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Em bé</label>
                <input type="number" name="infant" class="form-control" value="{{ old('infant', $infant) }}" min="0">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Họ và tên</label>
            <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email') }}">
        </div>

        <button class="btn btn-primary">Xác nhận đặt vé</button>
    </form>
</div>
@endsection