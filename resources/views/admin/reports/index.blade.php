@extends('layouts.admin')

@section('title', 'Reports Management - Admin Panel')
@section('page-title', 'Reports Management')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ $reports->total() }}</h3>
                        <p class="mb-0 opacity-75">Total Reports</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-flag fa-2x opacity-75"></i>
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
                        <h3 class="mb-1">{{ $reports->where('status', 'pending')->count() }}</h3>
                        <p class="mb-0 opacity-75">Pending Review</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x opacity-75"></i>
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
                        <h3 class="mb-1">{{ $reports->where('status', 'resolved')->count() }}</h3>
                        <p class="mb-0 opacity-75">Resolved</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
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
                        <h3 class="mb-1">{{ $reports->where('status', 'rejected')->count() }}</h3>
                        <p class="mb-0 opacity-75">Rejected</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                       placeholder="Search reports...">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="reason">
                    <option value="">All Reasons</option>
                    @foreach($reasons as $key => $label)
                        <option value="{{ $key }}" {{ request('reason') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="sort">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="priority" {{ request('sort') == 'priority' ? 'selected' : '' }}>Priority</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>Search
                </button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary w-100" title="Clear">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Quick Actions -->
@if($reports->where('status', 'pending')->count() > 0)
    <div class="alert alert-warning mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>{{ $reports->where('status', 'pending')->count() }}</strong> reports need your attention
            </div>
            <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}" class="btn btn-warning btn-sm">
                Review Pending Reports
            </a>
        </div>
    </div>
@endif

<!-- Reports Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Reports List</h5>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success btn-sm" id="bulkResolve" style="display: none;">
                <i class="fas fa-check me-1"></i>Resolve Selected
            </button>
            <button class="btn btn-outline-danger btn-sm" id="bulkReject" style="display: none;">
                <i class="fas fa-times me-1"></i>Reject Selected (<span id="selectedCount">0</span>)
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="30">
                            <input type="checkbox" class="form-check-input" id="selectAll">
                        </th>
                        <th>Report Details</th>
                        <th>Reported Item</th>
                        <th>Reporter</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr class="{{ $report->status === 'pending' ? 'table-warning' : '' }}">
                            <td>
                                <input type="checkbox" class="form-check-input item-checkbox" value="{{ $report->id }}">
                            </td>
                            <td>
                                <div class="report-details">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-{{ $report->status_color }} me-2">{{ $report->status_label }}</span>
                                        <small class="text-muted">ID: #{{ $report->id }}</small>
                                    </div>
                                    @if($report->description)
                                        <p class="mb-0 text-muted small">{{ Str::limit($report->description, 80) }}</p>
                                    @endif
                                    @if($report->admin_note)
                                        <div class="admin-note mt-1">
                                            <small class="text-info">
                                                <i class="fas fa-sticky-note me-1"></i>
                                                {{ Str::limit($report->admin_note, 50) }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $report->item->first_photo }}" alt="{{ $report->item->title }}" 
                                         class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0">{{ Str::limit($report->item->title, 25) }}</h6>
                                        <small class="text-muted">by {{ $report->item->user->name }}</small>
                                        <br><small class="text-muted">{{ $report->item->location->city }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $report->reporter->avatar_url }}" alt="{{ $report->reporter->name }}" 
                                         class="rounded-circle me-2" width="30" height="30">
                                    <div>
                                        <small class="fw-bold">{{ $report->reporter->name }}</small>
                                        <br><small class="text-muted">{{ $report->reporter->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $report->reason_label }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $report->status_color }}">
                                    {{ $report->status_label }}
                                </span>
                                @if($report->reviewed_by)
                                    <br><small class="text-muted">
                                        by {{ $report->reviewer->name }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $report->created_at->format('M d, Y') }}</small>
                                <br><small class="text-muted">{{ $report->created_at->diffForHumans() }}</small>
                                @if($report->reviewed_at)
                                    <br><small class="text-info">
                                        Reviewed {{ $report->reviewed_at->diffForHumans() }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group-vertical" role="group">
                                    <a href="{{ route('admin.reports.show', $report->id) }}" 
                                       class="btn btn-outline-primary btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    @if($report->status === 'pending')
                                        <button class="btn btn-outline-info btn-sm" onclick="quickReview({{ $report->id }})" title="Quick Review">
                                            <i class="fas fa-search"></i> Review
                                        </button>
                                    @endif
                                    @if(in_array($report->status, ['pending', 'reviewed']))
                                        <button class="btn btn-outline-success btn-sm" onclick="quickResolve({{ $report->id }})" title="Quick Resolve">
                                            <i class="fas fa-check"></i> Resolve
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="quickReject({{ $report->id }})" title="Quick Reject">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-flag fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No reports found</h5>
                                <p class="text-muted">
                                    @if(request()->hasAny(['search', 'status', 'reason']))
                                        No reports match your search criteria
                                    @else
                                        No reports have been submitted yet
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($reports->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $reports->firstItem() }}-{{ $reports->lastItem() }} of {{ $reports->total() }} reports
                </div>
                {{ $reports->withQueryString()->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Quick Action Modals -->
<div class="modal fade" id="quickActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickActionForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="admin_note" class="form-label">Admin Note</label>
                        <textarea class="form-control" id="admin_note" name="admin_note" rows="3" 
                                  placeholder="Optional note about this action..."></textarea>
                    </div>
                    <div id="actionSpecificFields"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="confirmAction">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.report-details {
    max-width: 200px;
}

.admin-note {
    background: #e3f2fd;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    border-left: 3px solid #2196f3;
}

.btn-group-vertical .btn {
    margin-bottom: 2px;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    white-space: nowrap;
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group-vertical .btn {
        padding: 0.25rem 0.375rem;
        font-size: 0.7rem;
    }
    
    .report-details {
        max-width: 150px;
    }
}
</style>
@endpush

@push('scripts')
<script>
let quickActionModal, quickActionForm;

document.addEventListener('DOMContentLoaded', function() {
    quickActionModal = new bootstrap.Modal(document.getElementById('quickActionModal'));
    quickActionForm = document.getElementById('quickActionForm');
});

// Bulk selection
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    toggleBulkActions();
});

