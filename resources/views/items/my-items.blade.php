@extends('layouts.app')

@section('title', 'My Items - Find & Found')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">My Items</h1>
                <p class="page-subtitle">Manage your lost and found items</p>
            </div>
            <a href="{{ route('items.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Item
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-overview mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $items->total() }}</div>
                        <div class="stat-label">Total Items</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card lost">
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $items->where('status', 'lost')->count() }}</div>
                        <div class="stat-label">Lost Items</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card found">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $items->where('status', 'found')->count() }}</div>
                        <div class="stat-label">Found Items</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card resolved">
                    <div class="stat-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $items->where('is_resolved', true)->count() }}</div>
                        <div class="stat-label">Resolved</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar mb-4">
        <form method="GET" class="d-flex justify-content-between align-items-center">
            <div class="filter-tabs">
                <a href="{{ route('items.my-items') }}" 
                   class="filter-tab {{ !request('status') && !request('resolved') ? 'active' : '' }}">
                   All Items
                </a>
                <a href="{{ route('items.my-items', ['status' => 'lost']) }}" 
                   class="filter-tab {{ request('status') == 'lost' ? 'active' : '' }}">
                   Lost
                </a>
                <a href="{{ route('items.my-items', ['status' => 'found']) }}" 
                   class="filter-tab {{ request('status') == 'found' ? 'active' : '' }}">
                   Found
                </a>
                <a href="{{ route('items.my-items', ['resolved' => '1']) }}" 
                   class="filter-tab {{ request('resolved') == '1' ? 'active' : '' }}">
                   Resolved
                </a>
            </div>
            <div class="view-controls">
                <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="most_viewed" {{ request('sort') == 'most_viewed' ? 'selected' : '' }}>Most Viewed</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Items List -->
    <div class="items-container">
        @forelse($items as $item)
            <div class="item-card">
                <div class="item-image">
                    <img src="{{ $item->first_photo }}" alt="{{ $item->title }}">
                    <div class="item-status status-{{ $item->status }}">
                        {{ $item->status_label }}
                    </div>
                    @if($item->is_resolved)
                        <div class="resolved-badge">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    @endif
                    @if(!$item->is_active)
                        <div class="inactive-overlay">
                            <span>Inactive</span>
                        </div>
                    @endif
                </div>
                <div class="item-content">
                    <h5 class="item-title">{{ $item->title }}</h5>
                    <p class="item-description">{{ Str::limit($item->description, 100) }}</p>
                    <div class="item-meta">
                        <div class="meta-info">
                            <span class="meta-location">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $item->location->city }}
                            </span>
                            <span class="meta-date">{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="meta-stats">
                            <span class="stat-item">
                                <i class="fas fa-eye"></i>
                                {{ $item->views_count }}
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-comments"></i>
                                {{ $item->comments_count }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="item-actions">
                    <div class="primary-actions">
                        <a href="{{ route('items.show', $item->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(!$item->is_resolved)
                                <li>
                                    <form action="{{ route('items.mark-resolved', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="dropdown-item" type="submit">
                                            <i class="fas fa-check me-2"></i>Mark as Resolved
                                        </button>
                                    </form>
                                </li>
                            @endif
                            <li>
                                <form action="{{ route('items.toggle-active', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="dropdown-item" type="submit">
                                        <i class="fas fa-{{ $item->is_active ? 'eye-slash' : 'eye' }} me-2"></i>
                                        {{ $item->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="fas fa-trash me-2"></i>Delete
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-illustration">
                    <i class="fas fa-box-open"></i>
                </div>
                <h4>No items found</h4>
                <p>You haven't posted any items yet. Start by adding your first item.</p>
                <a href="{{ route('items.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Post Your First Item
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
        <div class="pagination-wrapper">
            {{ $items->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* Page Header */
.page-header {
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

/* Stats Overview */
.stats-overview {
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
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
    font-size: 1.5rem;
    font-weight: 700;
    color: #212529;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
}

/* Filter Bar */
.filter-bar {
    background: white;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.filter-tabs {
    display: flex;
    gap: 1rem;
}

.filter-tab {
    padding: 6px 16px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    text-decoration: none;
    color: #495057;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.filter-tab.active,
.filter-tab:hover {
    background: #2563eb;
    color: white;
    border-color: #2563eb;
    text-decoration: none;
}

/* Items Container */
.items-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.item-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.item-card:hover {
    transform: translateY(-2px);
}

.item-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-status {
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 0.75rem;
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

.resolved-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 24px;
    height: 24px;
    background: #28a745;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
}

.inactive-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
}

.item-content {
    padding: 1.25rem;
}

.item-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #212529;
}

.item-description {
    color: #6c757d;
    margin-bottom: 1rem;
    line-height: 1.4;
    font-size: 0.9rem;
}

.item-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.meta-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.meta-location,
.meta-date {
    font-size: 0.8rem;
    color: #6c757d;
}

.meta-location {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.meta-stats {
    display: flex;
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.8rem;
    color: #6c757d;
}

.item-actions {
    padding: 1rem 1.25rem;
    background: #f8f9fa;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.primary-actions {
    display: flex;
    gap: 0.5rem;
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-illustration {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1.5rem;
}

.empty-state h4 {
    color: #495057;
    margin-bottom: 1rem;
}

.empty-state p {
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

/* Responsive */
@media (max-width: 768px) {
    .items-container {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .filter-bar .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .filter-tabs {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .item-actions {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .primary-actions {
        justify-content: center;
    }
}
</style>
@endpush