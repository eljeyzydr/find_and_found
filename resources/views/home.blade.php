@extends('layouts.app')

@section('title', 'Find & Found - Platform Barang Hilang & Ditemukan')
@section('description', 'Platform terpercaya untuk membantu menghubungkan orang yang kehilangan barang dengan yang menemukannya. Cari barang hilang atau laporkan barang temuan dengan mudah.')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-content">
                    <h1 class="text-primary hero-title mb-4">
                        Lost something?<br>
                        <span class="text-primary">Found something?</span>
                    </h1>
                    <p class="hero-subtitle mb-4" style="color: #212529" >
                        Connect with your community to reunite lost items with their owners. 
                        Every item has a story, every find brings hope.
                    </p>
                    <div class="hero-actions">
                        <a href="{{ route('items.index') }}" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-search me-2"></i>Browse Lost Items
                        </a>
                        @auth
                            <a href="{{ route('items.create') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-heart me-2"></i>Post Found Item
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-heart me-2"></i>Post Found Item
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            <!-- Ganti bagian hero-illustration dengan kode ini -->
<div class="col-lg-5">
    <div class="hero-illustration">
        <div class="stats-bubble">
            <i class="fas fa-check-circle text-success me-2"></i>
            {{ $stats['resolved_items'] }} items found today
        </div>
        
        <!-- Main Illustration -->
        <div class="illustration-content">
            <svg viewBox="0 0 400 300" class="main-illustration">
                <!-- Background Circle -->
                <defs>
                    <linearGradient id="bgGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#e3f2fd;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#f3e5f5;stop-opacity:1" />
                    </linearGradient>
                    <linearGradient id="phoneGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#2196f3;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#1976d2;stop-opacity:1" />
                    </linearGradient>
                    <linearGradient id="walletGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#4caf50;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#388e3c;stop-opacity:1" />
                    </linearGradient>
                    <linearGradient id="keyGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#ff9800;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#f57c00;stop-opacity:1" />
                    </linearGradient>
                </defs>
                
                <!-- Background -->
                <ellipse cx="200" cy="150" rx="180" ry="120" fill="url(#bgGradient)" opacity="0.3"/>
                
                <!-- People (simplified circles with avatars) -->
                <!-- Person 1 -->
                <circle cx="120" cy="80" r="25" fill="#1976d2" stroke="#fff" stroke-width="3"/>
                <circle cx="120" cy="80" r="20" fill="#e3f2fd"/>
                <circle cx="120" cy="75" r="8" fill="#1976d2"/>
                <path d="M105 85 Q120 95 135 85" stroke="#1976d2" stroke-width="2" fill="none"/>
                
                <!-- Person 2 -->
                <circle cx="280" cy="100" r="25" fill="#e91e63" stroke="#fff" stroke-width="3"/>
                <circle cx="280" cy="100" r="20" fill="#fce4ec"/>
                <circle cx="280" cy="95" r="8" fill="#e91e63"/>
                <path d="M265 105 Q280 115 295 105" stroke="#e91e63" stroke-width="2" fill="none"/>
                
                <!-- Person 3 -->
                <circle cx="200" cy="220" r="25" fill="#4caf50" stroke="#fff" stroke-width="3"/>
                <circle cx="200" cy="220" r="20" fill="#e8f5e8"/>
                <circle cx="200" cy="215" r="8" fill="#4caf50"/>
                <path d="M185 225 Q200 235 215 225" stroke="#4caf50" stroke-width="2" fill="none"/>
                
                <!-- Lost Items -->
                <!-- Phone -->
                <rect x="60" y="40" width="20" height="35" rx="8" fill="url(#phoneGradient)" stroke="#fff" stroke-width="2"/>
                <rect x="63" y="45" width="14" height="20" fill="#e3f2fd"/>
                <circle cx="70" cy="70" r="3" fill="#e3f2fd"/>
                
                <!-- Wallet -->
                <ellipse cx="320" cy="180" rx="18" ry="12" fill="url(#walletGradient)" stroke="#fff" stroke-width="2"/>
                <rect x="310" y="175" width="20" height="3" fill="#2e7d32"/>
                <rect x="312" y="178" width="16" height="2" fill="#66bb6a"/>
                
                <!-- Keys -->
                <g transform="translate(50,200)">
                    <circle cx="10" cy="10" r="8" fill="url(#keyGradient)" stroke="#fff" stroke-width="2"/>
                    <circle cx="10" cy="10" r="4" fill="none" stroke="#fff" stroke-width="1"/>
                    <rect x="15" y="8" width="15" height="4" fill="url(#keyGradient)"/>
                    <rect x="28" y="6" width="3" height="3" fill="url(#keyGradient)"/>
                    <rect x="28" y="11" width="3" height="3" fill="url(#keyGradient)"/>
                </g>
                
                <!-- Connection Lines -->
                <path d="M120 80 Q180 60 280 100" stroke="#2196f3" stroke-width="2" fill="none" opacity="0.5" stroke-dasharray="5,5"/>
                <path d="M280 100 Q240 160 200 220" stroke="#2196f3" stroke-width="2" fill="none" opacity="0.5" stroke-dasharray="5,5"/>
                <path d="M120 80 Q160 150 200 220" stroke="#2196f3" stroke-width="2" fill="none" opacity="0.5" stroke-dasharray="5,5"/>
                
                <!-- Search/Match Icons -->
                <g transform="translate(150,130)">
                    <circle cx="15" cy="15" r="12" fill="#fff" stroke="#2196f3" stroke-width="2"/>
                    <circle cx="15" cy="15" r="6" fill="none" stroke="#2196f3" stroke-width="2"/>
                    <path d="M25 25 L30 30" stroke="#2196f3" stroke-width="2"/>
                </g>
                
                <!-- Heart/Match Icon -->
                <g transform="translate(180,90)">
                    <path d="M12,5.5 C9,2.5 5,4 5,8 C5,12 12,19 12,19 S19,12 19,8 C19,4 15,2.5 12,5.5 Z" fill="#e91e63" opacity="0.8"/>
                </g>
            </svg>
        </div>
        
        <div class="nearby-matches">
            <i class="fas fa-map-marker-alt text-warning me-2"></i>
            Nearby matches
        </div>
    </div>
