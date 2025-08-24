@extends('layouts.admin')

@section('title', 'Statistics - Admin')
@section('page-title', 'Statistics & Analytics')

@section('content')
<!-- Overview Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ number_format($data['success_rate']['total_items']) }}</h3>
                        <p class="mb-0 opacity-75">Total Items</p>
                        <small class="opacity-50">{{ $data['success_rate']['resolved_items'] }} resolved</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-box fa-2x opacity-75"></i>
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
                        <h3 class="mb-1">{{ $data['success_rate']['success_percentage'] }}%</h3>
                        <p class="mb-0 opacity-75">Success Rate</p>
                        <small class="opacity-50">Items reunited with owners</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x opacity-75"></i>
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
                        <h3 class="mb-1">{{ number_format($data['top_cities']->count()) }}</h3>
                        <p class="mb-0 opacity-75">Cities Covered</p>
                        <small class="opacity-50">Across Indonesia</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-map-marker-alt fa-2x opacity-75"></i>
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
                        <h3 class="mb-1">{{ number_format(\App\Models\User::count()) }}</h3>
                        <p class="mb-0 opacity-75">Total Users</p>
                        <small class="opacity-50">{{ \App\Models\User::active()->count() }} active</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- User Growth Chart -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-area me-2"></i>User Growth</h5>
            </div>
            <div class="card-body">
                <canvas id="userGrowthChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Items by Month Chart -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Items Posted by Month</h5>
            </div>
            <div class="card-body">
                <canvas id="itemsChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Success Rate and Cities -->
<div class="row mb-4">
    <!-- Success Rate Chart -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-heart me-2"></i>Items Resolved by Month</h5>
            </div>
            <div class="card-body">
                <canvas id="resolvedChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Cities -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Top Cities</h5>
            </div>
            <div class="card-body">
                <div class="cities-list">
                    @foreach($data['top_cities']->take(10) as $index => $city)
                        <div class="city-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="city-rank me-3">
                                        <span class="badge bg-primary rounded-circle" style="width: 30px; height: 30px; line-height: 16px;">
                                            {{ $index + 1 }}
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $city->city }}</h6>
                                        <small class="text-muted">{{ $city->items_count }} items posted</small>
                                    </div>
                                </div>
                                <div class="city-progress" style="width: 100px;">
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary" 
                                             style="width: {{ ($city->items_count / $data['top_cities']->first()->items_count) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Statistics -->
<div class="row">
    <!-- Monthly Breakdown -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Monthly Breakdown</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>New Users</th>
                                <th>Items Posted</th>
                                <th>Items Resolved</th>
                                <th>Success Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['users_by_month'] as $month)
                                @php
                                    $itemsThisMonth = $data['items_by_month']->where('year', $month->year)->where('month', $month->month)->first();
                                    $resolvedThisMonth = $data['resolved_items_by_month']->where('year', $month->year)->where('month', $month->month)->first();
                                    $itemsCount = $itemsThisMonth ? $itemsThisMonth->count : 0;
                                    $resolvedCount = $resolvedThisMonth ? $resolvedThisMonth->count : 0;
                                    $successRate = $itemsCount > 0 ? round(($resolvedCount / $itemsCount) * 100, 1) : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::createFromDate($month->year, $month->month, 1)->format('M Y') }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ number_format($month->count) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ number_format($itemsCount) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ number_format($resolvedCount) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress me-2" style="width: 60px; height: 8px;">
                                                <div class="progress-bar bg-success" style="width: {{ $successRate }}%"></div>
                                            </div>
                                            <span class="text-muted">{{ $successRate }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Key Metrics</h5>
            </div>
            <div class="card-body">
                <div class="metrics-list">
                    <div class="metric-item mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Items Lost vs Found</span>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="flex-fill">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-warning" style="width: 60%"></div>
                                </div>
                                <small class="text-muted">60% Lost</small>
                            </div>
                            <div class="flex-fill">
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" style="width: 40%"></div>
                                </div>
                                <small class="text-muted">40% Found</small>
                            </div>
                        </div>
                    </div>

                    <div class="metric-item mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Average Resolution Time</span>
                            <span class="fw-bold">3.5 days</span>
                        </div>
                    </div>

                    <div class="metric-item mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Most Active Category</span>
                            <span class="fw-bold">Electronics</span>
                        </div>
                    </div>

                    <div class="metric-item mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Peak Hour</span>
                            <span class="fw-bold">2:00 PM - 4:00 PM</span>
                        </div>
                    </div>

                    <div class="metric-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">User Retention Rate</span>
                            <span class="fw-bold text-success">78%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// User Growth Chart
const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
new Chart(userGrowthCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($data['users_by_month']->pluck('month')->map(function($month, $index) use ($data) { 
            return \Carbon\Carbon::createFromDate($data['users_by_month'][$index]->year, $month, 1)->format('M Y'); 
        })) !!},
        datasets: [{
            label: 'New Users',
            data: {!! json_encode($data['users_by_month']->pluck('count')) !!},
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
                beginAtZero: true
            }
        }
    }
});

// Items Chart
const itemsCtx = document.getElementById('itemsChart').getContext('2d');
new Chart(itemsCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($data['items_by_month']->pluck('month')->map(function($month, $index) use ($data) { 
            return \Carbon\Carbon::createFromDate($data['items_by_month'][$index]->year, $month, 1)->format('M Y'); 
        })) !!},
        datasets: [{
            label: 'Items Posted',
            data: {!! json_encode($data['items_by_month']->pluck('count')) !!},
            backgroundColor: '#10b981',
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
                beginAtZero: true
            }
        }
    }
});

// Resolved Items Chart
const resolvedCtx = document.getElementById('resolvedChart').getContext('2d');
new Chart(resolvedCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($data['resolved_items_by_month']->pluck('month')->map(function($month, $index) use ($data) { 
            return \Carbon\Carbon::createFromDate($data['resolved_items_by_month'][$index]->year, $month, 1)->format('M Y'); 
        })) !!},
        datasets: [{
            label: 'Items Resolved',
            data: {!! json_encode($data['resolved_items_by_month']->pluck('count')) !!},
            borderColor: '#ef4444',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
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
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush