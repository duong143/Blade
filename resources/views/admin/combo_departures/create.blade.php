@extends('admin.layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm đợt khởi hành</h3>
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

        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.combo-departures.store') }}">
            @csrf

            <div class="form-group">
                <label>Combo</label>
                <select name="combo_id" class="form-control" required>
                    <option value="">-- Chọn combo --</option>
                    @foreach($combos as $c)
                    <option value="{{ $c->id }}" @selected((string)old('combo_id', $comboId ?? '') === (string)$c->id)>
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
                            value="{{ old('start_date') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>End date</label>
                        <input type="date" name="end_date" class="form-control"
                            value="{{ old('end_date') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Capacity</label>
                        <input type="number" name="capacity" class="form-control"
                            value="{{ old('capacity', 35) }}" min="1" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Sold</label>
                        <input type="number" name="sold" class="form-control"
                            value="{{ old('sold', 0) }}" min="0">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1" @selected(old('status', 1)==1)>ON</option>
                            <option value="0" @selected(old('status', 1)==0)>OFF</option>
                        </select>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.combo-departures.index', ['combo_id' => old('combo_id', $comboId ?? '')]) }}"
                class="btn btn-secondary">
                Quay lại
            </a>
        </form>
    </div>
</div>
@endsection