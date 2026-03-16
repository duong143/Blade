@extends('admin.layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa đợt khởi hành #{{ $departure->id }}</h3>
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

        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.combo-departures.update', $departure->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Combo</label>
                <select name="combo_id" class="form-control" required>
                    <option value="">-- Chọn combo --</option>
                    @foreach($combos as $c)
                    <option value="{{ $c->id }}" @selected((string)old('combo_id', $departure->combo_id) === (string)$c->id)>
                        #{{ $c->id }} - {{ $c->title }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Start date</label>
                        <input type="date" name="start_date" class="form-control"
                            value="{{ old('start_date', optional($departure->start_date)->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>End date</label>
                        <input type="date" name="end_date" class="form-control"
                            value="{{ old('end_date', optional($departure->end_date)->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Capacity</label>
                        <input type="number" name="capacity" class="form-control"
                            value="{{ old('capacity', $departure->capacity) }}" min="1" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Sold</label>
                        <input type="number" name="sold" class="form-control"
                            value="{{ old('sold', $departure->sold) }}" min="0">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" @selected(old('status', $departure->status)==1)>ON</option>
                            <option value="0" @selected(old('status', $departure->status)==0)>OFF</option>
                        </select>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.combo-departures.index', ['combo_id' => old('combo_id', $departure->combo_id)]) }}"
                class="btn btn-secondary">
                Quay lại
            </a>
        </form>
    </div>
</div>

@php
$priceAdult = $departure->prices->firstWhere('passenger_type', 'adult')?->base_price;
$priceChild = $departure->prices->firstWhere('passenger_type', 'child')?->base_price;
$priceInfant = $departure->prices->firstWhere('passenger_type', 'infant')?->base_price;
@endphp

<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">Giá theo loại khách</h3>
    </div>

    <div class="card-body">
        {{-- Adult --}}
        <form method="POST" action="{{ route('admin.combo-departure-prices.store') }}" class="mb-3">
            @csrf
            <input type="hidden" name="departure_id" value="{{ $departure->id }}">
            <input type="hidden" name="passenger_type" value="adult">

            <div class="row">
                <div class="col-md-4">
                    <label>Adult price</label>
                    <input type="number" name="base_price" class="form-control"
                        value="{{ old('base_price', $priceAdult ?? 0) }}" min="0" required>
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button class="btn btn-primary form-control">Lưu</button>
                </div>
            </div>
        </form>

        {{-- Child --}}
        <form method="POST" action="{{ route('admin.combo-departure-prices.store') }}" class="mb-3">
            @csrf
            <input type="hidden" name="departure_id" value="{{ $departure->id }}">
            <input type="hidden" name="passenger_type" value="child">

            <div class="row">
                <div class="col-md-4">
                    <label>Child price</label>
                    <input type="number" name="base_price" class="form-control"
                        value="{{ old('base_price', $priceChild ?? 0) }}" min="0" required>
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button class="btn btn-primary form-control">Lưu</button>
                </div>
            </div>
        </form>

        {{-- Infant --}}
        <form method="POST" action="{{ route('admin.combo-departure-prices.store') }}">
            @csrf
            <input type="hidden" name="departure_id" value="{{ $departure->id }}">
            <input type="hidden" name="passenger_type" value="infant">

            <div class="row">
                <div class="col-md-4">
                    <label>Infant price</label>
                    <input type="number" name="base_price" class="form-control"
                        value="{{ old('base_price', $priceInfant ?? 0) }}" min="0" required>
                    <small class="text-muted">Infant thường = 0</small>
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button class="btn btn-primary form-control">Lưu</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">Sale theo ngày</h3>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.combo-departure-sales.store') }}" class="mb-3">
            @csrf
            <input type="hidden" name="departure_id" value="{{ $departure->id }}">

            <div class="row">
                <div class="col-md-3">
                    <label>Từ ngày</label>
                    <input type="date" name="start_date" class="form-control"
                        value="{{ old('start_date') }}" required>
                </div>

                <div class="col-md-3">
                    <label>Đến ngày</label>
                    <input type="date" name="end_date" class="form-control"
                        value="{{ old('end_date') }}" required>
                </div>

                <div class="col-md-2">
                    <label>Sale %</label>
                    <input type="number" name="sale_percent" class="form-control"
                        min="0" max="100" value="{{ old('sale_percent', 0) }}" required>
                </div>

                <div class="col-md-3">
                    <label>Nhãn (tuỳ chọn)</label>
                    <input type="text" name="sale_label" class="form-control"
                        value="{{ old('sale_label') }}" placeholder="VD: Flash sale">
                </div>

                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <button class="btn btn-primary form-control">Lưu</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width:60px">#</th>
                    <th>Từ ngày</th>
                    <th>Đến ngày</th>
                    <th>Sale %</th>
                    <th>Nhãn</th>
                    <th style="width:120px">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departure->sales->sortByDesc('start_date') as $s)
                <tr>
                    <td>{{ $s->id }}</td>
                    <td>{{ optional($s->start_date)->format('Y-m-d') }}</td>
                    <td>{{ optional($s->end_date)->format('Y-m-d') }}</td>
                    <td>{{ $s->sale_percent }}%</td>
                    <td>{{ $s->sale_label }}</td>
                    <td>
                        <form action="{{ route('admin.combo-departure-sales.destroy', $s->id) }}"
                            method="POST"
                            onsubmit="return confirm('Xóa sale này?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Chưa có sale theo khoảng ngày.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection