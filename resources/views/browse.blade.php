@extends('layouts.app')

@section('title', 'Browse Items - Find & Found')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="browse-header mb-5">
        <div class="text-center">
            <h1 class="browse-title">Browse Lost & Found Items</h1>
            <p class="browse-subtitle">Discover items from your community and help reunite them with their owners</p>
        </div>
        
        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="stat-card lost">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_lost'] }}</div>
                    <div class="stat-label">Lost Items</div>
                </div>
            </div>
            <div class="stat-card found">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_found'] }}</div>
                    <div class="stat-label">Found Items</div>
                </div>
            </div>
            <div class="stat-card resolved">
                <div class="stat-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_resolved'] }}</div>
                    <div class="stat-label">Reunited</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="search-filter-section mb-5">
        <form action="{{ route('search') }}" method="GET" class="search-form">
            <div class="search-main">
                <div class="search-input-group">
                    <input type="text" class="form-control" name="q" 
                           placeholder="Search for lost or found items..." 
                           value="{{ request('q') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            
            <div class="search-filters">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost Items</option>
                            <option value="found" {{ request('status') == 'found' ? 'selected' : '' }}>Found Items</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="category">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="city" 
                               placeholder="City..." value="{{ request('city') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-outline-primary w-100" onclick="getCurrentLocation()">
                            <i class="fas fa-crosshairs me-2"></i>Near Me
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Categories Grid -->
    <div class="categories-section mb-5">
        <div class="section-header">
            <h3>Browse by Category</h3>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">View All Categories</a>
        </div>
        
        <div class="categories-grid">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" class="category-card">
                    <div class="category-visual">
                        <div class="category-icon">
                            @if($category->icon)
                                <img src="{{ $category->icon_url }}" alt="{{ $category->name }}">
                            @else
                                <i class="fas fa-box"></i>
                            @endif
                        </div>
                        <div class="category-stats">
                            <span class="lost-count">{{ $category->items->where('status', 'lost')->count() }} Lost</span>
                            <span class="found-count">{{ $category->items->where('status', 'found')->count() }} Found</span>
                        </div>
                    </div>
                    <div class="category-info">
                        <h6 class="category-name">{{ $category->name }}</h6>
                        <p class="category-total">{{ $category->items_count ?? 0 }} total items</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Recent Items Preview -->
    <div class="recent-items-section">
        <div class="section-header">
            <h3>Recent Items</h3>
            <a href="{{ route('items.index') }}" class="btn btn-outline-primary">View All Items</a>
        </div>
        
        <div class="items-preview">
            @foreach($recent_items as $item)
                <div class="preview-item">
                    <a href="{{ route('items.show', $item->id) }}" class="preview-link">
                        <div class="preview-image">
                            <img src="{{ $item->first_photo }}" alt="{{ $item->title }}">
                            <div class="preview-status status-{{ $item->status }}">
                                {{ $item->status_label }}
                            </div>
                            @if($item->is_resolved)
                                <div class="resolved-indicator">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            @endif
                        </div>
                        <div class="preview-content">
                            <h6 class="preview-title">{{ Str::limit($item->title, 35) }}</h6>
                            <div class="preview-meta">
                                <span class="meta-category">{{ $item->category->name }}</span>
                                <span class="meta-location">{{ $item->location->city }}</span>
                            </div>
                            <div class="preview-stats">
                                <span class="stat-views">
                                    <i class="fas fa-eye"></i> {{ $item->views_count }}
                                </span>
                                <span class="stat-time">{{ $item->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Call to Action -->
    <div class="cta-section">
        <div class="cta-content">
            <h3>Help Make a Difference</h3>
            <p>Join our community and help reunite lost items with their owners. Every post brings hope.</p>
            <div class="cta-actions">
                @auth
                    <a href="{{ route('items.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>Post an Item
                    </a>
                    <a href="{{ route('items.index') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-search me-2"></i>Browse All Items
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Join Community
                    </a>
                    <a href="{{ route('items.index') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-search me-2"></i>Start Browsing
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Browse Header */
.browse-header {
    background: white;
    border-radius: 12px;
    padding: 3rem 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.browse-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 1rem;
}

.browse-subtitle {
    font-size: 1.2rem;
    color: #6c757d;
    margin-bottom: 2rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.stat-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-card.lost {
    background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
    color: #212529;
}

.stat-card.found {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.stat-card.resolved {
    background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
    color: white;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

/* Search & Filter Section */
.search-filter-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.search-main {
    margin-bottom: 1.5rem;
}

.search-input-group {
    display: flex;
    max-width: 600px;
    margin: 0 auto;
    gap: 0.5rem;
}

.search-input-group .form-control {
    border-radius: 25px;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
}

.search-input-group .btn {
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-filters {
    border-top: 1px solid #e9ecef;
    padding-top: 1.5rem;
}

/* Section Headers */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.section-header h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #212529;
    margin-bottom: 0;
}

/* Categories Section */
.categories-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.category-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.category-card:hover {
    background: #e3f2fd;
    border-color: #2563eb;
    transform: translateY(-2px);
    text-decoration: none;
    color: inherit;
}

.category-visual {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.category-icon {
    width: 60px;
    height: 60px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.category-icon img {
    width: 32px;
    height: 32px;
    object-fit: contain;
}

.category-icon i {
    font-size: 1.5rem;
    color: #2563eb;
}

.category-stats {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    font-size: 0.75rem;
}

.lost-count {
    background: #fff3cd;
    color: #856404;
    padding: 2px 6px;
    border-radius: 8px;
    text-align: center;
}

.found-count {
    background: #d1eddf;
    color: #155724;
    padding: 2px 6px;
    border-radius: 8px;
    text-align: center;
}

.category-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #212529;
}

.category-total {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0;
}

/* Recent Items Section */
.recent-items-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.items-preview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.preview-item {
    background: #f8f9fa;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.2s;
}

.preview-item:hover {
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
    height: 180px;
    overflow: hidden;
}

.preview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.preview-item:hover .preview-image img {
    transform: scale(1.05);
}

.preview-status {
    position: absolute;
    top: 8px;
    left: 8px;
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

.resolved-indicator {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 20px;
    height: 20px;
    background: #28a745;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.6rem;
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

.preview-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.75rem;
    color: #6c757d;
}

.stat-views {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* CTA Section */
.cta-section {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    color: white;
    border-radius: 12px;
    padding: 3rem 2rem;
    text-align: center;
    margin-top: 3rem;
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
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.cta-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.cta-actions .btn {
    min-width: 180px;
}

.btn-outline-light:hover {
    background: white;
    color: #2563eb;
}

/* Responsive */
@media (max-width: 768px) {
    .browse-title {
        font-size: 2rem;
    }
    
    .quick-stats {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .search-input-group {
        flex-direction: column;
        gap: 1rem;
    }
    
    .search-input-group .btn {
        border-radius: 25px;
        width: auto;
        height: auto;
        padding: 0.75rem 1.5rem;
    }
    
    .categories-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .items-preview {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
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
    .browse-header {
        padding: 2rem 1rem;
    }
    
    .search-filter-section {
        padding: 1.5rem;
    }
    
    .categories-section,
    .recent-items-section {
        padding: 1.5rem;
    }
    
    .cta-section {
        padding: 2rem 1rem;
    }
    
    .cta-content h3 {
        font-size: 1.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Get current location for search
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            // Add hidden inputs to form
            const form = document.querySelector('.search-form');
            
            // Remove existing coordinate inputs
            const existingLat = form.querySelector('input[name="latitude"]');
            const existingLng = form.querySelector('input[name="longitude"]');
            if (existingLat) existingLat.remove();
            if (existingLng) existingLng.remove();
            
            // Add new coordinate inputs
            const latInput = document.createElement('input');
            latInput.type = 'hidden';
            latInput.name = 'latitude';
            latInput.value = lat;
            form.appendChild(latInput);
            
            const lngInput = document.createElement('input');
            lngInput.type = 'hidden';
            lngInput.name = 'longitude';
            lngInput.value = lng;
            form.appendChild(lngInput);
            
            // Set radius
            const radiusInput = document.createElement('input');
            radiusInput.type = 'hidden';
            radiusInput.name = 'radius';
            radiusInput.value = '10'; // 10km radius
            form.appendChild(radiusInput);
            
            // Update button text
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check me-2"></i>Location Set';
            button.classList.remove('btn-outline-primary');
            button.classList.add('btn-success');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-primary');
            }, 2000);
            
        }, function(error) {
            alert('Unable to get your location. Please enter your city manually.');
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

// Auto-submit search form when filters change
document.querySelectorAll('.search-form select').forEach(select => {
    select.addEventListener('change', function() {
        // Only auto-submit if there's a search term
        const searchInput = document.querySelector('.search-form input[name="q"]');
        if (searchInput.value.trim()) {
            document.querySelector('.search-form').submit();
        }
    });
});

// Enhanced search suggestions (could be implemented with AJAX)
const searchInput = document.querySelector('input[name="q"]');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        // TODO: Implement search suggestions
        // This would make AJAX calls to get suggestions based on input
    });
}

// Smooth scroll to sections
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
@endpush