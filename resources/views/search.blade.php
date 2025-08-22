@extends('layouts.app')

@section('title', 'Search Results - Find & Found')

@section('content')
<div class="container py-4">
    <!-- Search Header -->
    <div class="search-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="search-title">
                    @if(request('q'))
                        Search results for "{{ request('q') }}"
                    @else
                        Browse All Items
                    @endif
                </h1>
                <p class="search-subtitle">
                    {{ $items->total() }} items found
                    @if(request()->hasAny(['status', 'category', 'city']))
                        with your filters
                    @endif
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#advancedSearchModal">
                    <i class="fas fa-sliders-h me-2"></i>Advanced Search
                </button>
            </div>
        </div>
    </div>

    <!-- Search Filters -->
    <div class="search-filters mb-4">
        <form method="GET" action="{{ route('search') }}" id="searchForm">
            <div class="row g-3">
                <!-- Search Query -->
                <div class="col-md-4">
                    <div class="search-input">
                        <input type="text" class="form-control" name="q" value="{{ request('q') }}" 
                               placeholder="Search items..." id="searchQuery">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-md-2">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                        <option value="found" {{ request('status') == 'found' ? 'selected' : '' }}>Found</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div class="col-md-3">
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Location Filter -->
                <div class="col-md-2">
                    <input type="text" class="form-control" name="city" value="{{ request('city') }}" 
                           placeholder="City..." onchange="this.form.submit()">
                </div>

                <!-- Search Button -->
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Active Filters -->
    @if(request()->hasAny(['q', 'status', 'category', 'city', 'date_from', 'date_to']))
        <div class="active-filters mb-4">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="filter-label">Active Filters:</span>
                
                @if(request('q'))
                    <span class="filter-badge">
                        Search: "{{ request('q') }}"
                        <a href="{{ request()->fullUrlWithoutQuery('q') }}" class="remove-filter">×</a>
                    </span>
                @endif
                
                @if(request('status'))
                    <span class="filter-badge">
                        Status: {{ ucfirst(request('status')) }}
                        <a href="{{ request()->fullUrlWithoutQuery('status') }}" class="remove-filter">×</a>
                    </span>
                @endif
                
                @if(request('category'))
                    @php
                        $categoryName = $categories->find(request('category'))->name ?? 'Unknown';
                    @endphp
                    <span class="filter-badge">
                        Category: {{ $categoryName }}
                        <a href="{{ request()->fullUrlWithoutQuery('category') }}" class="remove-filter">×</a>
                    </span>
                @endif
                
                @if(request('city'))
                    <span class="filter-badge">
                        City: {{ request('city') }}
                        <a href="{{ request()->fullUrlWithoutQuery('city') }}" class="remove-filter">×</a>
                    </span>
                @endif
                
                <a href="{{ route('search') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-times me-1"></i>Clear All
                </a>
            </div>
        </div>
    @endif

    <!-- Sort & View Options -->
    <div class="search-controls mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="sort-options">
                <form method="GET" class="d-flex align-items-center gap-2">
                    @foreach(request()->except('sort') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <label class="form-label mb-0">Sort by:</label>
                    <select name="sort" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Viewed</option>
                        <option value="nearest" {{ request('sort') == 'nearest' ? 'selected' : '' }}>Nearest to Me</option>
                    </select>
                </form>
            </div>
            
            <div class="view-toggle">
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="view" id="gridView" checked>
                    <label class="btn btn-outline-secondary btn-sm" for="gridView">
                        <i class="fas fa-th"></i>
                    </label>
                    <input type="radio" class="btn-check" name="view" id="listView">
                    <label class="btn btn-outline-secondary btn-sm" for="listView">
                        <i class="fas fa-list"></i>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results -->
    <div class="search-results">
        <!-- Grid View -->
        <div id="gridResults" class="results-grid">
            @forelse($items as $item)
                <div class="result-card">
                    <a href="{{ route('items.show', $item->id) }}" class="card-link">
                        <div class="card-image">
                            <img src="{{ asset('storage/items/' . $photo) }}" alt="foto item" loading="lazy">
                            <div class="card-status status-{{ $item->status }}">
                                {{ $item->status_label }}
                            </div>
                            @if($item->is_resolved)
                                <div class="resolved-badge">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            @endif
                        </div>
                        <div class="card-content">
                            <h6 class="card-title">{{ $item->title }}</h6>
                            <p class="card-description">{{ Str::limit($item->description, 80) }}</p>
                            <div class="card-meta">
                                <div class="meta-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $item->location->city }}
                                </div>
                                <div class="meta-time">{{ $item->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="card-stats">
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
                    </a>
                </div>
            @empty
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4>No items found</h4>
                    <p>Try adjusting your search criteria or browse different categories</p>
                    <div class="no-results-actions">
                        <a href="{{ route('items.index') }}" class="btn btn-primary">Browse All Items</a>
                        @auth
                            <a href="{{ route('items.create') }}" class="btn btn-outline-primary">Post New Item</a>
                        @endauth
                    </div>
                </div>
            @endforelse
        </div>

        <!-- List View -->
        <div id="listResults" class="results-list d-none">
            @foreach($items as $item)
                <div class="result-item">
                    <div class="item-image">
                        <img src="{{ $item->first_photo }}" alt="{{ $item->title }}">
                        @foreach ($item->photo_urls as $photo)
    <img src="{{ $photo }}" alt="{{ $item->title }}">
@endforeach

                            {{ $item->status_label }}
                        </div>
                    </div>
                    <div class="item-content">
                        <div class="item-header">
                            <h5 class="item-title">
                                <a href="{{ route('items.show', $item->id) }}">{{ $item->title }}</a>
                            </h5>
                            <div class="item-meta">
                                <span class="meta-category">{{ $item->category->name }}</span>
                                <span class="meta-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $item->location->city }}
                                </span>
                                <span class="meta-time">{{ $item->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <p class="item-description">{{ Str::limit($item->description, 150) }}</p>
                        <div class="item-footer">
                            <div class="item-stats">
                                <span class="stat-item">
                                    <i class="fas fa-eye"></i>
                                    {{ $item->views_count }} views
                                </span>
                                <span class="stat-item">
                                    <i class="fas fa-comments"></i>
                                    {{ $item->comments_count }} comments
                                </span>
                            </div>
                            <a href="{{ route('items.show', $item->id) }}" class="btn btn-primary btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
        <div class="pagination-wrapper">
            {{ $items->withQueryString()->links() }}
        </div>
    @endif
</div>

<!-- Advanced Search Modal -->
<div class="modal fade" id="advancedSearchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Advanced Search</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('search') }}">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Keywords</label>
                            <input type="text" class="form-control" name="q" value="{{ request('q') }}" 
                                   placeholder="Search keywords...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Any Status</option>
                                <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost Items</option>
                                <option value="found" {{ request('status') == 'found' ? 'selected' : '' }}>Found Items</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <option value="">Any Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city" value="{{ request('city') }}" 
                                   placeholder="Enter city name...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Radius (km)</label>
                            <input type="number" class="form-control" name="radius" value="{{ request('radius', 10) }}" 
                                   min="1" max="100" placeholder="Search radius">
                            <div class="form-text">Distance from your location</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Viewed</option>
                                <option value="nearest" {{ request('sort') == 'nearest' ? 'selected' : '' }}>Nearest</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="{{ route('search') }}" class="btn btn-outline-primary">Clear All</a>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Search Header */
.search-header {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.search-title {
    font-size: 2rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.5rem;
}

.search-subtitle {
    color: #6c757d;
    margin-bottom: 0;
}

/* Search Filters */
.search-filters {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.search-input {
    position: relative;
}

.search-input input {
    padding-right: 2.5rem;
}

.search-icon {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

/* Active Filters */
.active-filters {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
}

.filter-label {
    font-weight: 600;
    color: #495057;
}

.filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.75rem;
    background: #e3f2fd;
    color: #1565c0;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 500;
}

.remove-filter {
    color: #1565c0;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.1rem;
    line-height: 1;
}

.remove-filter:hover {
    color: #0d47a1;
}

/* Search Controls */
.search-controls {
    background: white;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Results Grid */
.results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.result-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.result-card:hover {
    transform: translateY(-2px);
}

.card-link {
    display: block;
    text-decoration: none;
    color: inherit;
}

.card-link:hover {
    text-decoration: none;
    color: inherit;
}

.card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card-status {
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

.card-content {
    padding: 1.25rem;
}

.card-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #212529;
}

.card-description {
    color: #6c757d;
    margin-bottom: 1rem;
    line-height: 1.4;
    font-size: 0.9rem;
}

.card-meta {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    font-size: 0.8rem;
    color: #6c757d;
}

.meta-location {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.card-stats {
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

/* Results List */
.results-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.result-item {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    gap: 1.5rem;
}

.result-item .item-image {
    width: 150px;
    height: 120px;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    flex-shrink: 0;
}

.result-item .item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.result-item .item-status {
    position: absolute;
    top: 8px;
    left: 8px;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

.item-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.item-header {
    margin-bottom: 0.75rem;
}

.item-title {
    margin-bottom: 0.5rem;
}

.item-title a {
    color: #212529;
    text-decoration: none;
    font-weight: 600;
}

.item-title a:hover {
    color: #2563eb;
    text-decoration: none;
}

.item-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.8rem;
    color: #6c757d;
}

.item-description {
    color: #495057;
    line-height: 1.5;
    margin-bottom: auto;
}

.item-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.item-stats {
    display: flex;
    gap: 1rem;
}

/* No Results */
.no-results {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.no-results-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1.5rem;
}

.no-results h4 {
    color: #495057;
    margin-bottom: 1rem;
}

.no-results p {
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

.no-results-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

/* Responsive */
@media (max-width: 768px) {
    .search-header {
        padding: 1.5rem;
    }
    
    .search-header .row {
        text-align: center;
    }
    
    .search-title {
        font-size: 1.5rem;
    }
    
    .search-filters {
        padding: 1rem;
    }
    
    .search-controls .d-flex {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch !important;
    }
    
    .sort-options .d-flex {
        justify-content: center;
    }
    
    .view-toggle {
        align-self: center;
    }
    
    .results-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .result-item {
        flex-direction: column;
        gap: 1rem;
    }
    
    .result-item .item-image {
        width: 100%;
        height: 200px;
    }
    
    .item-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .no-results-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .no-results-actions .btn {
        min-width: 200px;
    }
    
    .active-filters .d-flex {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .filter-badge {
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 576px) {
    .search-filters .row {
        row-gap: 0.75rem;
    }
    
    .search-filters .col-md-1 {
        grid-column: 1 / -1;
    }
    
    .search-filters .btn {
        width: 100%;
    }
    
    .card-content {
        padding: 1rem;
    }
    
    .result-item {
        padding: 1rem;
    }
}

/* Loading States */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Search Input Focus */
.search-input input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
}

.search-input input:focus + .search-icon {
    color: #2563eb;
}

/* Form Select Styling */
.form-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
}

/* Modal Customization */
.modal-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.modal-title {
    font-weight: 600;
}

/* Advanced Search Form */
#advancedSearchModal .form-label {
    font-weight: 500;
    color: #495057;
}

#advancedSearchModal .form-text {
    color: #6c757d;
    font-size: 0.8rem;
}
</style>
@endpush

@push('scripts')
<script>
// View Toggle
document.getElementById('gridView').addEventListener('change', function() {
    if (this.checked) {
        document.getElementById('gridResults').classList.remove('d-none');
        document.getElementById('listResults').classList.add('d-none');
        localStorage.setItem('searchView', 'grid');
    }
});

document.getElementById('listView').addEventListener('change', function() {
    if (this.checked) {
        document.getElementById('gridResults').classList.add('d-none');
        document.getElementById('listResults').classList.remove('d-none');
        localStorage.setItem('searchView', 'list');
    }
});

// Load saved view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('searchView');
    if (savedView === 'list') {
        document.getElementById('listView').checked = true;
        document.getElementById('gridResults').classList.add('d-none');
        document.getElementById('listResults').classList.remove('d-none');
    }
});

// Auto-submit search form on Enter
document.getElementById('searchQuery').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('searchForm').submit();
    }
});

// Search suggestions (basic implementation)
let searchTimeout;
document.getElementById('searchQuery').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();
    
    if (query.length >= 2) {
        searchTimeout = setTimeout(() => {
            // TODO: Implement search suggestions
            showSearchSuggestions(query);
        }, 300);
    } else {
        hideSearchSuggestions();
    }
});

function showSearchSuggestions(query) {
    // TODO: Fetch search suggestions from API
    console.log('Fetching suggestions for:', query);
}

function hideSearchSuggestions() {
    const suggestions = document.getElementById('searchSuggestions');
    if (suggestions) {
        suggestions.remove();
    }
}

// Clear individual filters
document.querySelectorAll('.remove-filter').forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = this.href;
    });
});

