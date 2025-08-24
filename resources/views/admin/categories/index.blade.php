@extends('layouts.admin')

@section('title', 'Category Management - Admin Panel')
@section('page-title', 'Category Management')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ $categories->total() }}</h3>
                        <p class="mb-0 opacity-75">Total Categories</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-tags fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stats-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ $categories->where('is_active', true)->count() }}</h3>
                        <p class="mb-0 opacity-75">Active Categories</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stats-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ $categories->where('is_active', false)->count() }}</h3>
                        <p class="mb-0 opacity-75">Inactive Categories</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-eye-slash fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stats-card danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-1">{{ $categories->sum('items_count') }}</h3>
                        <p class="mb-0 opacity-75">Total Items</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-box fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions Bar -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div class="search-section">
                <form method="GET" class="d-flex gap-3">
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                           placeholder="Search categories..." style="width: 300px;">
                    <select class="form-select" name="status" style="width: 150px;">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                </form>
            </div>
            <div class="action-buttons">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Add Category
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Categories Grid -->
<div class="row">
    @forelse($categories as $category)
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card category-card {{ !$category->is_active ? 'inactive' : '' }}">
                <div class="card-body">
                    <div class="category-header d-flex justify-content-between align-items-start mb-3">
                        <div class="category-icon">
                            @if($category->icon)
                                <img src="{{ $category->icon_url }}" alt="{{ $category->name }}" width="48" height="48">
                            @else
                                <i class="fas fa-tag fa-2x text-primary"></i>
                            @endif
                        </div>
                        <div class="category-status">
                            <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    
                    <h5 class="category-title">{{ $category->name }}</h5>
                    <p class="category-description text-muted">
                        {{ $category->description ?: 'No description provided' }}
                    </p>
                    
                    <div class="category-stats mb-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-item">
                                    <div class="stat-number">{{ $category->items_count ?: 0 }}</div>
                                    <div class="stat-label">Items</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <div class="stat-number">{{ $category->items->where('status', 'lost')->count() }}</div>
                                    <div class="stat-label">Lost</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <div class="stat-number">{{ $category->items->where('status', 'found')->count() }}</div>
                                    <div class="stat-label">Found</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="category-meta mb-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            Created {{ $category->created_at->diffForHumans() }}
                        </small>
                        <br>
                        <small class="text-muted">
                            <i class="fas fa-link me-1"></i>
                            Slug: {{ $category->slug }}
                        </small>
                    </div>
                    
                    <div class="category-actions d-flex gap-2">
                        <a href="{{ route('categories.show', $category->slug) }}" 
                           class="btn btn-outline-primary btn-sm flex-fill" target="_blank">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" 
                           class="btn btn-outline-secondary btn-sm flex-fill">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.categories.toggle-active', $category->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning btn-sm" 
                                    title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}">
                                <i class="fas fa-{{ $category->is_active ? 'eye-slash' : 'eye' }}"></i>
                            </button>
                        </form>
                        @if($category->items_count == 0)
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" 
                                  class="d-inline" onsubmit="return confirm('Delete this category?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No categories found</h5>
                    <p class="text-muted">
                        @if(request()->has('search') || request()->has('status'))
                            No categories match your search criteria
                        @else
                            No categories have been created yet
                        @endif
                    </p>
                    @if(!request()->has('search') && !request()->has('status'))
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Create First Category
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($categories->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $categories->withQueryString()->links() }}
    </div>
@endif
@endsection

@push('styles')
<style>
.category-card {
    transition: all 0.2s;
    height: 100%;
}

.category-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

.category-card.inactive {
    opacity: 0.7;
    background-color: #f8f9fa;
}

.category-card.inactive .category-title {
    color: #6c757d;
}

.category-icon {
    width: 60px;
    height: 60px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.category-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #212529;
}

.category-description {
    font-size: 0.9rem;
    line-height: 1.4;
    margin-bottom: 1rem;
    min-height: 2.8rem;
}

.category-stats .stat-item {
    padding: 0.5rem 0;
}

.category-stats .stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2563eb;
    line-height: 1;
}

.category-stats .stat-label {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 500;
}

.category-meta {
    border-top: 1px solid #e9ecef;
    padding-top: 0.75rem;
}

.category-actions .btn {
    font-size: 0.8rem;
    padding: 0.375rem 0.5rem;
}

.search-section .form-control,
.search-section .form-select {
    height: calc(2.25rem + 2px);
}

@media (max-width: 768px) {
    .card-body .d-flex {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch !important;
    }
    
    .search-section form {
        flex-direction: column;
        gap: 0.75rem !important;
    }
    
    .search-section .form-control,
    .search-section .form-select {
        width: 100% !important;
    }
    
    .category-actions {
        flex-wrap: wrap;
    }
    
    .category-actions .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
}

@media (max-width: 576px) {
    .category-stats .row > div {
        margin-bottom: 0.5rem;
    }
    
    .category-stats .stat-number {
        font-size: 1.25rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Auto-submit form on select change
document.querySelector('select[name="status"]').addEventListener('change', function() {
    this.closest('form').submit();
});

// Confirm delete for categories with items
document.querySelectorAll('form[action*="destroy"]').forEach(form => {
    form.addEventListener('submit', function(e) {
        const categoryName = this.closest('.card').querySelector('.category-title').textContent;
        if (!confirm(`Are you sure you want to delete the category "${categoryName}"?`)) {
            e.preventDefault();
        }
    });
});

// Confirm toggle status
document.querySelectorAll('form[action*="toggle-active"]').forEach(form => {
    form.addEventListener('submit', function(e) {
        const categoryName = this.closest('.card').querySelector('.category-title').textContent;
        const isActive = this.closest('.card').querySelector('.badge').textContent.trim() === 'Active';
        const action = isActive ? 'deactivate' : 'activate';
        
        if (!confirm(`Are you sure you want to ${action} the category "${categoryName}"?`)) {
            e.preventDefault();
        }
    });
});

// Search form enhancement
const searchForm = document.querySelector('form');
const searchInput = document.querySelector('input[name="search"]');

searchInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchForm.submit();
    }
});

// Real-time search (optional, debounced)
let searchTimeout;
searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        // Uncomment to enable real-time search
        // searchForm.submit();
    }, 500);
});

// Category card interactions
document.querySelectorAll('.category-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.borderColor = '#2563eb';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.borderColor = '';
    });
});

// Toast notifications for actions
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 5000);
}

// Handle successful actions
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
    const type = urlParams.get('type');
    
    if (message) {
        showToast(message, type || 'success');
        
        // Clean URL
        const cleanUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString().replace(/[?&](message|type)=[^&]*/g, '').replace(/^&/, '') : '');
        window.history.replaceState({}, '', cleanUrl);
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey || e.metaKey) {
        switch(e.key) {
            case 'n':
                e.preventDefault();
                window.location.href = '{{ route("admin.categories.create") }}';
                break;
            case 'f':
                e.preventDefault();
                searchInput.focus();
                break;
        }
    }
});

// Category stats animation
function animateNumbers() {
    document.querySelectorAll('.stat-number').forEach(element => {
        const finalValue = parseInt(element.textContent);
        let currentValue = 0;
        const increment = Math.ceil(finalValue / 20);
        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= finalValue) {
                currentValue = finalValue;
                clearInterval(timer);
            }
            element.textContent = currentValue;
        }, 50);
    });
}

// Run animation on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(animateNumbers, 500);
});
</script>
@endpush