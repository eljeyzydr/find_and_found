@extends('layouts.app')

@section('title', $category->name . ' - Find & Found')

@section('content')
<div class="container py-4">
    <!-- Category Header -->
    <div class="category-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="category-icon-large">
                        @if($category->icon)
                            <img src="{{ $category->icon_url }}" alt="{{ $category->name }}">
                        @else
                            <i class="fas fa-box"></i>
                        @endif
                    </div>
                    <div>
                        <h1 class="category-title">{{ $category->name }}</h1>
                        <p class="category-description">{{ $category->description }}</p>
                    </div>
                </div>
                <div class="category-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $items->total() }}</span>
                        <span class="stat-label">Total Items</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $items->where('status', 'lost')->count() }}</span>
                        <span class="stat-label">Lost</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $items->where('status', 'found')->count() }}</span>
                        <span class="stat-label">Found</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                @auth
                    <a href="{{ route('items.create', ['category' => $category->id]) }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>Post Item
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Join to Post
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="filter-tabs">
                <span class="filter-label">Show:</span>
                <a href="{{ route('categories.show', $category->slug) }}" 
                   class="filter-tab {{ !request('status') ? 'active' : '' }}">
                   All Items
                </a>
                <a href="{{ route('categories.show', [$category->slug, 'status' => 'lost']) }}" 
                   class="filter-tab {{ request('status') == 'lost' ? 'active' : '' }}">
                   Lost
                </a>
                <a href="{{ route('categories.show', [$category->slug, 'status' => 'found']) }}" 
                   class="filter-tab {{ request('status') == 'found' ? 'active' : '' }}">
                   Found
                </a>
            </div>
            <div class="sort-controls">
                <form method="GET" class="d-flex gap-2">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Viewed</option>
                    </select>
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
                        <div class="item-stats">
                            <span class="stat-item">
                                <i class="fas fa-eye"></i>
                                {{ $item->views_count }}
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-comments"></i>
                                {{ $item->comments->count() }}
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-illustration">
                    <i class="fas fa-search fa-3x"></i>
                </div>
                <h4>No {{ strtolower($category->name) }} items found</h4>
                <p>Be the first to post a {{ strtolower($category->name) }} item in this category.</p>
                @auth
                    <a href="{{ route('items.create', ['category' => $category->id]) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Post First {{ $category->name }} Item
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Join to Post Items
                    </a>
                @endauth
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
        <div class="pagination-wrapper">
            {{ $items->withQueryString()->links() }}
        </div>
    @endif

    <!-- Related Categories -->
    @if($related_categories && $related_categories->count() > 0)
        <div class="related-categories mt-5">
            <h3>Related Categories</h3>
            <div class="related-grid">
                @foreach($related_categories as $relatedCategory)
                    <a href="{{ route('categories.show', $relatedCategory->slug) }}" class="related-category">
                        <div class="related-icon">
                            @if($relatedCategory->icon)
                                <img src="{{ $relatedCategory->icon_url }}" alt="{{ $relatedCategory->name }}">
                            @else
                                <i class="fas fa-box"></i>
                            @endif
                        </div>
                        <div class="related-info">
                            <h6>{{ $relatedCategory->name }}</h6>
                            <p>{{ $relatedCategory->items_count ?? 0 }} items</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* Category Header */
.category-header {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.category-icon-large {
    width: 80px;
    height: 80px;
    background: #f8f9fa;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.category-icon-large img {
    width: 48px;
    height: 48px;
    object-fit: contain;
}

.category-icon-large i {
    font-size: 2rem;
    color: #2563eb;
}

.category-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.5rem;
}

.category-description {
    color: #6c757d;
    font-size: 1.1rem;
    margin-bottom: 0;
    line-height: 1.5;
}

.category-stats {
    display: flex;
    gap: 2rem;
}

.category-stats .stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.category-stats .stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2563eb;
    display: block;
}

.category-stats .stat-label {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
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
    margin-bottom: 0.75rem;
    font-size: 0.85rem;
    color: #6c757d;
}

.meta-location {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.item-stats {
    display: flex;
    gap: 1rem;
    font-size: 0.8rem;
    color: #6c757d;
}

.item-stats .stat-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-illustration {
    margin-bottom: 1.5rem;
    color: #dee2e6;
}

.empty-state h4 {
    color: #495057;
    margin-bottom: 1rem;
}

.empty-state p {
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

/* Related Categories */
.related-categories {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.related-categories h3 {
    margin-bottom: 1.5rem;
    color: #212529;
    font-weight: 600;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.related-category {
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

.related-category:hover {
    background: #e9ecef;
    text-decoration: none;
    color: inherit;
    transform: translateY(-1px);
}

.related-icon {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.related-icon img {
    width: 24px;
    height: 24px;
    object-fit: contain;
}

.related-icon i {
    font-size: 1.2rem;
    color: #2563eb;
}

.related-info h6 {
    margin-bottom: 0.25rem;
    color: #212529;
    font-weight: 600;
}

.related-info p {
    margin-bottom: 0;
    color: #6c757d;
    font-size: 0.8rem;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

/* Responsive */
@media (max-width: 768px) {
    .category-header {
        padding: 1.5rem;
    }
    
    .category-header .row {
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .category-title {
        font-size: 2rem;
    }
    
    .category-stats {
        justify-content: center;
        gap: 1.5rem;
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
    
    .items-container {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }
    
    .related-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .items-container {
        grid-template-columns: 1fr;
    }
    
    .category-stats {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
@endpush