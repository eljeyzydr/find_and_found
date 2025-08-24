@extends('layouts.admin')

@section('title', 'Edit Category - Admin Panel')
@section('page-title', 'Edit Category')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Category Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Brief description of this category">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="icon" class="form-label">Category Icon</label>
                        
                        @if($category->icon)
                            <div class="current-icon mb-2">
                                <label class="form-label text-muted small">Current Icon:</label>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $category->icon_url }}" alt="Current Icon" 
                                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;" 
                                         class="me-2">
                                    <small class="text-muted">{{ $category->icon }}</small>
                                </div>
                            </div>
                        @endif
                        
                        <input type="file" class="form-control @error('icon') is-invalid @enderror" 
                               id="icon" name="icon" accept="image/*">
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Upload a new icon to replace current one (JPG, PNG, GIF, SVG - Max 1MB)
                            @if($category->icon)
                                <br><small class="text-warning">Leave empty to keep current icon</small>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Category
                            </label>
                        </div>
                        <div class="form-text">Active categories will be visible to users</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Categories
                        </a>
                        <div>
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-save me-2"></i>Update Category
                            </button>
                            <button type="button" class="btn btn-danger" onclick="deleteCategory()">
                                <i class="fas fa-trash me-2"></i>Delete
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Hidden Delete Form -->
                <form id="deleteForm" action="{{ route('admin.categories.destroy', $category->id) }}" 
                      method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Preview Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Preview</h5>
            </div>
            <div class="card-body">
                <div id="categoryPreview" class="text-center">
                    <div class="icon-preview mb-3">
                        <div class="category-icon-placeholder">
                            @if($category->icon)
                                <img src="{{ $category->icon_url }}" alt="Current Icon" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                            @else
                                <i class="fas fa-image fa-2x text-muted"></i>
                            @endif
                        </div>
                    </div>
                    <h6 id="namePreview">{{ $category->name }}</h6>
                    <p id="descriptionPreview" class="text-muted">
                        {{ $category->description ?: 'No description provided' }}
                    </p>
                    <div class="status-preview">
                        <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}" id="statusPreview">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Stats -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Category Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="stat-item">
                            <div class="stat-number">{{ $category->items()->count() }}</div>
                            <div class="stat-label">Total Items</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-item">
                            <div class="stat-number">{{ $category->items()->active()->count() }}</div>
                            <div class="stat-label">Active Items</div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <div class="stat-item">
                            <div class="stat-number text-warning">{{ $category->items()->lost()->count() }}</div>
                            <div class="stat-label">Lost</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-item">
                            <div class="stat-number text-success">{{ $category->items()->found()->count() }}</div>
                            <div class="stat-label">Found</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Items -->
        @if($category->items()->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Recent Items</h5>
                </div>
                <div class="card-body">
                    @foreach($category->items()->latest()->take(3)->get() as $item)
                        <div class="d-flex align-items-center mb-2">
                            <img src="{{ $item->first_photo }}" alt="{{ $item->title }}" 
                                 style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;" 
                                 class="me-2">
                            <div class="flex-grow-1">
                                <div class="small fw-bold">{{ Str::limit($item->title, 25) }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    {{ $item->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <span class="badge bg-{{ $item->status === 'lost' ? 'warning' : 'success' }} badge-sm">
                                {{ $item->status_label }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Live preview updates
document.getElementById('name').addEventListener('input', function() {
    const namePreview = document.getElementById('namePreview');
    namePreview.textContent = this.value || 'Category Name';
});

document.getElementById('description').addEventListener('input', function() {
    const descriptionPreview = document.getElementById('descriptionPreview');
    descriptionPreview.textContent = this.value || 'No description provided';
});

document.getElementById('is_active').addEventListener('change', function() {
    const statusPreview = document.getElementById('statusPreview');
    statusPreview.textContent = this.checked ? 'Active' : 'Inactive';
    statusPreview.className = this.checked ? 'badge bg-success' : 'badge bg-secondary';
});

// Icon preview for new uploads
document.getElementById('icon').addEventListener('change', function() {
    const file = this.files[0];
    const iconPlaceholder = document.querySelector('.category-icon-placeholder');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            iconPlaceholder.innerHTML = `<img src="${e.target.result}" alt="Preview" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">`;
        };
        reader.readAsDataURL(file);
    }
});

// Delete confirmation
function deleteCategory() {
    if (confirm('Are you sure you want to delete this category?\n\nThis action cannot be undone. Make sure no items are using this category.')) {
        document.getElementById('deleteForm').submit();
    }
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    if (!name) {
        e.preventDefault();
        alert('Category name is required!');
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
    
    // Reset on error (this won't work for server errors, but good for client-side)
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }, 10000);
});
</script>
@endpush

@push('styles')
<style>
.category-icon-placeholder {
    width: 80px;
    height: 80px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    border: 2px dashed #dee2e6;
}

#categoryPreview {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.stat-item {
    padding: 0.5rem 0;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: #495057;
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.current-icon {
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 4px;
    border: 1px solid #e9ecef;
}

.badge-sm {
    font-size: 0.65rem;
}

.form-text {
    font-size: 0.85rem;
}

/* Loading state */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
@endpush