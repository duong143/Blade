@extends('admin.layout')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Danh sách đợt khởi hành</h3>

        <a href="{{ route('admin.combo-departures.create', ['combo_id' => request('combo_id')]) }}"
            class="btn btn-primary btn-sm">
            + Thêm đợt
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="GET" action="{{ route('admin.combo-departures.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <select name="combo_id" class="form-control">
                        <option value="">-- Tất cả combo --</option>
                        @foreach($combos as $c)
                        <option value="{{ $c->id }}" @selected((string)request('combo_id')===(string)$c->id)>
                            #{{ $c->id }} - {{ $c->title }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-secondary">Lọc</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width:80px">#</th>
                    <th>Combo</th>
                    <th style="width:140px">Start</th>
                    <th style="width:140px">End</th>
                    <th style="width:120px">Capacity</th>
                    <th style="width:100px">Sold</th>
                    <th style="width:120px">Remaining</th>
                    <th style="width:120px">Status</th>
                    <th style="width:170px">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departures as $d)
                <tr>
                    <td>{{ $d->id }}</td>
                    <td>#{{ $d->combo?->id }} - {{ $d->combo?->title }}</td>
                    <td>{{ optional($d->start_date)->format('Y-m-d') }}</td>
                    <td>{{ optional($d->end_date)->format('Y-m-d') }}</td>
                    <td>{{ $d->capacity }}</td>
                    <td>{{ $d->sold }}</td>
                    <td>{{ $d->slots_remaining }}</td>
                    <td>
                        @if($d->status)
                        <span class="badge bg-success">ON</span>
                        @else
                        <span class="badge bg-secondary">OFF</span>
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-sm btn-warning" href="{{ route('admin.combo-departures.edit', $d->id) }}">Sửa</a>

                        <form action="{{ route('admin.combo-departures.destroy', $d->id) }}"
                            method="POST" class="d-inline"
                            onsubmit="return confirm('Xóa đợt này?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Chưa có đợt khởi hành</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $departures->links() }}
    </div>
</div>
@endsection