// Form auto-submit for filters
document.querySelectorAll('select[onchange]').forEach(function(select) {
    select.addEventListener('change', function() {
        // Add loading state
        this.classList.add('loading');
        this.form.submit();
    });
});

// Infinite scroll (optional feature)
let isLoading = false;
let currentPage = {{ $items->currentPage() }};
let lastPage = {{ $items->lastPage() }};

function loadMoreResults() {
    if (isLoading || currentPage >= lastPage) return;
    
    isLoading = true;
    currentPage++;
    
    const searchParams = new URLSearchParams(window.location.search);
    searchParams.set('page', currentPage);
    
    fetch(`{{ route('search') }}?${searchParams.toString()}`)
        .then(response => response.text())
        .then(html => {
            // Parse the HTML and extract new results
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newResults = doc.querySelectorAll('.result-card');
            
            // Append new results to current view
            const resultsContainer = document.getElementById('gridResults');
            newResults.forEach(result => {
                resultsContainer.appendChild(result);
            });
            
            isLoading = false;
        })
        .catch(error => {
            console.error('Error loading more results:', error);
            isLoading = false;
        });
}

// Optional: Enable infinite scroll
function enableInfiniteScroll() {
    window.addEventListener('scroll', function() {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 1000) {
            loadMoreResults();
        }
    });
}

