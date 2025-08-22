@extends('layouts.app')

@section('title', 'Dashboard - Find & Found')

@section('content')
<div class="container py-4">
    <!-- Welcome Header -->
    <div class="welcome-section mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="welcome-title">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="welcome-subtitle">Here's what's happening with your items</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('items.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Post New Item
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid mb-5">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['my_items'] }}</div>
                <div class="stat-label">Total Items</div>
            </div>
        </div>
        
        <div class="stat-card lost">
            <div class="stat-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['lost_items'] }}</div>
                <div class="stat-label">Lost Items</div>
            </div>
        </div>
        
        <div class="stat-card found">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['found_items'] }}</div>
                <div class="stat-label">Found Items</div>
            </div>
        </div>
        
        <div class="stat-card resolved">
            <div class="stat-icon">
                <i class="fas fa-heart"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $stats['resolved_items'] }}</div>
                <div class="stat-label">Reunited</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- My Recent Items -->
        <div class="col-lg-8 mb-4">
            <div class="dashboard-section">
                <div class="section-header">
                    <h3>My Recent Items</h3>
                    <a href="{{ route('items.my-items') }}" class="btn btn-outline-primary btn-sm">View All</a>
                </div>
                
                @if($recent_items->count() > 0)
                    <div class="items-list">
                        @foreach($recent_items as $item)
                            <div class="item-row">
                                <div class="item-image">
                                    <img src="{{ $item->first_photo }}" alt="{{ $item->title }}">
                                    <div class="item-status status-{{ $item->status }}">
                                        {{ $item->status === 'lost' ? 'Lost' : 'Found' }}
                                    </div>
                                </div>
                                <div class="item-details">
                                    <h6 class="item-title">{{ $item->title }}</h6>
                                    <p class="item-description">{{ Str::limit($item->description, 80) }}</p>
                                    <div class="item-meta">
                                        <span class="meta-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $item->location->city }}
                                        </span>
                                        <span class="meta-time">{{ $item->created_at->diffForHumans() }}</span>
                                        @if($item->is_resolved)
                                            <span class="badge bg-success">Resolved</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="item-actions">
                                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <h5>No items yet</h5>
                        <p>Start by posting your first lost or found item</p>
                        <a href="{{ route('items.create') }}" class="btn btn-primary">Post First Item</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="dashboard-section mb-4">
                <div class="section-header">
                    <h3>Quick Actions</h3>
                </div>
                <div class="quick-actions">
                    <a href="{{ route('items.create') }}" class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="action-content">
                            <h6>Post Item</h6>
                            <p>Report lost or found item</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('items.index') }}" class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="action-content">
                            <h6>Browse Items</h6>
                            <p>Find lost items near you</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('chats.index') }}" class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="action-content">
                            <h6>Messages</h6>
                            <p>Chat with other users</p>
                            @if($stats['unread_chats'] > 0)
                                <span class="badge bg-danger">{{ $stats['unread_chats'] }}</span>
                            @endif
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Notifications -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h3>Notifications</h3>
                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-primary btn-sm">View All</a>
                </div>
                
                @if($recent_notifications->count() > 0)
                    <div class="notifications-list">
                        @foreach($recent_notifications as $notification)
                            <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }}">
                                <div class="notification-icon">
                                    <i class="{{ $notification->type_icon }} text-{{ $notification->type_color }}"></i>
                                </div>
                                <div class="notification-content">
                                    <h6>{{ $notification->title }}</h6>
                                    <p>{{ $notification->message }}</p>
                                    <small>{{ $notification->created_at_human }}</small>
                                </div>
                                @if(!$notification->is_read)
                                    <div class="notification-dot"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    @if($stats['unread_notifications'] > 0)
                        <div class="text-center mt-3">
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    Mark All Read
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="empty-notifications">
                        <i class="fas fa-bell-slash"></i>
                        <p>No notifications yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tips Section -->
    <div class="tips-section mt-5">
        <div class="tips-header">
            <h3><i class="fas fa-lightbulb me-2"></i>Tips for Success</h3>
        </div>
        <div class="tips-grid">
            <div class="tip-card">
                <div class="tip-icon">
                    <i class="fas fa-camera"></i>
                </div>
                <h6>Use Clear Photos</h6>
                <p>Upload high-quality images from multiple angles to increase visibility</p>
            </div>
            <div class="tip-card">
                <div class="tip-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h6>Be Specific with Location</h6>
                <p>Provide detailed location information to help others find your item</p>
            </div>
            <div class="tip-card">
                <div class="tip-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h6>Respond Quickly</h6>
                <p>Reply to messages promptly to increase chances of reunion</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Welcome Section */
