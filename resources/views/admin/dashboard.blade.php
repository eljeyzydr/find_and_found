@extends('layouts.admin')

@section('title', 'Admin Dashboard - Find & Found')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ number_format($stats['total_users']) }}</h3>
                        <p class="mb-0 opacity-75">Total Users</p>
                        <small class="opacity-50">{{ $stats['active_users'] }} aktif</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stats-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ number_format($stats['total_items']) }}</h3>
                        <p class="mb-0 opacity-75">Total Items</p>
                        <small class="opacity-50">{{ $stats['active_items'] }} aktif</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-box fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stats-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ number_format($stats['pending_reports']) }}</h3>
                        <p class="mb-0 opacity-75">Laporan Pending</p>
                        <small class="opacity-50">{{ $stats['total_reports'] }} total</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-flag fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stats-card danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ number_format($stats['resolved_items']) }}</h3>
                        <p class="mb-0 opacity-75">Berhasil Ditemukan</p>
                        <small class="opacity-50">
                            {{ $stats['total_items'] > 0 ? round(($stats['resolved_items'] / $stats['total_items']) * 100, 1) : 0 }}% success rate
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-heart fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- User Registrations Chart -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Registrasi User (30 Hari Terakhir)</h5>
            </div>
            <div class="card-body">
                <canvas id="userRegistrationsChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Items by Status -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Items by Status</h5>
            </div>
            <div class="card-body">
                <canvas id="itemsStatusChart"></canvas>
                <div class="mt-3">
                    @foreach($items_by_status as $status)
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-{{ $status->status === 'lost' ? 'danger' : 'success' }}">
                                {{ ucfirst($status->status) }}
                            </span>
                            <span>{{ $status->count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row mb-4">
    <!-- Recent Users -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>User Terbaru</h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($recent_users->count() > 0)
                    @foreach($recent_users as $user)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle me-3" width="40" height="40">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $user->name }}</h6>
                                <small class="text-muted">{{ $user->email }}</small>
                                <br>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                            <div>
                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                    {{ $user->status }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">Belum ada user baru</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Items -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-box me-2"></i>Item Terbaru</h5>
                <a href="{{ route('admin.items.index') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($recent_items->count() > 0)
                    @foreach($recent_items as $item)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $item->first_photo }}" alt="{{ $item->title }}" class="rounded me-3" width="40" height="40" style="object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ Str::limit($item->title, 25) }}</h6>
                                <small class="text-muted">oleh {{ $item->user->name }}</small>
                                <br>
                                <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                            </div>
                            <div>
                                <span class="badge bg-{{ $item->status === 'lost' ? 'danger' : 'success' }}">
                                    {{ $item->status_label }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">Belum ada item baru</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-flag me-2"></i>Laporan Terbaru
                    @if($stats['pending_reports'] > 0)
                        <span class="badge bg-danger">{{ $stats['pending_reports'] }}</span>
                    @endif
                </h5>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($recent_reports->count() > 0)
                    @foreach($recent_reports as $report)
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class="fas fa-flag text-{{ $report->status_color }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $report->reason_label }}</h6>
                                <small class="text-muted">Item: {{ Str::limit($report->item->title, 20) }}</small>
                                <br>
                                <small class="text-muted">{{ $report->created_at->diffForHumans() }}</small>
                            </div>
                            <div>
                                <span class="badge bg-{{ $report->status_color }}">
                                    {{ $report->status_label }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">Belum ada laporan</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats -->
<div class="row">
    <!-- Categories Stats -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Kategori Populer</h5>
            </div>
            <div class="card-body">
                <canvas id="categoriesChart" height="150"></canvas>
            </div>
        </div>
    </div>

    <!-- Cities Stats -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Kota Teratas</h5>
            </div>
            <div class="card-body">
                @if($items_by_city->count() > 0)
                    @foreach($items_by_city as $city)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $city->city }}</span>
                            <div>
                                <span class="me-2">{{ $city->items_count }} items</span>
                                <div class="progress" style="width: 100px; height: 8px;">
                                    <div class="progress-bar bg-primary" style="width: {{ ($city->items_count / $items_by_city->first()->items_count) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">Belum ada data kota</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-users me-2"></i>Kelola Users
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.items.index') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-box me-2"></i>Kelola Items
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-flag me-2"></i>Review Laporan
                            @if($stats['pending_reports'] > 0)
                                <span class="badge bg-danger">{{ $stats['pending_reports'] }}</span>
                            @endif
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-tags me-2"></i>Kelola Kategori
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// User Registrations Chart
const userCtx = document.getElementById('userRegistrationsChart').getContext('2d');
new Chart(userCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($user_registrations->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('M d'); })) !!},
        datasets: [{
            label: 'Registrasi User',
            data: {!! json_encode($user_registrations->pluck('count')) !!},
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
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
                    stepSize: 1
                }
            }
        }
    }
});

// Items Status Chart
const statusCtx = document.getElementById('itemsStatusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($items_by_status->pluck('status')->map(function($status) { return ucfirst($status); })) !!},
        datasets: [{
            data: {!! json_encode($items_by_status->pluck('count')) !!},
            backgroundColor: ['#ef4444', '#10b981'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Categories Chart
const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
new Chart(categoriesCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($items_by_category->pluck('name')) !!},
        datasets: [{
            label: 'Items',
            data: {!! json_encode($items_by_category->pluck('items_count')) !!},
            backgroundColor: '#3b82f6',
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
                    stepSize: 1
                }
            },
            x: {
                ticks: {
                    maxRotation: 45
                }
            }
        }
    }
});

// Auto-refresh data every 30 seconds
setInterval(function() {
    // TODO: Implement AJAX refresh for stats
}, 30000);
</script>
@endpush