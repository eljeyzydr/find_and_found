@extends('layouts.admin')

@section('title', 'Add Category - Admin Panel')
@section('page-title', 'Add New Category')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Category Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Brief description of this category">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="icon" class="form-label">Category Icon</label>
                        <input type="file" class="form-control @error('icon') is-invalid @enderror" 
                               id="icon" name="icon" accept="image/*">
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Upload an icon for this category (JPG, PNG, GIF, SVG - Max 1MB)</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
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
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Preview</h5>
            </div>
            <div class="card-body">
                <div id="categoryPreview" class="text-center">
                    <div class="icon-preview mb-3">
                        <div class="category-icon-placeholder">
                            <i class="fas fa-image fa-2x text-muted"></i>
                        </div>
                    </div>
                    <h6 id="namePreview">Category Name</h6>
                    <p id="descriptionPreview" class="text-muted">Category description will appear here</p>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Tips</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="fas fa-lightbulb text-warning me-2"></i>Choose a clear, descriptive name</li>
                    <li class="mb-2"><i class="fas fa-image text-info me-2"></i>Use simple, recognizable icons</li>
                    <li class="mb-2"><i class="fas fa-users text-success me-2"></i>Think about user experience</li>
                    <li><i class="fas fa-check text-primary me-2"></i>Keep descriptions concise</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Live preview
document.getElementById('name').addEventListener('input', function() {
    const namePreview = document.getElementById('namePreview');
    namePreview.textContent = this.value || 'Category Name';
});

document.getElementById('description').addEventListener('input', function() {
    const descriptionPreview = document.getElementById('descriptionPreview');
    descriptionPreview.textContent = this.value || 'Category description will appear here';
});

// Icon preview
document.getElementById('icon').addEventListener('change', function() {
    const file = this.files[0];
    const iconPlaceholder = document.querySelector('.category-icon-placeholder');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            iconPlaceholder.innerHTML = `<img src="${e.target.result}" alt="Preview" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">`;
        };
        reader.readAsDataURL(file);
    } else {
        iconPlaceholder.innerHTML = '<i class="fas fa-image fa-2x text-muted"></i>';
    }
});

// Auto-generate slug preview (optional)
document.getElementById('name').addEventListener('input', function() {
    const slug = this.value.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .trim();
    console.log('Generated slug:', slug);
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

.form-text {
    font-size: 0.85rem;
}
</style>
@endpush