// Uncomment to enable infinite scroll
// enableInfiniteScroll();

// Track search analytics
function trackSearch() {
    const searchData = {
        query: '{{ request("q") }}',
        status: '{{ request("status") }}',
        category: '{{ request("category") }}',
        city: '{{ request("city") }}',
        results_count: {{ $items->total() }},
        timestamp: new Date().toISOString()
    };
    
    // TODO: Send to analytics service
    console.log('Search tracked:', searchData);
}

// Track search on page load
if ('{{ request()->hasAny(["q", "status", "category", "city"]) }}') {
    trackSearch();
}

// Handle back button
window.addEventListener('popstate', function(e) {
    // Reload page to ensure proper state
    window.location.reload();
});

// Form validation for advanced search
document.querySelector('#advancedSearchModal form').addEventListener('submit', function(e) {
    const dateFrom = this.querySelector('[name="date_from"]').value;
    const dateTo = this.querySelector('[name="date_to"]').value;
    
    if (dateFrom && dateTo && dateFrom > dateTo) {
        e.preventDefault();
        alert('Date from cannot be later than date to');
        return false;
    }
    
    const radius = this.querySelector('[name="radius"]').value;
    if (radius && (radius < 1 || radius > 100)) {
        e.preventDefault();
        alert('Radius must be between 1 and 100 kilometers');
        return false;
    }
});

// Highlight search terms in results
function highlightSearchTerms() {
    const searchQuery = '{{ request("q") }}';
    if (!searchQuery) return;
    
    const terms = searchQuery.toLowerCase().split(' ');
    const elements = document.querySelectorAll('.card-title, .card-description, .item-title, .item-description');
    
    elements.forEach(element => {
        let html = element.innerHTML;
        terms.forEach(term => {
            if (term.length > 2) { // Only highlight terms longer than 2 characters
                const regex = new RegExp(`(${term})`, 'gi');
                html = html.replace(regex, '<mark>$1</mark>');
            }
        });
        element.innerHTML = html;
    });
}

// Highlight search terms on page load
document.addEventListener('DOMContentLoaded', highlightSearchTerms);
</script>
@endpush