.welcome-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.welcome-title {
    font-size: 2rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.5rem;
}

.welcome-subtitle {
    color: #6c757d;
    margin-bottom: 0;
    font-size: 1.1rem;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    background: #2563eb;
    color: white;
}

.stat-card.lost .stat-icon {
    background: #ffc107;
    color: #212529;
}

.stat-card.found .stat-icon {
    background: #28a745;
}

.stat-card.resolved .stat-icon {
    background: #dc3545;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #212529;
    line-height: 1;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 500;
}

/* Dashboard Sections */
.dashboard-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.section-header h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #212529;
    margin-bottom: 0;
}

/* Items List */
.items-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.item-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    transition: background-color 0.2s;
}

.item-row:hover {
    background: #e9ecef;
}

.item-image {
    position: relative;
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-status {
    position: absolute;
    top: 4px;
    right: 4px;
    padding: 2px 6px;
    border-radius: 8px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-lost {
    background: #ffc107;
    color: #212529;
}

.status-found {
    background: #28a745;
    color: white;
}

.item-details {
    flex: 1;
    min-width: 0;
}

.item-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #212529;
}

.item-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.item-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 0.8rem;
    color: #6c757d;
}

.meta-location {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.item-actions {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
}

/* Quick Actions */
.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.action-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s;
}

.action-card:hover {
    background: #e9ecef;
    text-decoration: none;
    color: inherit;
    transform: translateX(4px);
}

.action-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    background: #2563eb;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.action-content h6 {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #212529;
}

.action-content p {
    color: #6c757d;
    font-size: 0.85rem;
    margin-bottom: 0;
}

/* Notifications */
.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    position: relative;
}

.notification-item.unread {
    background: #e3f2fd;
    border-left: 3px solid #2563eb;
}

.notification-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.notification-content h6 {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #212529;
    font-size: 0.9rem;
}

.notification-content p {
    color: #6c757d;
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.notification-content small {
    color: #6c757d;
    font-size: 0.75rem;
}

.notification-dot {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 8px;
    height: 8px;
    background: #2563eb;
    border-radius: 50%;
}

.empty-notifications {
    text-align: center;
    padding: 2rem 1rem;
    color: #6c757d;
}

.empty-notifications i {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #dee2e6;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    font-size: 3rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.empty-state h5 {
    color: #495057;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 1.5rem;
}

/* Tips Section */
.tips-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.tips-header h3 {
    color: #212529;
    margin-bottom: 1.5rem;
}

.tips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.tip-card {
    text-align: center;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.tip-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #2563eb;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin: 0 auto 1rem;
}

.tip-card h6 {
    color: #212529;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.tip-card p {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0;
    line-height: 1.4;
}

/* Responsive */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .stat-card {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        font-size: 1.2rem;
    }
    
    .item-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .item-actions {
        align-self: stretch;
        justify-content: space-between;
    }
    
    .tips-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Auto-refresh notifications count
function updateNotificationCount() {
    fetch('/api/notifications/count')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.notification-badge');
            if (badge) {
                badge.textContent = data.count;
                badge.style.display = data.count > 0 ? 'block' : 'none';
            }
        })
        .catch(error => console.log('Error fetching notifications:', error));
}

// Update counts every 30 seconds
setInterval(updateNotificationCount, 30000);

// Mark notification as read when clicked
document.querySelectorAll('.notification-item').forEach(item => {
    item.addEventListener('click', function() {
        const notificationId = this.dataset.id;
        if (notificationId && this.classList.contains('unread')) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(() => {
                this.classList.remove('unread');
                updateNotificationCount();
            });
        }
    });
});
</script>
@endpush