document.querySelectorAll('.item-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', toggleBulkActions);
});

function toggleBulkActions() {
    const checked = document.querySelectorAll('.item-checkbox:checked');
    const bulkResolve = document.getElementById('bulkResolve');
    const bulkReject = document.getElementById('bulkReject');
    const selectedCount = document.getElementById('selectedCount');
    
    if (checked.length > 0) {
        bulkResolve.style.display = 'inline-block';
        bulkReject.style.display = 'inline-block';
        selectedCount.textContent = checked.length;
    } else {
        bulkResolve.style.display = 'none';
        bulkReject.style.display = 'none';
    }
}

// Quick Actions
function quickReview(reportId) {
    setupQuickAction(reportId, 'review', 'Review Report', 'btn-info', 'Mark as Reviewed');
}

function quickResolve(reportId) {
    setupQuickAction(reportId, 'resolve', 'Resolve Report', 'btn-success', 'Resolve Report', `
        <div class="mb-3">
            <label for="action" class="form-label">Action Taken</label>
            <select class="form-select" name="action" required>
                <option value="">Select Action</option>
                <option value="block_item">Block Item</option>
                <option value="block_user">Block User</option>
                <option value="warning">Send Warning</option>
                <option value="no_action">No Action Needed</option>
            </select>
        </div>
    `);
}

function quickReject(reportId) {
    setupQuickAction(reportId, 'reject', 'Reject Report', 'btn-danger', 'Reject Report');
}

function setupQuickAction(reportId, action, title, btnClass, btnText, additionalFields = '') {
    document.querySelector('#quickActionModal .modal-title').textContent = title;
    document.getElementById('actionSpecificFields').innerHTML = additionalFields;
    document.getElementById('confirmAction').className = `btn ${btnClass}`;
    document.getElementById('confirmAction').textContent = btnText;
    
    quickActionForm.action = `/admin/reports/${reportId}/${action}`;
    quickActionModal.show();
}

// Bulk Actions
document.getElementById('bulkResolve').addEventListener('click', function() {
    const checked = document.querySelectorAll('.item-checkbox:checked');
    const reportIds = Array.from(checked).map(cb => cb.value);
    
    if (confirm(`Resolve ${reportIds.length} selected reports?`)) {
        // TODO: Implement bulk resolve
        console.log('Bulk resolve reports:', reportIds);
    }
});

document.getElementById('bulkReject').addEventListener('click', function() {
    const checked = document.querySelectorAll('.item-checkbox:checked');
    const reportIds = Array.from(checked).map(cb => cb.value);
    
    if (confirm(`Reject ${reportIds.length} selected reports?`)) {
        // TODO: Implement bulk reject
        console.log('Bulk reject reports:', reportIds);
    }
});

// Auto-submit form on select change
document.querySelectorAll('select').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});

// Priority highlighting
document.querySelectorAll('tr').forEach(row => {
    const status = row.querySelector('.badge')?.textContent.trim();
    if (status === 'Pending') {
        const reportId = row.querySelector('.item-checkbox')?.value;
        const createdDate = new Date(row.cells[6].textContent);
        const daysSinceCreated = (new Date() - createdDate) / (1000 * 60 * 60 * 24);
        
        if (daysSinceCreated > 7) {
            row.style.borderLeft = '4px solid #dc3545';
        } else if (daysSinceCreated > 3) {
            row.style.borderLeft = '4px solid #ffc107';
        }
    }
});

// Real-time updates for pending reports
setInterval(function() {
    const pendingCount = document.querySelectorAll('tr.table-warning').length;
    if (pendingCount > 0) {
        // Update page title with pending count
        document.title = `(${pendingCount}) Reports Management - Admin Panel`;
    }
}, 30000);

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey || e.metaKey) {
        switch(e.key) {
            case 'a':
                e.preventDefault();
                document.getElementById('selectAll').click();
                break;
            case 'r':
                e.preventDefault();
                if (document.querySelectorAll('.item-checkbox:checked').length > 0) {
                    document.getElementById('bulkResolve').click();
                }
                break;
            case 'x':
                e.preventDefault();
                if (document.querySelectorAll('.item-checkbox:checked').length > 0) {
                    document.getElementById('bulkReject').click();
                }
                break;
        }
    }
});

// Toast notifications for successful actions
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 5000);
}

// Handle form submission with feedback
quickActionForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const actionUrl = this.action;
    
    fetch(actionUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            quickActionModal.hide();
            showToast(data.message || 'Action completed successfully');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast(data.message || 'An error occurred', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while processing the request', 'danger');
    });
});
</script>
@endpush