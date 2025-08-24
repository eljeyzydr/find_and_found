@extends('layouts.admin')

@section('title', 'Chat Management - Admin')
@section('page-title', 'Chat Management')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Chat Messages</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-danger btn-sm" id="bulkDeleteBtn" style="display: none;">
                            <i class="fas fa-trash me-2"></i>Delete Selected
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" placeholder="Search messages..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.chats.index') }}" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </form>

                <!-- Chat Messages Table -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Sender</th>
                                <th>Receiver</th>
                                <th>Message</th>
                                <th>Item Context</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th width="100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($chats as $chat)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="item-checkbox" value="{{ $chat->id }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $chat->sender->avatar_url }}" alt="{{ $chat->sender->name }}" 
                                                 class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <div class="fw-bold">{{ $chat->sender->name }}</div>
                                                <small class="text-muted">{{ $chat->sender->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $chat->receiver->avatar_url }}" alt="{{ $chat->receiver->name }}" 
                                                 class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <div class="fw-bold">{{ $chat->receiver->name }}</div>
                                                <small class="text-muted">{{ $chat->receiver->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="message-preview">
                                            {{ Str::limit($chat->message, 100) }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($chat->item)
                                            <div class="item-context">
                                                <a href="{{ route('items.show', $chat->item->id) }}" target="_blank" class="text-decoration-none">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $chat->item->first_photo }}" alt="{{ $chat->item->title }}" 
                                                             class="rounded me-2" width="32" height="32" style="object-fit: cover;">
                                                        <div>
                                                            <div class="fw-bold">{{ Str::limit($chat->item->title, 30) }}</div>
                                                            <small class="badge bg-{{ $chat->item->status === 'lost' ? 'warning' : 'success' }}">
                                                                {{ $chat->item->status_label }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $chat->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $chat->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($chat->is_read)
                                            <span class="badge bg-success">Read</span>
                                        @else
                                            <span class="badge bg-warning">Unread</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <form action="{{ route('admin.chats.destroy', $chat->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-delete" 
                                                        title="Delete Chat">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No chat messages found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($chats->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $chats->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Selected Messages</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <span id="selectedCount">0</span> selected messages?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.chats.bulk-delete') }}" method="POST" id="bulkDeleteForm">
                    @csrf
                    <input type="hidden" name="chats" id="selectedChats">
                    <button type="submit" class="btn btn-danger">Delete Messages</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkActions();
    });
    
    // Individual checkbox change
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkActions);
    });
    
    function toggleBulkActions() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        if (checkedBoxes.length > 0) {
            bulkDeleteBtn.style.display = 'inline-block';
            document.getElementById('selectedCount').textContent = checkedBoxes.length;
        } else {
            bulkDeleteBtn.style.display = 'none';
        }
    }
    
    // Bulk delete
    bulkDeleteBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
        const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
        document.getElementById('selectedChats').value = JSON.stringify(selectedIds);
        
        const modal = new bootstrap.Modal(document.getElementById('bulkDeleteModal'));
        modal.show();
    });
});
</script>
@endpush