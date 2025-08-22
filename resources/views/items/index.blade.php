@extends('layouts.app')

@section('title', 'Browse Items - Find & Found')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Categories -->
        <div class="col-lg-3">
            <div class="sidebar-section">
                <h5 class="sidebar-title">Categories</h5>
                <div class="categories-list">
                    <a href="{{ route('items.index') }}" class="category-item {{ !request('category') ? 'active' : '' }}">
                        <span class="category-name">All Items</span>
                        <span class="category-count">{{ $items->total() }}</span>
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('items.index', ['category' => $category->id]) }}" 
                           class="category-item {{ request('category') == $category->id ? 'active' : '' }}">
                            <i class="fas fa-{{ $category->icon ?? 'box' }} me-2"></i>
                            <span class="category-name">{{ $category->name }}</span>
                            <span class="category-count">{{ $category->items_count ?? 0 }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="page-title">Recent Posts</h2>
                        <p class="page-subtitle">{{ $items->total() }} items • Updated just now</p>
                    </div>
                    @auth
                        <a href="{{ route('items.create') }}" class="btn btn-primary">
                            Post New Item
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="filter-tabs">
                        <span class="filter-label">Show:</span>
                        <a href="{{ route('items.index', array_merge(request()->except('status'), [])) }}" 
                           class="filter-tab {{ !request('status') ? 'active' : '' }}">All Items</a>
                        <a href="{{ route('items.index', array_merge(request()->all(), ['status' => 'lost'])) }}" 
                           class="filter-tab {{ request('status') == 'lost' ? 'active' : '' }}">Lost</a>
                        <a href="{{ route('items.index', array_merge(request()->all(), ['status' => 'found'])) }}" 
                           class="filter-tab {{ request('status') == 'found' ? 'active' : '' }}">Found</a>
                    </div>
                    <div class="sort-controls">
                        <form method="GET" class="d-flex gap-2">
                            @foreach(request()->except(['sort', 'view']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Viewed</option>
                            </select>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#filtersModal">
                                <i class="fas fa-filter"></i> Filters
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Items Grid -->
            <div class="items-container">
                @forelse($items as $item)
                    <div class="item-card-wrapper">
                        <a href="{{ route('items.show', $item->id) }}" class="item-card">
                            <div class="item-image">
                                <img src="{{ $item->first_photo }}" alt="{{ $item->title }}" loading="lazy">
                                <div class="item-status status-{{ $item->status }}">
                                    {{ $item->status_label }}
                                </div>
                                @if($item->is_resolved)
                                    <div class="item-resolved">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="item-content">
                                <h6 class="item-title">{{ $item->title }}</h6>
                                <p class="item-description">{{ Str::limit($item->description, 80) }}</p>
                                <div class="item-meta">
                                    <div class="meta-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $item->location->city }}
                                    </div>
                                    <div class="meta-time">{{ $item->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-illustration">
                            <i class="fas fa-search fa-3x"></i>
                        </div>
                        <h4>No items found</h4>
                        <p>Try adjusting your search criteria or browse different categories.</p>
                        @auth
                            <a href="{{ route('items.create') }}" class="btn btn-primary">Post First Item</a>
                        @endauth
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($items->hasPages())
                <div class="pagination-wrapper">
                    {{ $items->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Filters Modal -->
<div class="modal fade" id="filtersModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Advanced Filters</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('items.index') }}">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search items...">
                        </div>
                        <div class="col-6">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city" value="{{ request('city') }}" placeholder="Enter city">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Date Range</label>
                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Within Radius</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="radius" value="{{ request('radius', 10) }}" min="1" max="100">
                                <span class="input-group-text">km</span>
                            </div>
                            <div class="form-text">From your current location</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="{{ route('items.index') }}" class="btn btn-outline-primary">Clear</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Sidebar */
.sidebar-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.sidebar-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #212529;
}

.categories-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.category-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    color: #495057;
    transition: all 0.2s;
    border: 1px solid transparent;
}

.category-item:hover {
    background-color: #f8f9fa;
    color: #495057;
    text-decoration: none;
}

.category-item.active {
    background-color: #2563eb;
    color: white;
    border-color: #2563eb;
}

.category-name {
    font-weight: 500;
}

.category-count {
    background: rgba(0, 0, 0, 0.1);
    color: inherit;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
}

.category-item.active .category-count {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

/* Page Header */
.page-header {
    margin-bottom: 2rem;
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

/* Filter Bar */
.filter-bar {
    background: white;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.filter-tabs {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.filter-label {
    font-weight: 500;
    color: #6c757d;
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

.sort-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Items Container */
.items-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.item-card {
    display: block;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.2s;
    text-decoration: none;
    color: inherit;
}

.item-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    color: inherit;
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
    transition: transform 0.3s;
}

.item-card:hover .item-image img {
    transform: scale(1.05);
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
    letter-spacing: 0.5px;
}

.status-lost {
    background: #ffc107;
    color: #212529;
}

.status-found {
    background: #28a745;
    color: white;
}

.item-resolved {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #28a745;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
}

.item-content {
    padding: 1.25rem;
}

.item-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #212529;
    line-height: 1.3;
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
    font-size: 0.85rem;
    color: #6c757d;
}

.meta-location {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem 1rem;
    color: #6c757d;
}

.empty-illustration {
    margin-bottom: 1.5rem;
    color: #dee2e6;
}

.empty-state h4 {
    margin-bottom: 0.5rem;
    color: #495057;
}

.empty-state p {
    margin-bottom: 1.5rem;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

/* Responsive */
@media (max-width: 992px) {
    .items-container {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }
}

@media (max-width: 768px) {
    .filter-bar {
        padding: 1rem;
    }
    
    .filter-bar .d-flex {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch !important;
    }
    
    .filter-tabs {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .sort-controls {
        justify-content: center;
    }
    
    .items-container {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}
</style>
@endpush