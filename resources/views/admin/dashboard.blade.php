@extends('admin.layout')

@section('title', 'Trung tâm Thống kê Hệ thống')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Báo cáo Tổng quan</h1>
        <span class="badge badge-dark p-2"><i class="far fa-calendar-alt"></i> Hôm nay: {{ date('d/m/Y') }}</span>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success shadow">
                <div class="inner">
                    <h3>{{ number_format($totalRevenue, 0, ',', '.') }}<small>đ</small></h3>
                    <p>Doanh thu thực tế</p>
                </div>
                <div class="icon"><i class="fas fa-wallet"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info shadow">
                <div class="inner">
                    <h3>{{ $bookingsToday }}</h3>
                    <p>Đơn hàng mới hôm nay</p>
                </div>
                <div class="icon"><i class="fas fa-shopping-cart"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning shadow">
                <div class="inner">
                    <h3>{{ $contactsToday }}</h3>
                    <p>Yêu cầu tư vấn mới</p>
                </div>
                <div class="icon"><i class="fas fa-headset"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-navy shadow">
                <div class="inner">
                    <h3>{{ $totalUsers }}</h3>
                    <p>Người dùng hệ thống</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
    </div>

    <div class="row mt-2 mb-4">
        <div class="col-lg-7 col-12 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold mb-0 text-success"><i class="fas fa-chart-bar me-2"></i> Phân tích doanh thu bán Combo theo các tháng (Năm {{ date('Y') }})</h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 320px; width: 100%;">
                        <canvas id="revenueBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-12 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold mb-0 text-info"><i class="fas fa-chart-pie me-2"></i> Tỷ trọng Top 5 Combo được mua nhiều nhất</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div style="position: relative; height: 320px; width: 100%;">
                        <canvas id="topComboPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold mb-0 text-primary"><i class="fas fa-comment-dots me-2"></i> Liên hệ mới nhất</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Khách hàng</th>
                                <th>Chủ đề</th>
                                <th>Thời gian</th>
                                <th class="text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentContacts as $contact)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $contact->name }}</div>
                                    <small class="text-muted">{{ $contact->email }}</small>
                                </td>
                                <td>{{ Str::limit($contact->subject, 30) }}</td>
                                <td><small>{{ $contact->created_at->diffForHumans() }}</small></td>
                                <td class="text-center">
                                    <span class="badge {{ $contact->is_read ? 'bg-secondary' : 'bg-danger' }}">
                                        {{ $contact->is_read ? 'Đã xử lý' : 'Chưa xem' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-primary px-4 rounded-pill">Xem tất cả liên hệ</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold mb-0"><i class="fas fa-database me-2"></i> Kho nội dung</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span><i class="fas fa-map-marker-alt text-primary me-2"></i> Điểm đến:</span>
                        <span class="fw-bold fs-5">{{ $totalDestinations }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span><i class="fas fa-camera text-info me-2"></i> Điểm check-in:</span>
                        <span class="fw-bold fs-5">{{ $totalAttractions }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <span><i class="fas fa-pen-nib text-success me-2"></i> Bài viết blog:</span>
                        <span class="fw-bold fs-5">{{ $totalBlogs }}</span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold mb-0 text-success"><i class="fas fa-rss me-2"></i> Blog mới đăng</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($recentBlogs as $blog)
                        <li class="list-group-item d-flex align-items-center gap-3">
                            <img src="{{ asset($blog->image) }}" class="rounded shadow-sm" width="50" height="40" style="object-fit: cover;">
                            <div class="overflow-hidden">
                                <div class="text-truncate fw-bold small">{{ $blog->title }}</div>
                                <small class="text-muted" style="font-size: 10px;">{{ $blog->created_at->format('d/m/Y') }}</small>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div id="chartDataObj"
    data-months="{{ json_encode($months ?? []) }}"
    data-revenues="{{ json_encode($revenues ?? []) }}"
    data-combolabels="{{ json_encode($comboLabels ?? []) }}"
    data-combocounts="{{ json_encode($comboCounts ?? []) }}"
    style="display: none;">
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Đọc dữ liệu từ thẻ DIV ẩn ở trên
    const chartDataEl = document.getElementById('chartDataObj');

    const monthsLabels = JSON.parse(chartDataEl.dataset.months);
    const revenuesData = JSON.parse(chartDataEl.dataset.revenues);
    const comboLabels = JSON.parse(chartDataEl.dataset.combolabels);
    const comboCounts = JSON.parse(chartDataEl.dataset.combocounts);

    // 1. BIỂU ĐỒ CỘT DOANH THU
    const ctxBar = document.getElementById('revenueBarChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: monthsLabels,
            datasets: [{
                label: 'Doanh thu (VND)',
                data: revenuesData,
                backgroundColor: 'rgba(40, 167, 69, 0.85)',
                borderColor: '#28a745',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                }
            }
        }
    });

    // 2. BIỂU ĐỒ TRÒN TOP COMBO
    const ctxPie = document.getElementById('topComboPieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: comboLabels.length > 0 ? comboLabels : ['Chưa có dữ liệu'],
            datasets: [{
                data: comboCounts.length > 0 ? comboCounts : [1],
                backgroundColor: ['#17a2b8', '#007bff', '#ffc107', '#28a745', '#6f42c1'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
</script>

<style>
    .small-box {
        border-radius: 12px;
    }

    .card {
        border-radius: 12px;
    }

    .table thead th {
        border-top: 0;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .list-group-item {
        border-left: 0;
        border-right: 0;
        padding: 12px 20px;
    }
</style>
@endsection