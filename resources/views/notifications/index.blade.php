@extends('layouts.app')

@section('title', 'Notifications - Find & Found')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="notifications-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Notifications</h1>
                <p class="page-subtitle">
                    {{ $notifications->total() }} notifications • 
                    {{ $notifications->where('is_read', false)->count() }} unread
                </p>
            </div>
            <div class="header-actions">
                @if($notifications->where('is_read', false)->count() > 0)
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-check-double me-2"></i>Mark All Read
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs mb-4">
        <div class="tabs-container">
            <a href="{{ route('notifications.index') }}" 
               class="tab-item {{ !request('type') && !request('filter') ? 'active' : '' }}">
               All Notifications
            </a>
            <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
               class="tab-item {{ request('filter') == 'unread' ? 'active' : '' }}">
               Unread
               @if($notifications->where('is_read', false)->count() > 0)
                   <span class="badge bg-danger ms-1">{{ $notifications->where('is_read', false)->count() }}</span>
               @endif
            </a>
            <a href="{{ route('notifications.index', ['type' => 'comment']) }}" 
               class="tab-item {{ request('type') == 'comment' ? 'active' : '' }}">
               Comments
            </a>
            <a href="{{ route('notifications.index', ['type' => 'chat']) }}" 
               class="tab-item {{ request('type') == 'chat' ? 'active' : '' }}">
               Messages
            </a>
            <a href="{{ route('notifications.index', ['type' => 'match']) }}" 
               class="tab-item {{ request('type') == 'match' ? 'active' : '' }}">
               Matches
            </a>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="notifications-container">
        @forelse($notifications as $notification)
            <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }}" 
                 data-notification-id="{{ $notification->id }}">
                <div class="notification-icon">
                    <i class="{{ $notification->type_icon }} text-{{ $notification->type_color }}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-header">
                        <h6 class="notification-title">{{ $notification->title }}</h6>
                        <div class="notification-meta">
                            <span class="notification-time">{{ $notification->created_at_human }}</span>
                            @if(!$notification->is_read)
                                <span class="unread-dot"></span>
                            @endif
                        </div>
                    </div>
                    <p class="notification-message">{{ $notification->message }}</p>
                    @if($notification->data && isset($notification->data['item_id']))
                        <div class="notification-context">
                            <i class="fas fa-box me-1"></i>
                            <a href="{{ route('items.show', $notification->data['item_id']) }}">
                                View Item
                            </a>
                        </div>
                    @endif
                </div>
                <div class="notification-actions">
                    @if($notification->action_url && $notification->action_url !== '#')
                        <a href="{{ route('notifications.read', $notification->id) }}" 
                           class="btn btn-outline-primary btn-sm">
                            View
                        </a>
                    @endif
                    @if(!$notification->is_read)
                        <button class="btn btn-outline-secondary btn-sm" 
                                onclick="markAsRead({{ $notification->id }})">
                            <i class="fas fa-check"></i>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-notifications">
                <div class="empty-illustration">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <h4>No notifications</h4>
                <p>When you receive notifications, they'll appear here</p>
                <a href="{{ route('items.index') }}" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Browse Items
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="pagination-wrapper">
            {{ $notifications->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* Page Header */
.notifications-header {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: #6c757d;
    margin-bottom: 0;
}

.header-actions {
    display: flex;
    gap: 0.5rem;
}

/* Filter Tabs */
.filter-tabs {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.tabs-container {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.tab-item {
    display: flex;
    align-items: center;
    padding: 8px 16px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    text-decoration: none;
    color: #495057;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.tab-item.active,
.tab-item:hover {
    background: #2563eb;
    color: white;
    border-color: #2563eb;
    text-decoration: none;
}

.tab-item .badge {
    font-size: 0.7rem;
}

/* Notifications Container */
.notifications-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    border-bottom: 1px solid #e9ecef;
    transition: all 0.2s;
    cursor: pointer;
    position: relative;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item:hover {
    background: #f8f9fa;
}

.notification-item.unread {
    background: #e3f2fd;
    border-left: 4px solid #2563eb;
}

.notification-item.unread:hover {
    background: #bbdefb;
}

.notification-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.5rem;
}

.notification-title {
    font-weight: 600;
    margin-bottom: 0;
    color: #212529;
    font-size: 1rem;
}

.notification-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}

.notification-time {
    font-size: 0.8rem;
    color: #6c757d;
}

.unread-dot {
    width: 8px;
    height: 8px;
    background: #2563eb;
    border-radius: 50%;
}

.notification-message {
    color: #495057;
    margin-bottom: 0.5rem;
    line-height: 1.5;
    font-size: 0.9rem;
}

.notification-context {
    display: inline-flex;
    align-items: center;
    padding: 4px 8px;
    background: rgba(37, 99, 235, 0.1);
    border-radius: 12px;
    font-size: 0.8rem;
    color: #2563eb;
    text-decoration: none;
}

.notification-context:hover {
    background: rgba(37, 99, 235, 0.2);
    text-decoration: none;
    color: #1d4ed8;
}

.notification-context a {
    color: inherit;
    text-decoration: none;
}

.notification-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    flex-shrink: 0;
}

.notification-actions .btn {
    min-width: 60px;
}

/* Empty State */
.empty-notifications {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-illustration {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1.5rem;
}

.empty-notifications h4 {
    color: #495057;
    margin-bottom: 1rem;
}

.empty-notifications p {
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

/* Notification Type Colors */
.text-comment {
    color: #2563eb;
}

.text-chat {
    color: #10b981;
}

.text-match {
    color: #f59e0b;
}

.text-system {
    color: #6b7280;
}

.text-report {
    color: #ef4444;
}

/* Loading State */
.notification-item.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Responsive */
@media (max-width: 768px) {
    .notifications-header {
        padding: 1.5rem;
    }
    
    .notifications-header .d-flex {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start !important;
    }
    
    .header-actions {
        width: 100%;
        justify-content: center;
    }
    
    .tabs-container {
        justify-content: center;
    }
    
    .notification-item {
        padding: 1rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .notification-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .notification-meta {
        align-self: flex-end;
    }
    
    .notification-actions {
        flex-direction: row;
        align-self: stretch;
        justify-content: center;
    }
    
    .notification-actions .btn {
        flex: 1;
    }
}

@media (max-width: 576px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .tabs-container {
        flex-direction: column;
    }
    
    .tab-item {
        justify-content: center;
    }
    
    .notification-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
function markAsRead(notificationId) {
    const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
    
    if (!notificationItem) return;
    
    // Add loading state
    notificationItem.classList.add('loading');
    
    fetch(`/notifications/${notificationId}/read`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) {
            // Mark as read in UI
            notificationItem.classList.remove('unread');
            notificationItem.classList.remove('loading');
            
            // Remove unread dot
            const unreadDot = notificationItem.querySelector('.unread-dot');
            if (unreadDot) {
                unreadDot.remove();
            }
            
            // Remove mark as read button
            const markReadBtn = notificationItem.querySelector('.btn-outline-secondary');
            if (markReadBtn) {
                markReadBtn.remove();
            }
            
            // Update badge counts
            updateNotificationCounts();
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
        notificationItem.classList.remove('loading');
    });
}

function updateNotificationCounts() {
    // Update unread count in navbar
    const navBadge = document.querySelector('.navbar .notification-badge');
    if (navBadge) {
        const currentCount = parseInt(navBadge.textContent) - 1;
        if (currentCount <= 0) {
            navBadge.style.display = 'none';
        } else {
            navBadge.textContent = currentCount;
        }
    }
    
    // Update page subtitle
    const subtitle = document.querySelector('.page-subtitle');
    if (subtitle) {
        // This would need to be updated with actual counts from server
        // For now, just reload the page to get updated counts
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }
}

// Auto-mark as read when clicking notification
document.querySelectorAll('.notification-item').forEach(item => {
    item.addEventListener('click', function(e) {
        // Don't mark as read if clicking action buttons
        if (e.target.closest('.notification-actions')) {
            return;
        }
        
        const notificationId = this.dataset.notificationId;
        if (this.classList.contains('unread')) {
            markAsRead(notificationId);
        }
    });
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'a' && (e.ctrlKey || e.metaKey)) {
        // Ctrl+A or Cmd+A to mark all as read
        e.preventDefault();
        const markAllBtn = document.querySelector('form[action*="mark-all-read"] button');
        if (markAllBtn) {
            markAllBtn.click();
        }
    }
});

// Auto-refresh notifications every 30 seconds
setInterval(function() {
    // Check for new notifications
    fetch('/api/notifications/count')
        .then(response => response.json())
        .then(data => {
            const currentUnread = document.querySelectorAll('.notification-item.unread').length;
            if (data.count > currentUnread) {
                // New notifications available, show indicator
                showNewNotificationsAlert();
            }
        })
        .catch(error => console.log('Error checking notifications:', error));
}, 30000);

function showNewNotificationsAlert() {
    const alert = document.createElement('div');
    alert.className = 'alert alert-info alert-dismissible fade show position-fixed';
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
    alert.innerHTML = `
        <i class="fas fa-bell me-2"></i>
        New notifications available
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alert);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endpush