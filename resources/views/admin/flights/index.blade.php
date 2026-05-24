@extends('admin.layout')

@section('title', 'Danh sách chuyến bay')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Danh sách chuyến bay</h3>
        <a href="{{ route('admin.flights.create') }}" class="btn btn-primary btn-sm">+ Thêm chuyến bay</a>
    </div>

    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Từ khóa</label>
                    <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}"
                        placeholder="Mã chuyến bay, hãng, sân bay...">
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Hãng bay</label>
                    <select name="airline_id" class="form-control">
                        <option value="">-- Tất cả --</option>
                        @foreach($airlines as $airline)
                        <option value="{{ $airline->id }}" @selected((string)request('airline_id')===(string)$airline->id)>
                            {{ $airline->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Sân bay đi</label>
                    <select name="departure_airport_id" class="form-control">
                        <option value="">-- Tất cả --</option>
                        @foreach($airports as $airport)
                        <option value="{{ $airport->id }}" @selected((string)request('departure_airport_id')===(string)$airport->id)>
                            {{ $airport->city }} ({{ $airport->code }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Sân bay đến</label>
                    <select name="arrival_airport_id" class="form-control">
                        <option value="">-- Tất cả --</option>
                        @foreach($airports as $airport)
                        <option value="{{ $airport->id }}" @selected((string)request('arrival_airport_id')===(string)$airport->id)>
                            {{ $airport->city }} ({{ $airport->code }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Ngày bay</label>
                    <input type="date" name="departure_date" class="form-control" value="{{ request('departure_date') }}">
                </div>

                <div class="col-md-1 mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="is_active" class="form-control">
                        <option value="">--</option>
                        <option value="1" @selected(request('is_active')==='1' )>Hiện</option>
                        <option value="0" @selected(request('is_active')==='0' )>Ẩn</option>
                    </select>
                </div>
            </div>

            <button class="btn btn-primary btn-sm">Lọc</button>
            <a href="{{ route('admin.flights.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mã chuyến</th>
                        <th>Hãng bay</th>
                        <th>Tuyến bay</th>
                        <th>Ngày giờ đi</th>
                        <th>Ngày giờ đến</th>
                        <th>Giá NL + Thuế</th>
                        <th>Ghế</th>
                        <th>Trạng thái</th>
                        <th width="210">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($flights as $flight)
                    <tr>
                        <td>{{ $flight->id }}</td>
                        <td>
                            <strong>{{ $flight->flight_number }}</strong><br>
                            <small>{{ $flight->seat_class }}</small>
                        </td>
                        <td>{{ $flight->airline->name ?? '-' }}</td>
                        <td>
                            {{ $flight->departureAirport->city ?? '-' }} ({{ $flight->departureAirport->code ?? '-' }})
                            <br>
                            →
                            <br>
                            {{ $flight->arrivalAirport->city ?? '-' }} ({{ $flight->arrivalAirport->code ?? '-' }})
                        </td>
                        <td>
                            {{ optional($flight->departure_date)->format('d/m/Y') }}<br>
                            {{ substr((string)$flight->departure_time, 0, 5) }}
                        </td>
                        <td>
                            {{ optional($flight->arrival_date)->format('d/m/Y') }}<br>
                            {{ substr((string)$flight->arrival_time, 0, 5) }}
                        </td>
                        <td>{{ number_format(((int)$flight->adult_price + (int)$flight->tax_fee), 0, ',', '.') }} đ</td>
                        <td>{{ $flight->available_seats }}/{{ $flight->total_seats }}</td>
                        <td>
                            @if($flight->is_active)
                            <span class="badge badge-success">Hiển thị</span>
                            @else
                            <span class="badge badge-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.flights.edit', $flight->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                            <form action="{{ route('admin.flights.toggle-status', $flight->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-info">
                                    {{ $flight->is_active ? 'Ẩn' : 'Hiện' }}
                                </button>
                            </form>

                            <form action="{{ route('admin.flights.destroy', $flight->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Xóa chuyến bay này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">Chưa có chuyến bay nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $flights->links() }}
    </div>
</div>
@endsection