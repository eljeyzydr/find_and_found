@extends('layouts.admin')

@section('title', 'User Management - Admin Panel')
@section('page-title', 'User Management')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stats-content">
                <h3>{{ $users->total() }}</h3>
                <p>Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card success">
            <div class="stats-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stats-content">
                <h3>{{ $users->where('status', 'active')->count() }}</h3>
                <p>Active Users</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card warning">
            <div class="stats-icon">
                <i class="fas fa-user-slash"></i>
            </div>
            <div class="stats-content">
                <h3>{{ $users->where('status', 'blocked')->count() }}</h3>
                <p>Blocked Users</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="stats-card info">
            <div class="stats-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stats-content">
                <h3>{{ $users->where('role', 'admin')->count() }}</h3>
                <p>Administrators</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Search Users</label>
                <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                       placeholder="Search by name, email, or phone...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Role</label>
                <select class="form-select" name="role">
                    <option value="">All Roles</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Sort By</label>
                <select class="form-select" name="sort">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                    <option value="most_active" {{ request('sort') == 'most_active' ? 'selected' : '' }}>Most Active</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Users List</h5>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm" onclick="exportUsers()">
                <i class="fas fa-download me-1"></i>Export
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        @if($users->count() > 0)
            <!-- Bulk Actions -->
            <div class="bulk-actions" id="bulkActions" style="display: none;">
                <div class="d-flex align-items-center gap-3 p-3 bg-light border-bottom">
                    <span id="selectedCount">0</span> users selected
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="bulkAction('activate')">
                            <i class="fas fa-user-check me-1"></i>Activate
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="bulkAction('block')">
                            <i class="fas fa-user-slash me-1"></i>Block
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="bulkAction('delete')">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>User</th>
                            <th>Contact</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Activity</th>
                            <th>Joined</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                                             class="rounded-circle me-3" width="40" height="40">
                                        <div>
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                            <small class="text-muted">ID: {{ $user->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div><i class="fas fa-envelope me-1"></i>{{ $user->email }}</div>
                                        @if($user->phone)
                                            <div><i class="fas fa-phone me-1"></i>{{ $user->phone }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->role == 'admin' ? 'primary' : 'secondary' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <div><strong>{{ $user->items_count }}</strong> items posted</div>
                                        <div><strong>{{ $user->comments_count }}</strong> comments</div>
                                        <div><strong>{{ $user->reports_count }}</strong> reports</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div>{{ $user->created_at->format('M d, Y') }}</div>
                                        <div class="text-muted">{{ $user->created_at->diffForHumans() }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.show', $user->id) }}" 
                                           class="btn btn-outline-primary btn-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                                           class="btn btn-outline-secondary btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                    data-bs-toggle="dropdown" title="More">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button class="dropdown-item" type="submit">
                                                            <i class="fas fa-{{ $user->status == 'active' ? 'user-slash' : 'user-check' }} me-2"></i>
                                                            {{ $user->status == 'active' ? 'Block' : 'Activate' }}
                                                        </button>
                                                    </form>
                                                </li>
                                                @if($user->id !== auth()->id())
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" 
                                                              class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="dropdown-item text-danger" type="submit">
                                                                <i class="fas fa-trash me-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No users found</h5>
                <p class="text-muted">Try adjusting your search criteria</p>
            </div>
        @endif
    </div>
    
    @if($users->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                </div>
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.stats-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stats-card.success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.stats-card.warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.stats-card.info {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}

.stats-icon {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stats-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.stats-content p {
    margin-bottom: 0;
    opacity: 0.9;
}

.table-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.bulk-actions {
    background: #e3f2fd;
    border-left: 4px solid #2196f3;
}

.user-checkbox:checked {
    background-color: #2563eb;
    border-color: #2563eb;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.4rem;
    }
    
    .stats-card {
        text-align: center;
        flex-direction: column;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Bulk selection
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    toggleBulkActions();
});

document.querySelectorAll('.user-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', toggleBulkActions);
});

function toggleBulkActions() {
    const checked = document.querySelectorAll('.user-checkbox:checked').length;
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (checked > 0) {
        bulkActions.style.display = 'block';
        selectedCount.textContent = checked;
    } else {
        bulkActions.style.display = 'none';
    }
    
    // Update select all checkbox
    const selectAll = document.getElementById('selectAll');
    const totalCheckboxes = document.querySelectorAll('.user-checkbox').length;
    selectAll.indeterminate = checked > 0 && checked < totalCheckboxes;
    selectAll.checked = checked === totalCheckboxes;
}

// Bulk actions
function bulkAction(action) {
    const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'))
        .map(cb => cb.value);
    
    if (selectedUsers.length === 0) {
        alert('Please select users first');
        return;
    }
    
    let confirmMessage = '';
    switch(action) {
        case 'activate':
            confirmMessage = `Activate ${selectedUsers.length} selected users?`;
            break;
        case 'block':
            confirmMessage = `Block ${selectedUsers.length} selected users?`;
            break;
        case 'delete':
            confirmMessage = `Delete ${selectedUsers.length} selected users? This action cannot be undone.`;
            break;
    }
    
    if (!confirm(confirmMessage)) {
        return;
    }
    
    // Create and submit form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.users.bulk-action") }}';
    
    // CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    // Action
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = action;
    form.appendChild(actionInput);
    
    // Selected users
    selectedUsers.forEach(userId => {
        const userInput = document.createElement('input');
        userInput.type = 'hidden';
        userInput.name = 'users[]';
        userInput.value = userId;
        form.appendChild(userInput);
    });
    
    document.body.appendChild(form);
    form.submit();
}

// Export users
function exportUsers() {
    const params = new URLSearchParams(window.location.search);
    params.append('export', 'true');
    window.location.href = '{{ route("admin.users.index") }}?' + params.toString();
}

// Auto-submit search form
document.querySelectorAll('select[name="role"], select[name="status"], select[name="sort"]').forEach(select => {
    select.addEventListener('change', function() {
        this.form.submit();
    });
});

// Real-time search
let searchTimeout;
document.querySelector('input[name="search"]').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        this.form.submit();
    }, 500);
});

// Status badge tooltips
document.querySelectorAll('.badge').forEach(badge => {
    if (badge.textContent.trim() === 'Blocked') {
        badge.title = 'This user is blocked and cannot access the platform';
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + A to select all
    if ((e.ctrlKey || e.metaKey) && e.key === 'a' && !e.target.matches('input, textarea')) {
        e.preventDefault();
        document.getElementById('selectAll').click();
    }
    
    // Delete key for bulk delete
    if (e.key === 'Delete' && document.querySelectorAll('.user-checkbox:checked').length > 0) {
        e.preventDefault();
        bulkAction('delete');
    }
});
</script>
@endpush