</div>
        </div>
    </div>
</section>

<!-- Quick Search -->
<section class="search-section py-4 bg-light">
    <div class="container">
        <form action="{{ route('search') }}" method="GET" class="search-form">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <input type="text" class="form-control form-control-lg" name="q" 
                           placeholder="Search lost or found items..." value="{{ request('q') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="col-md-4 text-end">
                    <div class="stats-mini">
                        <span class="stat-item">
                            <strong>{{ number_format($stats['total_items']) }}</strong>
                            <small>Items Reunited</small>
                        </span>
                        <span class="stat-item">
                            <strong>{{ number_format($stats['active_users']) }}</strong>
                            <small>Active Users</small>
                        </span>
                        <span class="stat-item">
                            <strong>{{ number_format($stats['cities_covered']) }}</strong>
                            <small>Cities Covered</small>
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Recent Posts Section -->
<section class="recent-posts py-5">
    <div class="container">
        <div class="section-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="section-title">Recent Posts</h2>
                    <p class="section-subtitle">{{ $recent_lost->count() + $recent_found->count() }} items • Updated just now</p>
                </div>
                <div class="col-md-6 text-md-end">
                    @auth
                        <a href="{{ route('items.create') }}" class="btn btn-primary">
                            Post New Item
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="tabs">
                    <span class="tab-label">Show:</span>
                    <a href="{{ route('items.index') }}" class="tab-item active">All Items</a>
                    <a href="{{ route('items.index', ['status' => 'lost']) }}" class="tab-item">Lost</a>
                    <a href="{{ route('items.index', ['status' => 'found']) }}" class="tab-item">Found</a>
                </div>
                <div class="view-controls">
                    <select class="form-select form-select-sm">
                        <option>Newest First</option>
                        <option>Oldest First</option>
                        <option>Most Viewed</option>
                    </select>
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-filter"></i> Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Items Grid -->
        <div class="items-grid">
            <div class="row g-4">
                @foreach($recent_lost->take(2)->merge($recent_found->take(2)) as $item)
                    <div class="col-lg-6">
                        <div class="item-card">
                            <div class="item-image">
                                <img src="{{ $item->first_photo }}" alt="{{ $item->title }}">
                                <div class="item-status status-{{ $item->status }}">
                                    {{ $item->status_label }}
                                </div>
                            </div>
                            <div class="item-content">
                                <h5 class="item-title">{{ $item->title }}</h5>
                                <p class="item-description">{{ Str::limit($item->description, 100) }}</p>
                                <div class="item-meta">
                                    <span class="meta-location">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $item->location->city }}
                                    </span>
                                    <span class="meta-time">{{ $item->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
@if($popular_categories->count() > 0)
<section class="categories-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Browse by Category</h2>
            <p class="section-subtitle">Find items in specific categories</p>
        </div>

        <div class="categories-grid">
            <div class="row g-4">
                @foreach($popular_categories->take(6) as $category)
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="{{ route('categories.show', $category->slug) }}" class="category-card">
                            <div class="category-icon">
                                <i class="fas fa-{{ $category->icon ?? 'box' }}"></i>
                            </div>
                            <h6 class="category-name">{{ $category->name }}</h6>
                            <span class="category-count">{{ $category->items_count }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
@endsection

@push('styles')
<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
    color: #2563eb;
    min-height: 70vh;
    display: flex;
    align-items: center;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    line-height: 1.2;
}

.hero-subtitle {
    font-size: 1.25rem;
    opacity: 0.9;
    max-width: 500px;
}

.hero-illustration {
    position: relative;
    height: 400px;
}

.stats-bubble {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(255, 255, 255, 0.95);
    color: #333;
    padding: 10px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.nearby-matches {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: rgba(255, 255, 255, 0.95);
    color: #333;
    padding: 8px 12px;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 500;
}

.people-illustration {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

/* Search Section */
.search-section {
    border-bottom: 1px solid #dee2e6;
}

.stats-mini {
    display: flex;
    gap: 2rem;
}

.stat-item {
    display: flex;
    flex-direction: column;
    text-align: center;
}

.stat-item strong {
    font-size: 1.5rem;
    color: #495057;
}

.stat-item small {
    color: #6c757d;
    font-size: 0.8rem;
}

/* Recent Posts */
.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.5rem;
}

.section-subtitle {
    color: #6c757d;
    margin-bottom: 0;
}

/* Filter Tabs */
.filter-tabs {
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 1rem;
}

.tabs {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.tab-label {
    font-weight: 500;
    color: #6c757d;
}

.tab-item {
    padding: 8px 16px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    text-decoration: none;
    color: #495057;
    font-weight: 500;
    transition: all 0.2s;
}

.tab-item.active,
.tab-item:hover {
    background: #2563eb;
    color: white;
    border-color: #2563eb;
}

.view-controls {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

/* Item Cards */
.item-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
    text-decoration: none;
    color: inherit;
    display: block;
}

.item-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
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
}

.item-status {
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
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

.item-content {
    padding: 1.5rem;
}

.item-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #212529;
}

.item-description {
    color: #6c757d;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.item-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
    color: #6c757d;
}

/* Categories */
.categories-grid .category-card {
    display: block;
    text-align: center;
    padding: 2rem 1rem;
    background: white;
    border-radius: 12px;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.categories-grid .category-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    color: inherit;
}

.category-icon {
    font-size: 2rem;
    color: #2563eb;
    margin-bottom: 1rem;
}

.category-name {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #212529;
}

.category-count {
    color: #6c757d;
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .stats-mini {
        flex-direction: column;
        gap: 1rem;
    }
    
    .tabs {
        flex-wrap: wrap;
    }
    
    .view-controls {
        margin-top: 1rem;
    }
}
</style>
@endpush