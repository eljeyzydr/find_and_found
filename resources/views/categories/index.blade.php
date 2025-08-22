@extends('layouts.app')

@section('title', 'Browse Categories - Find & Found')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header mb-5">
        <div class="text-center">
            <h1 class="page-title">Browse by Category</h1>
            <p class="page-subtitle">Find lost and found items in specific categories</p>
        </div>
    </div>

    <!-- Stats Banner -->
    <div class="stats-banner mb-5">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['total_lost'] }}</div>
                    <div class="stat-label">Lost Items</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['total_found'] }}</div>
                    <div class="stat-label">Found Items</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['total_resolved'] }}</div>
                    <div class="stat-label">Successfully Reunited</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="categories-section mb-5">
        <div class="categories-grid">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" class="category-card">
                    <div class="category-icon">
                        @if($category->icon)
                            <img src="{{ $category->icon_url }}" alt="{{ $category->name }}">
                        @else
                            <i class="fas fa-box"></i>
                        @endif
                    </div>
                    <div class="category-content">
                        <h5 class="category-name">{{ $category->name }}</h5>
                        <p class="category-description">{{ $category->description }}</p>
                        <div class="category-stats">
                            <span class="stat-badge lost">
                                {{ $category->items->where('status', 'lost')->count() }} Lost
                            </span>
                            <span class="stat-badge found">
                                {{ $category->items->where('status', 'found')->count() }} Found
                            </span>
                        </div>
                    </div>
                    <div class="category-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Recent Items Preview -->
    @if($recent_items->count() > 0)
        <div class="recent-preview">
            <div class="section-header mb-4">
                <h3>Recent Items</h3>
                <a href="{{ route('items.index') }}" class="btn btn-outline-primary">View All Items</a>
            </div>
            
            <div class="preview-grid">
                @foreach($recent_items as $item)
                    <div class="preview-card">
                        <a href="{{ route('items.show', $item->id) }}" class="preview-link">
                            <div class="preview-image">
                                <img src="{{ $item->first_photo }}" alt="{{ $item->title }}">
                                <div class="preview-status status-{{ $item->status }}">
                                    {{ $item->status_label }}
                                </div>
                            </div>
                            <div class="preview-content">
                                <h6 class="preview-title">{{ Str::limit($item->title, 40) }}</h6>
                                <div class="preview-meta">
                                    <span class="meta-category">{{ $item->category->name }}</span>
                                    <span class="meta-location">{{ $item->location->city }}</span>
                                </div>
                                <div class="preview-time">{{ $item->created_at->diffForHumans() }}</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- CTA Section -->
    <div class="cta-section">
        <div class="cta-card">
            <div class="cta-content">
                <h3>Can't find what you're looking for?</h3>
                <p>Post your lost or found item to help connect with the community</p>
            </div>
            <div class="cta-actions">
                @auth
                    <a href="{{ route('items.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>Post Item
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Join Now
                    </a>
                @endauth
                <a href="{{ route('items.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-search me-2"></i>Browse All
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Page Header */
.page-header {
    text-align: center;
    margin-bottom: 3rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 1rem;
}

.page-subtitle {
    font-size: 1.2rem;
    color: #6c757d;
    margin-bottom: 0;
}

/* Stats Banner */
.stats-banner {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.stat-item {
    padding: 1rem;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2563eb;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6c757d;
    font-weight: 500;
    font-size: 1rem;
}

/* Categories Grid */
.categories-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
}

.category-card {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 12px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.category-card:hover {
    background: #e3f2fd;
    border-color: #2563eb;
    transform: translateY(-2px);
    text-decoration: none;
    color: inherit;
    box-shadow: 0 4px 16px rgba(37, 99, 235, 0.1);
}

.category-icon {
    width: 80px;
    height: 80px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.category-icon img {
    width: 48px;
    height: 48px;
    object-fit: contain;
}

.category-icon i {
    font-size: 2rem;
    color: #2563eb;
}

.category-content {
    flex: 1;
    min-width: 0;
}

.category-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.5rem;
}

.category-description {
    color: #6c757d;
    font-size: 0.9rem;
    line-height: 1.4;
    margin-bottom: 1rem;
}

.category-stats {
    display: flex;
    gap: 0.75rem;
}

.stat-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-badge.lost {
    background: #fff3cd;
    color: #856404;
}

.stat-badge.found {
    background: #d1eddf;
    color: #155724;
}

.category-arrow {
    color: #6c757d;
    font-size: 1.2rem;
    transition: transform 0.2s;
}

.category-card:hover .category-arrow {
    transform: translateX(4px);
    color: #2563eb;
}

/* Recent Preview */
.recent-preview {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-header h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #212529;
    margin-bottom: 0;
}

.preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.preview-card {
    background: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.2s;
}

.preview-card:hover {
    transform: translateY(-2px);
}

.preview-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.preview-link:hover {
    text-decoration: none;
    color: inherit;
}

.preview-image {
    position: relative;
    height: 150px;
    overflow: hidden;
}

.preview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-status {
    position: absolute;
    top: 8px;
    right: 8px;
    padding: 4px 8px;
    border-radius: 12px;
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

.preview-content {
    padding: 1rem;
}

.preview-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #212529;
}

.preview-meta {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    font-size: 0.8rem;
    color: #6c757d;
}

.preview-time {
    font-size: 0.75rem;
    color: #6c757d;
}

/* CTA Section */
.cta-section {
    margin-top: 3rem;
}

.cta-card {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    color: white;
    border-radius: 12px;
    padding: 3rem 2rem;
    text-align: center;
    box-shadow: 0 4px 16px rgba(37, 99, 235, 0.3);
}

.cta-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.cta-content p {
    font-size: 1.1rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.cta-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.cta-actions .btn {
    min-width: 150px;
}

.btn-outline-primary {
    border-color: white;
    color: white;
}

.btn-outline-primary:hover {
    background: white;
    color: #2563eb;
    border-color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .categories-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .category-card {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .category-stats {
        justify-content: center;
    }
    
    .preview-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .cta-card {
        padding: 2rem 1rem;
    }
    
    .cta-content h3 {
        font-size: 1.5rem;
    }
    
    .cta-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .cta-actions .btn {
        width: 100%;
        max-width: 300px;
    }
}

@media (max-width: 576px) {
    .stats-banner .row {
        text-align: center;
    }
    
    .stat-number {
        font-size: 2rem;
    }
}
</style>
@endpush