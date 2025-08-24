@extends('layouts.admin')

@section('title', 'Item Management - Admin Panel')
@section('page-title', 'Item Management')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ $items->total() }}</h3>
                        <p class="mb-0 opacity-75">Total Items</p>
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
                        <h3 class="mb-1">{{ $items->where('status', 'lost')->count() }}</h3>
                        <p class="mb-0 opacity-75">Lost Items</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-circle fa-2x opacity-75"></i>
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
                        <h3 class="mb-1">{{ $items->where('status', 'found')->count() }}</h3>
                        <p class="mb-0 opacity-75">Found Items</p>
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
                        <h3 class="mb-1">{{ $items->where('is_resolved', true)->count() }}</h3>
                        <p class="mb-0 opacity-75">Resolved</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-heart fa-2x opacity-75"></i>
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
                       placeholder="Search items...">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                    <option value="found" {{ request('status') == 'found' ? 'selected' : '' }}>Found</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="active">
                    <option value="">All Items</option>
                    <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="resolved">
                    <option value="">All Items</option>
                    <option value="0" {{ request('resolved') == '0' ? 'selected' : '' }}>Unresolved</option>
                    <option value="1" {{ request('resolved') == '1' ? 'selected' : '' }}>Resolved</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="category">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Items Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Items List</h5>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-warning btn-sm" id="bulkDeactivate" style="display: none;">
                <i class="fas fa-eye-slash me-1"></i>Deactivate Selected
            </button>
            <button class="btn btn-outline-danger btn-sm" id="bulkDelete" style="display: none;">
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
                        <th>Item</th>
                        <th>Owner</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Stats</th>
                        <th>Posted</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr class="{{ !$item->is_active ? 'table-secondary' : '' }}">
                            <td>
                                <input type="checkbox" class="form-check-input item-checkbox" value="{{ $item->id }}">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $item->first_photo }}" alt="{{ $item->title }}" 
                                         class="rounded me-3" width="50" height="50" style="object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0">{{ Str::limit($item->title, 30) }}</h6>
                                        <small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                        @if(!$item->is_active)
                                            <br><small class="text-warning">
                                                <i class="fas fa-eye-slash me-1"></i>Inactive
                                            </small>
                                        @endif
                                        @if($item->is_resolved)
                                            <br><small class="text-success">
                                                <i class="fas fa-check-circle me-1"></i>Resolved
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $item->user->avatar_url }}" alt="{{ $item->user->name }}" 
                                         class="rounded-circle me-2" width="30" height="30">
                                    <div>
                                        <small class="fw-bold">{{ $item->user->name }}</small>
                                        <br><small class="text-muted">{{ $item->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $item->category->name }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $item->status === 'lost' ? 'warning' : 'success' }}">
                                    {{ $item->status_label }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $item->location->city }}
                                </small>
                            </td>
                            <td>
                                <div class="item-stats">
                                    <small class="d-block">
                                        <i class="fas fa-eye me-1"></i>{{ $item->views_count }} views
                                    </small>
                                    <small class="d-block">
                                        <i class="fas fa-comments me-1"></i>{{ $item->comments->count() }} comments
                                    </small>
                                    <small class="d-block">
                                        <i class="fas fa-flag me-1"></i>{{ $item->reports->count() }} reports
                                    </small>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">{{ $item->created_at->format('M d, Y') }}</small>
                                <br><small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group-vertical" role="group">
                                    <a href="{{ route('admin.items.show', $item->id) }}" 
                                       class="btn btn-outline-primary btn-sm" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.items.toggle-status', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning btn-sm w-100" 
                                                title="{{ $item->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $item->is_active ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" 
                                          class="d-inline" onsubmit="return confirm('Delete this item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No items found</h5>
                                <p class="text-muted">No items match your search criteria</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($items->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $items->firstItem() }}-{{ $items->lastItem() }} of {{ $items->total() }} items
                </div>
                {{ $items->withQueryString()->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.item-stats small {
    color: #6c757d;
    font-size: 0.75rem;
}

.btn-group-vertical .btn {
    margin-bottom: 2px;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.table-secondary {
    opacity: 0.7;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group-vertical .btn {
        padding: 0.25rem 0.375rem;
        font-size: 0.7rem;
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
    const bulkDeactivate = document.getElementById('bulkDeactivate');
    const bulkDelete = document.getElementById('bulkDelete');
    const selectedCount = document.getElementById('selectedCount');
    
    if (checked.length > 0) {
        bulkDeactivate.style.display = 'inline-block';
        bulkDelete.style.display = 'inline-block';
        selectedCount.textContent = checked.length;
    } else {
        bulkDeactivate.style.display = 'none';
        bulkDelete.style.display = 'none';
    }
}

// Bulk actions
document.getElementById('bulkDeactivate').addEventListener('click', function() {
    const checked = document.querySelectorAll('.item-checkbox:checked');
    const itemIds = Array.from(checked).map(cb => cb.value);
    
    if (confirm(`Deactivate ${itemIds.length} selected items?`)) {
        // TODO: Implement bulk deactivate
        console.log('Bulk deactivate items:', itemIds);
    }
});

document.getElementById('bulkDelete').addEventListener('click', function() {
    const checked = document.querySelectorAll('.item-checkbox:checked');
    const itemIds = Array.from(checked).map(cb => cb.value);
    
    if (confirm(`Delete ${itemIds.length} selected items? This action cannot be undone.`)) {
        // TODO: Implement bulk delete
        console.log('Bulk delete items:', itemIds);
    }
});

// Auto-submit form on select change
document.querySelectorAll('select').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>
@endpush