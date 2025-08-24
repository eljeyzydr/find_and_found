@extends('layouts.app')

@section('title', 'Edit Item - Find & Found')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="edit-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">Edit Item</h1>
                        <p class="page-subtitle">Update your item information</p>
                    </div>
                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Item
                    </a>
                </div>
            </div>

            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data" id="editItemForm">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="form-section mb-4">
                    <div class="section-header">
                        <h5><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                    </div>
                    <div class="section-content">
                        <div class="row">
                            <!-- Status -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-check-inline p-3 border rounded {{ $item->status === 'lost' ? 'border-warning bg-light' : '' }}">
                                            <input class="form-check-input" type="radio" name="status" id="statusLost" 
                                                   value="lost" {{ $item->status === 'lost' ? 'checked' : '' }} required>
                                            <label class="form-check-label w-100" for="statusLost">
                                                <div class="text-center">
                                                    <i class="fas fa-exclamation-circle fa-2x text-warning mb-2"></i>
                                                    <h6 class="fw-bold">Lost Item</h6>
                                                    <small class="text-muted">I lost this item</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-check-inline p-3 border rounded {{ $item->status === 'found' ? 'border-success bg-light' : '' }}">
                                            <input class="form-check-input" type="radio" name="status" id="statusFound" 
                                                   value="found" {{ $item->status === 'found' ? 'checked' : '' }} required>
                                            <label class="form-check-label w-100" for="statusFound">
                                                <div class="text-center">
                                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                                    <h6 class="fw-bold">Found Item</h6>
                                                    <small class="text-muted">I found this item</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('status')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Title -->
                            <div class="col-md-12 mb-3">
                                <label for="title" class="form-label">Item Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $item->title) }}" required 
                                       placeholder="Enter item name">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Event Date -->
                            <div class="col-md-6 mb-3">
                                <label for="event_date" class="form-label">Date {{ $item->status === 'lost' ? 'Lost' : 'Found' }} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('event_date') is-invalid @enderror" 
                                       id="event_date" name="event_date" 
                                       value="{{ old('event_date', $item->event_date->format('Y-m-d')) }}" 
                                       max="{{ date('Y-m-d') }}" required>
                                @error('event_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" required 
                                          placeholder="Describe the item in detail...">{{ old('description', $item->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Provide detailed description to help identify the item</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Photos -->
                <div class="form-section mb-4">
                    <div class="section-header">
                        <h5><i class="fas fa-camera me-2"></i>Photos</h5>
                    </div>
                    <div class="section-content">
                        <!-- ✅ FIXED: Existing Photos dengan path yang benar -->
                        @if($item->photos && count($item->photos) > 0)
                            <div class="existing-photos mb-3">
                                <label class="form-label">Current Photos</label>
                                <div class="photos-grid" id="existingPhotosGrid">
                                    @foreach($item->photos as $index => $photo)
                                        <div class="photo-item" data-photo="{{ $photo }}">
                                            <img src="{{ asset('storage/items/' . $photo) }}" alt="Item photo" 
                                                 onerror="this.src='{{ asset('images/no-image.png') }}'">
                                            <div class="photo-overlay">
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        onclick="removeExistingPhoto('{{ $photo }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <input type="hidden" name="existing_photos[]" value="{{ $photo }}">
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-text">Current photos: {{ count($item->photos) }}/5</div>
                            </div>
                        @endif

                        <!-- Add New Photos -->
                        <div class="mb-3">
                            <label for="photos" class="form-label">
                                Add New Photos
                                <span class="text-muted">(Max 5 total, 20MB per file)</span>
                            </label>
                            <input type="file" class="form-control @error('photos') is-invalid @enderror" 
                                   id="photos" name="photos[]" accept="image/*" multiple>
                            @error('photos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('photos.*')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload JPG, PNG, or GIF files. Max 20MB per photo.</div>
                        </div>

                        <!-- New Photos Preview -->
                        <div id="newPhotosPreview" class="photos-grid"></div>
                        
                        <!-- Photo Count Display -->
                        <div class="photo-count-display">
                            <small class="text-muted">
                                Total photos: <span id="totalPhotoCount">{{ count($item->photos ?? []) }}</span>/5
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="form-section mb-4">
                    <div class="section-header">
                        <h5><i class="fas fa-map-marker-alt me-2"></i>Location</h5>
                    </div>
                    <div class="section-content">
                        <div class="row">
                            <!-- Address -->
                            <div class="col-md-8 mb-3">
                                <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2" required 
                                          placeholder="Enter full address">{{ old('address', $item->location->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- City -->
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', $item->location->city) }}" required 
                                       placeholder="City name">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Province -->
                            <div class="col-md-6 mb-3">
                                <label for="province" class="form-label">Province</label>
                                <input type="text" class="form-control @error('province') is-invalid @enderror" 
                                       id="province" name="province" value="{{ old('province', $item->location->province) }}" 
                                       placeholder="Province name">
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Coordinates (Hidden) -->
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $item->location->latitude) }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $item->location->longitude) }}">

                            <!-- Map -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Location on Map</label>
                                <div class="map-controls mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="getCurrentLocation()">
                                        <i class="fas fa-crosshairs me-1"></i>Use My Location
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="searchOnMap()">
                                        <i class="fas fa-search me-1"></i>Search on Map
                                    </button>
                                </div>
                                <div id="editMap" class="map-container"></div>
                                <div class="form-text">Click on the map to update the location</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="form-actions">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmUpdate" required>
                            <label class="form-check-label" for="confirmUpdate">
                                I confirm that the information provided is accurate
                            </label>
                        </div>
                        <div class="actions">
                            <a href="{{ route('items.show', $item->id) }}" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="updateBtn">
                                <i class="fas fa-save me-2"></i>Update Item
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Edit Header */
.edit-header {
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

/* Form Sections */
.form-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.section-header {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #dee2e6;
}

.section-header h5 {
    margin-bottom: 0;
    color: #212529;
    font-weight: 600;
}

.section-content {
    padding: 1.5rem;
}

/* Status Radio Buttons */
.form-check {
    cursor: pointer;
    transition: all 0.2s;
}

.form-check:hover {
    background-color: #f8f9fa !important;
}

.form-check input[type="radio"]:checked + label {
    background-color: var(--bs-light) !important;
}

/* Photos */
.photos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.photo-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    aspect-ratio: 1;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    transition: all 0.2s;
}

.photo-item:hover {
    border-color: #2563eb;
}

.photo-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-overlay {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s;
}

.photo-item:hover .photo-overlay {
    opacity: 1;
}

.photo-item.removed {
    opacity: 0.5;
    border-color: #dc3545;
    position: relative;
}

.photo-item.removed::after {
    content: "Removed";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(220, 53, 69, 0.9);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
}

.photo-count-display {
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 4px;
    text-align: center;
    margin-top: 1rem;
}

/* Map */
.map-container {
    height: 300px;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #dee2e6;
}

.map-controls {
    display: flex;
    gap: 0.5rem;
}

/* Form Actions */
.form-actions {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.actions {
    display: flex;
    gap: 0.5rem;
}

/* Loading State */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Responsive */
@media (max-width: 768px) {
    .edit-header .d-flex {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start !important;
    }
    
    .form-actions .d-flex {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch !important;
    }
    
    .actions {
        justify-content: stretch;
    }
    
    .actions .btn {
        flex: 1;
    }
    
    .photos-grid {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 0.5rem;
    }
    
    .map-container {
        height: 250px;
    }
}
</style>
@endpush

@push('scripts')
<script>
let map, marker;
let currentPhotoCount = {{ count($item->photos ?? []) }};

// Initialize map
function initMap() {
    const lat = {{ $item->location->latitude }};
    const lng = {{ $item->location->longitude }};
    
    map = L.map('editMap').setView([lat, lng], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add existing marker
    marker = L.marker([lat, lng]).addTo(map);
    
    // Add click event to update location
    map.on('click', function(e) {
        updateLocation(e.latlng.lat, e.latlng.lng);
    });
}

function updateLocation(lat, lng) {
    if (marker) {
        map.removeLayer(marker);
    }
    
    marker = L.marker([lat, lng]).addTo(map);
    map.setView([lat, lng], 15);
    
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
}

function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            updateLocation(position.coords.latitude, position.coords.longitude);
        }, function(error) {
            alert('Failed to get your location. Please select manually on the map.');
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

function searchOnMap() {
    const address = document.getElementById('address').value;
    if (!address.trim()) {
        alert('Please enter an address first');
        return;
    }
    
    // TODO: Implement geocoding API to search address
    alert('Geocoding search will be implemented here');
}

// ✅ FIXED: Handle existing photo removal
function removeExistingPhoto(photoName) {
    const photoItem = document.querySelector(`[data-photo="${photoName}"]`);
    if (photoItem && !photoItem.classList.contains('removed')) {
        photoItem.classList.add('removed');
        const hiddenInput = photoItem.querySelector('input[name="existing_photos[]"]');
        if (hiddenInput) {
            hiddenInput.remove();
        }
        currentPhotoCount--;
        updatePhotoCount();
    }
}

// ✅ FIXED: Handle new photo preview dengan validasi
document.getElementById('photos').addEventListener('change', function(e) {
    const preview = document.getElementById('newPhotosPreview');
    preview.innerHTML = '';
    
    const files = Array.from(e.target.files);
    
    // Check file size (20MB = 20971520 bytes)
    for (let file of files) {
        if (file.size > 20971520) {
            alert(`File "${file.name}" terlalu besar. Maksimal 20MB per file.`);
            e.target.value = '';
            return;
        }
    }
    
    // Check total photos limit
    const existingPhotosCount = document.querySelectorAll('.photo-item:not(.removed)').length;
    const totalPhotos = existingPhotosCount + files.length;
    
    if (totalPhotos > 5) {
        alert('Maksimal 5 foto total');
        e.target.value = '';
        return;
    }
    
    // Show preview
    files.forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const photoDiv = document.createElement('div');
                photoDiv.className = 'photo-item new-photo';
                photoDiv.innerHTML = `
                    <img src="${e.target.result}" alt="New photo">
                    <div class="photo-overlay">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeNewPhoto(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                preview.appendChild(photoDiv);
            };
            reader.readAsDataURL(file);
        }
    });
    
    updatePhotoCount();
});

function removeNewPhoto(index) {
    const input = document.getElementById('photos');
    const dt = new DataTransfer();
    const files = input.files;
    
    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }
    
    input.files = dt.files;
    input.dispatchEvent(new Event('change'));
}

// ✅ Update photo count display
function updatePhotoCount() {
    const existingCount = document.querySelectorAll('.photo-item:not(.removed):not(.new-photo)').length;
    const newCount = document.querySelectorAll('.new-photo').length;
    const total = existingCount + newCount;
    
    document.getElementById('totalPhotoCount').textContent = total;
    
    // Update photo count display color
    const countDisplay = document.querySelector('.photo-count-display');
    if (total > 5) {
        countDisplay.classList.add('text-danger');
        countDisplay.classList.remove('text-muted');
    } else {
        countDisplay.classList.remove('text-danger');
        countDisplay.classList.add('text-muted');
    }
}

// Status radio styling
document.querySelectorAll('input[name="status"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.form-check').forEach(function(check) {
            check.classList.remove('border-warning', 'border-success', 'bg-light');
        });
        
        const selectedCheck = this.closest('.form-check');
        if (this.value === 'lost') {
            selectedCheck.classList.add('border-warning', 'bg-light');
        } else {
            selectedCheck.classList.add('border-success', 'bg-light');
        }
        
        // Update event date label
        const eventLabel = document.querySelector('label[for="event_date"]');
        if (eventLabel) {
            eventLabel.innerHTML = `Date ${this.value === 'lost' ? 'Lost' : 'Found'} <span class="text-danger">*</span>`;
        }
    });
});

// ✅ FIXED: Form validation dengan photo count check
document.getElementById('editItemForm').addEventListener('submit', function(e) {
    const lat = document.getElementById('latitude').value;
    const lng = document.getElementById('longitude').value;
    
    if (!lat || !lng) {
        e.preventDefault();
        alert('Please select a location on the map');
        return false;
    }
    
    // Check photo count
    const totalPhotos = document.querySelectorAll('.photo-item:not(.removed)').length + 
                       document.querySelectorAll('.new-photo').length;
    
    if (totalPhotos > 5) {
        e.preventDefault();
        alert('Maksimal 5 foto total');
        return false;
    }
    
    // Show loading state
    const updateBtn = document.getElementById('updateBtn');
    updateBtn.disabled = true;
    updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
    
    // Add loading class to form
    this.classList.add('loading');
});

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    updatePhotoCount();
});
</script>
@endpush