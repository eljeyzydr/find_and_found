@extends('layouts.admin')

@section('title', 'User Management - Admin Panel')
@section('page-title', 'User Management')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ $users->total() }}</h3>
                        <p class="mb-0 opacity-75">Total Users</p>
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
                        <h3 class="mb-1">{{ $users->where('status', 'active')->count() }}</h3>
                        <p class="mb-0 opacity-75">Active Users</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-check fa-2x opacity-75"></i>
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
                        <h3 class="mb-1">{{ $users->where('status', 'blocked')->count() }}</h3>
                        <p class="mb-0 opacity-75">Blocked Users</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-slash fa-2x opacity-75"></i>
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
                        <h3 class="mb-1">{{ $users->where('role', 'admin')->count() }}</h3>
                        <p class="mb-0 opacity-75">Administrators</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-shield fa-2x opacity-75"></i>
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
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                       placeholder="Search users...">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="role">
                    <option value="">All Roles</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="sort">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                    <option value="most_active" {{ request('sort') == 'most_active' ? 'selected' : '' }}>Most Active</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Users List</h5>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-danger btn-sm" id="bulkActions" style="display: none;">
                <i class="fas fa-trash me-1"></i>Delete Selected (<span id="selectedCount">0</span>)
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
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Activity</th>
                        <th>Joined</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input item-checkbox" value="{{ $user->id }}">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                                         class="rounded-circle me-3" width="40" height="40">
                                    <div>
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->email }}</small>
                                        @if($user->phone)
                                            <br><small class="text-muted">{{ $user->phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'secondary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="activity-stats">
                                    <small class="d-block">
                                        <i class="fas fa-box me-1"></i>
                                        {{ $user->items_count }} items
                                    </small>
                                    <small class="d-block">
                                        <i class="fas fa-comments me-1"></i>
                                        {{ $user->comments_count }} comments
                                    </small>
                                    <small class="d-block">
                                        <i class="fas fa-flag me-1"></i>
                                        {{ $user->reports_count }} reports
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">{{ $user->created_at->format('M d, Y') }}</span>
                                <br><small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
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
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-warning btn-sm" 
                                                    title="{{ $user->status === 'active' ? 'Block' : 'Activate' }}">
                                                <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" 
                                              class="d-inline" onsubmit="return confirm('Delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No users found</h5>
                                <p class="text-muted">No users match your search criteria</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $users->firstItem() }}-{{ $users->lastItem() }} of {{ $users->total() }} users
                </div>
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.activity-stats small {
    color: #6c757d;
    font-size: 0.75rem;
}

.table-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.user-avatar {
    width: 40px;
    height: 40px;
    object-fit: cover;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.375rem;
    }
    
    .activity-stats {
        font-size: 0.75rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
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
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (checked.length > 0) {
        bulkActions.style.display = 'inline-block';
        selectedCount.textContent = checked.length;
    } else {
        bulkActions.style.display = 'none';
    }
}

// Bulk delete
document.getElementById('bulkActions').addEventListener('click', function() {
    const checked = document.querySelectorAll('.item-checkbox:checked');
    const userIds = Array.from(checked).map(cb => cb.value);
    
    if (confirm(`Delete ${userIds.length} selected users?`)) {
        // TODO: Implement bulk delete
        console.log('Bulk delete users:', userIds);
    }
});

// Auto-submit form on select change
document.querySelectorAll('select[name="role"], select[name="status"], select[name="sort"]').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>
@endpush