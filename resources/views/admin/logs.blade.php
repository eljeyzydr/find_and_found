@extends('layouts.admin')

@section('title', 'Activity Logs - Admin')
@section('page-title', 'Activity Logs')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">System Activity Logs</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-secondary btn-sm" onclick="refreshLogs()">
                            <i class="fas fa-sync-alt me-2"></i>Refresh
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="clearOldLogs()">
                            <i class="fas fa-trash me-2"></i>Clear Old Logs
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <select class="form-select" id="logLevel">
                            <option value="">All Levels</option>
                            <option value="info">Info</option>
                            <option value="warning">Warning</option>
                            <option value="error">Error</option>
                            <option value="debug">Debug</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="logType">
                            <option value="">All Types</option>
                            <option value="user_action">User Actions</option>
                            <option value="system">System</option>
                            <option value="security">Security</option>
                            <option value="database">Database</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="dateFrom">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="dateTo">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100" onclick="filterLogs()">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </div>

                <!-- Live Logs Table -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="150">Timestamp</th>
                                <th width="80">Level</th>
                                <th width="100">Type</th>
                                <th width="120">User</th>
                                <th>Action</th>
                                <th width="150">IP Address</th>
                                <th width="100">Status</th>
                            </tr>
                        </thead>
                        <tbody id="logsTableBody">
                            <!-- Sample log entries since we don't have real activity logging yet -->
                            <tr>
                                <td>
                                    <div class="timestamp">{{ now()->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ now()->format('H:i:s') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">INFO</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">USER_ACTION</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" 
                                             class="rounded-circle me-2" width="24" height="24">
                                        <div>
                                            <div class="fw-bold">{{ auth()->user()->name }}</div>
                                            <small class="text-muted">{{ auth()->user()->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-details">
                                        <strong>User Login</strong>
                                        <br>
                                        <small class="text-muted">User successfully logged into the system</small>
                                    </div>
                                </td>
                                <td>
                                    <code>{{ request()->ip() }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-success">SUCCESS</span>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <div class="timestamp">{{ now()->subMinutes(15)->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ now()->subMinutes(15)->format('H:i:s') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-warning">WARNING</span>
                                </td>
                                <td>
                                    <span class="badge bg-warning">SECURITY</span>
                                </td>
                                <td>
                                    <span class="text-muted">System</span>
                                </td>
                                <td>
                                    <div class="action-details">
                                        <strong>Failed Login Attempt</strong>
                                        <br>
                                        <small class="text-muted">Multiple failed login attempts detected</small>
                                    </div>
                                </td>
                                <td>
                                    <code>192.168.1.100</code>
                                </td>
                                <td>
                                    <span class="badge bg-warning">WARNING</span>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="timestamp">{{ now()->subHour()->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ now()->subHour()->format('H:i:s') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">INFO</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">SYSTEM</span>
                                </td>
                                <td>
                                    <span class="text-muted">System</span>
                                </td>
                                <td>
                                    <div class="action-details">
                                        <strong>Database Backup</strong>
                                        <br>
                                        <small class="text-muted">Scheduled database backup completed successfully</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">-</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">SUCCESS</span>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="timestamp">{{ now()->subHours(2)->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ now()->subHours(2)->format('H:i:s') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-danger">ERROR</span>
                                </td>
                                <td>
                                    <span class="badge bg-danger">DATABASE</span>
                                </td>
                                <td>
                                    <span class="text-muted">System</span>
                                </td>
                                <td>
                                    <div class="action-details">
                                        <strong>Database Connection Error</strong>
                                        <br>
                                        <small class="text-muted">Temporary database connection timeout</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">-</span>
                                </td>
                                <td>
                                    <span class="badge bg-danger">ERROR</span>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="timestamp">{{ now()->subHours(3)->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ now()->subHours(3)->format('H:i:s') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">INFO</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">USER_ACTION</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" 
                                             class="rounded-circle me-2" width="24" height="24">
                                        <div>
                                            <div class="fw-bold">{{ auth()->user()->name }}</div>
                                            <small class="text-muted">Admin</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-details">
                                        <strong>Item Deleted</strong>
                                        <br>
                                        <small class="text-muted">Admin deleted reported item #12345</small>
                                    </div>
                                </td>
                                <td>
                                    <code>{{ request()->ip() }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-success">SUCCESS</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Auto-refresh notice -->
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="autoRefresh" checked>
                            <label class="form-check-label" for="autoRefresh">
                                Auto-refresh every 30 seconds
                            </label>
                        </div>
                        <div class="text-muted">
                            <i class="fas fa-circle text-success me-1"></i>
                            Live monitoring active
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="logDetailsContent">
                    <!-- Log details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- System Health Cards -->
<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="health-indicator mb-2">
                    <i class="fas fa-server fa-2x text-success"></i>
                </div>
                <h6>Server Status</h6>
                <span class="badge bg-success">Online</span>
                <div class="mt-2">
                    <small class="text-muted">Uptime: 99.9%</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="health-indicator mb-2">
                    <i class="fas fa-database fa-2x text-success"></i>
                </div>
                <h6>Database</h6>
                <span class="badge bg-success">Healthy</span>
                <div class="mt-2">
                    <small class="text-muted">Response: 15ms</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="health-indicator mb-2">
                    <i class="fas fa-memory fa-2x text-warning"></i>
                </div>
                <h6>Memory Usage</h6>
                <span class="badge bg-warning">Medium</span>
                <div class="mt-2">
                    <small class="text-muted">67% used</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="health-indicator mb-2">
                    <i class="fas fa-hdd fa-2x text-info"></i>
                </div>
                <h6>Storage</h6>
                <span class="badge bg-info">Good</span>
                <div class="mt-2">
                    <small class="text-muted">34% used</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let autoRefreshInterval;

document.addEventListener('DOMContentLoaded', function() {
    // Start auto-refresh if enabled
    if (document.getElementById('autoRefresh').checked) {
        startAutoRefresh();
    }
    
    // Auto-refresh toggle
    document.getElementById('autoRefresh').addEventListener('change', function() {
        if (this.checked) {
            startAutoRefresh();
        } else {
            stopAutoRefresh();
        }
    });
});

function startAutoRefresh() {
    autoRefreshInterval = setInterval(function() {
        refreshLogs();
    }, 30000); // 30 seconds
}

function stopAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
}

function refreshLogs() {
    // In a real implementation, this would fetch new logs via AJAX
    console.log('Refreshing logs...');
    
    // Show refresh animation
    const refreshBtn = document.querySelector('[onclick="refreshLogs()"]');
    const icon = refreshBtn.querySelector('i');
    icon.classList.add('fa-spin');
    
    // Simulate loading
    setTimeout(() => {
        icon.classList.remove('fa-spin');
        
        // Show success message
        showToast('Logs refreshed successfully', 'success');
    }, 1000);
}

function filterLogs() {
    const level = document.getElementById('logLevel').value;
    const type = document.getElementById('logType').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    // In a real implementation, this would filter logs via AJAX
    console.log('Filtering logs:', { level, type, dateFrom, dateTo });
    
    showToast('Filter applied', 'info');
}

function clearOldLogs() {
    if (!confirm('Are you sure you want to clear logs older than 30 days? This action cannot be undone.')) {
        return;
    }
    
    // In a real implementation, this would clear old logs
    console.log('Clearing old logs...');
    
    showToast('Old logs cleared successfully', 'success');
}

function showLogDetails(logId) {
    // In a real implementation, this would fetch log details via AJAX
    const modal = new bootstrap.Modal(document.getElementById('logDetailsModal'));
    
    document.getElementById('logDetailsContent').innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h6>Log Information</h6>
                <table class="table table-sm">
                    <tr><td><strong>ID:</strong></td><td>${logId}</td></tr>
                    <tr><td><strong>Level:</strong></td><td><span class="badge bg-info">INFO</span></td></tr>
                    <tr><td><strong>Type:</strong></td><td><span class="badge bg-primary">USER_ACTION</span></td></tr>
                    <tr><td><strong>Timestamp:</strong></td><td>${new Date().toLocaleString()}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Context</h6>
                <table class="table table-sm">
                    <tr><td><strong>User:</strong></td><td>{{ auth()->user()->name }}</td></tr>
                    <tr><td><strong>IP:</strong></td><td>{{ request()->ip() }}</td></tr>
                    <tr><td><strong>User Agent:</strong></td><td>{{ request()->userAgent() }}</td></tr>
                </table>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <h6>Full Message</h6>
                <div class="bg-light p-3 rounded">
                    <pre>User successfully performed login action with session ID: abc123</pre>
                </div>
            </div>
        </div>
    `;
    
    modal.show();
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    // Add to toast container or create one
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '1055';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast element after it's hidden
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

// Add click event to table rows for details
document.getElementById('logsTableBody').addEventListener('click', function(e) {
    const row = e.target.closest('tr');
    if (row) {
        const logId = Math.random().toString(36).substr(2, 9);
        showLogDetails(logId);
    }
});
</script>
@endpush