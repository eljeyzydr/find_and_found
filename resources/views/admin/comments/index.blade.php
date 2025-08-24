@extends('layouts.admin')

@section('title', 'Manage Comments - Admin Panel')
@section('page-title', 'Manage Comments')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ \App\Models\Comment::count() }}</h3>
                        <p class="mb-0 opacity-75">Total Comments</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-comments fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ \App\Models\Comment::approved()->count() }}</h3>
                        <p class="mb-0 opacity-75">Approved</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ \App\Models\Comment::pending()->count() }}</h3>
                        <p class="mb-0 opacity-75">Pending Review</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ \App\Models\Comment::whereDate('created_at', today())->count() }}</h3>
                        <p class="mb-0 opacity-75">Today</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-day fa-2x opacity-75"></i>
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
                <label class="form-label">Search</label>
                <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                       placeholder="Search comments or users...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="">All Comments</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date Range</label>
                <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Actions -->
<div class="card mb-4" id="bulkActions" style="display: none;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.comments.bulk-action') }}" id="bulkForm">
            @csrf
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Bulk Action</label>
                    <select class="form-select" name="action" required>
                        <option value="">Select Action</option>
                        <option value="approve">Approve Selected</option>
                        <option value="reject">Reject Selected</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure?')">
                        Apply to <span id="selectedCount">0</span> comments
                    </button>
                    <button type="button" class="btn btn-outline-secondary ms-2" onclick="clearSelection()">
                        Clear Selection
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Comments Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Comments List</h5>
            <div>
                <input type="checkbox" class="form-check-input me-2" id="selectAll">
                <label for="selectAll" class="form-check-label">Select All</label>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($comments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50"></th>
                            <th>Comment</th>
                            <th>User</th>
                            <th>Item</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comments as $comment)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input item-checkbox" 
                                           name="comments[]" value="{{ $comment->id }}" form="bulkForm">
                                </td>
                                <td>
                                    <div class="comment-content">
                                        <p class="mb-1">{{ Str::limit($comment->content, 100) }}</p>
                                        <small class="text-muted">{{ strlen($comment->content) }} characters</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" 
                                             class="rounded-circle me-2" width="32" height="32">
                                        <div>
                                            <div class="fw-bold">{{ $comment->user->name }}</div>
                                            <small class="text-muted">{{ $comment->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $comment->item->first_photo }}" alt="{{ $comment->item->title }}" 
                                             class="rounded me-2" width="32" height="32" style="object-fit: cover;">
                                        <div>
                                            <div class="fw-bold">{{ Str::limit($comment->item->title, 30) }}</div>
                                            <small class="text-muted">
                                                <span class="badge bg-{{ $comment->item->status === 'lost' ? 'warning' : 'success' }}">
                                                    {{ $comment->item->status_label }}
                                                </span>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($comment->is_approved)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $comment->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm table-actions">
                                        <a href="{{ route('items.show', $comment->item->id) }}#comment-{{ $comment->id }}" 
                                           class="btn btn-outline-primary" target="_blank" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!$comment->is_approved)
                                            <form method="POST" action="{{ route('admin.comments.approve', $comment->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($comment->is_approved)
                                            <form method="POST" action="{{ route('admin.comments.reject', $comment->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning" title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('comments.destroy', $comment->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-delete" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No comments found</h5>
                <p class="text-muted">Comments will appear here when users post them on items.</p>
            </div>
        @endif
    </div>
    
    @if($comments->hasPages())
        <div class="card-footer">
            {{ $comments->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Clear selection
function clearSelection() {
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    toggleBulkActions();
}

// Auto-approve comments with good content
function quickApprove() {
    if (confirm('This will approve all pending comments that appear to be legitimate. Continue?')) {
        // Implementation would go here
        alert('Feature not implemented in demo');
    }
}
</script>
@endpush