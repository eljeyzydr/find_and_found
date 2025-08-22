@extends('layouts.app')

@section('title', 'My Profile - Find & Found')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Profile Info -->
        <div class="col-lg-4 mb-4">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" id="avatarImage">
                        <div class="avatar-badge" onclick="triggerAvatarUpload()">
                            <i class="fas fa-camera"></i>
                        </div>
                        <div class="avatar-loading" id="avatarLoading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <!-- Hidden file input -->
                        <input type="file" id="avatarInput" accept="image/*" style="display: none;">
                    </div>
                    <h3 class="profile-name">{{ auth()->user()->name }}</h3>
                    <p class="profile-email">{{ auth()->user()->email }}</p>
                    <div class="profile-badges">
                        <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span>
                        <span class="badge bg-success">{{ ucfirst(auth()->user()->status) }}</span>
                    </div>
                </div>
                
                <div class="profile-stats">
                    <div class="stat-item">
                        <div class="stat-number">{{ auth()->user()->items()->count() }}</div>
                        <div class="stat-label">Items Posted</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ auth()->user()->items()->resolved()->count() }}</div>
                        <div class="stat-label">Resolved</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ auth()->user()->created_at->diffInDays(now()) }}</div>
                        <div class="stat-label">Days Active</div>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                    <a href="{{ route('profile.change-password') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-lock me-2"></i>Change Password
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="col-lg-8">
            <!-- Profile Details -->
            <div class="profile-section mb-4">
                <div class="section-header">
                    <h5><i class="fas fa-user me-2"></i>Profile Information</h5>
                </div>
                <div class="profile-details">
                    <div class="detail-row">
                        <div class="detail-label">Full Name</div>
                        <div class="detail-value">{{ auth()->user()->name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Email Address</div>
                        <div class="detail-value">{{ auth()->user()->email }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Phone Number</div>
                        <div class="detail-value">{{ auth()->user()->phone ?: 'Not provided' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Member Since</div>
                        <div class="detail-value">{{ auth()->user()->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Account Status</div>
                        <div class="detail-value">
                            <span class="badge bg-{{ auth()->user()->status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst(auth()->user()->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Summary -->
            <div class="profile-section mb-4">
                <div class="section-header">
                    <h5><i class="fas fa-chart-line me-2"></i>Activity Summary</h5>
                </div>
                <div class="activity-grid">
                    <div class="activity-card">
                        <div class="activity-icon lost">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-number">{{ auth()->user()->items()->lost()->count() }}</div>
                            <div class="activity-label">Lost Items Posted</div>
                        </div>
                    </div>
                    <div class="activity-card">
                        <div class="activity-icon found">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-number">{{ auth()->user()->items()->found()->count() }}</div>
                            <div class="activity-label">Found Items Posted</div>
                        </div>
                    </div>
                    <div class="activity-card">
                        <div class="activity-icon comments">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-number">{{ auth()->user()->comments()->count() }}</div>
                            <div class="activity-label">Comments Made</div>
                        </div>
                    </div>
                    <div class="activity-card">
                        <div class="activity-icon help">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-number">{{ auth()->user()->items()->resolved()->count() }}</div>
                            <div class="activity-label">Successful Reunions</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Items -->
            <div class="profile-section">
                <div class="section-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-box me-2"></i>Recent Items</h5>
                        <a href="{{ route('items.my-items') }}" class="btn btn-outline-primary btn-sm">View All</a>
                    </div>
                </div>
                <div class="recent-items">
                    @forelse(auth()->user()->items()->latest()->take(3)->get() as $item)
                        <div class="recent-item">
                            <div class="item-image">
                                <img src="{{ $item->first_photo }}" alt="{{ $item->title }}">
                                <div class="item-status status-{{ $item->status }}">
                                    {{ $item->status_label }}
                                </div>
                            </div>
                            <div class="item-info">
                                <h6 class="item-title">{{ $item->title }}</h6>
                                <p class="item-description">{{ Str::limit($item->description, 80) }}</p>
                                <div class="item-meta">
                                    <span class="meta-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $item->location->city }}
                                    </span>
                                    <span class="meta-date">{{ $item->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="item-stats">
                                <div class="stat">
                                    <i class="fas fa-eye"></i>
                                    {{ $item->views_count }}
                                </div>
                                <div class="stat">
                                    <i class="fas fa-comments"></i>
                                    {{ $item->comments_count }}
                                </div>
                            </div>
                            <div class="item-actions">
                                <a href="{{ route('items.show', $item->id) }}" class="btn btn-outline-primary btn-sm">View</a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-items">
                            <i class="fas fa-box-open"></i>
                            <p>No items posted yet</p>
                            <a href="{{ route('items.create') }}" class="btn btn-primary btn-sm">Post Your First Item</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Avatar Crop Modal -->
<div class="modal fade" id="avatarCropModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Your Avatar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="crop-container">
                    <img id="cropImage" style="max-width: 100%;">
                </div>
                <div class="crop-info mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Drag to move the image and use the corners to resize. The image will be cropped to a square.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="cropAndUpload">
                    <i class="fas fa-check me-2"></i>Save Avatar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Profile Card */
.profile-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.profile-header {
    margin-bottom: 2rem;
}

.profile-avatar {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 auto 1rem;
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #f8f9fa;
    transition: opacity 0.3s;
}

.avatar-badge {
    position: absolute;
    bottom: 8px;
    right: 8px;
    width: 32px;
    height: 32px;
    background: #2563eb;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.avatar-badge:hover {
    background: #1d4ed8;
    transform: scale(1.1);
}

.avatar-loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 40px;
    height: 40px;
    background: rgba(37, 99, 235, 0.9);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.profile-avatar.uploading img {
    opacity: 0.5;
}

.profile-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 0.5rem;
}

.profile-email {
    color: #6c757d;
    margin-bottom: 1rem;
}

.profile-badges {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    margin-bottom: 1rem;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
    padding: 1rem 0;
    border-top: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
}

.profile-stats .stat-item {
    text-align: center;
}

.profile-stats .stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2563eb;
    display: block;
}

.profile-stats .stat-label {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
}

/* Profile Sections */
.profile-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.section-header {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.section-header h5 {
    margin-bottom: 0;
    color: #212529;
    font-weight: 600;
}

/* Profile Details */
.profile-details {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.detail-label {
    font-weight: 500;
    color: #495057;
}

.detail-value {
    color: #212529;
    font-weight: 600;
}

/* Activity Grid */
.activity-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.activity-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.activity-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
}

.activity-icon.lost {
    background: #ffc107;
    color: #212529;
}

.activity-icon.found {
    background: #28a745;
}

.activity-icon.comments {
    background: #17a2b8;
}

.activity-icon.help {
    background: #dc3545;
}

.activity-number {
    font-size: 1.2rem;
    font-weight: 700;
    color: #212529;
}

.activity-label {
    font-size: 0.85rem;
    color: #6c757d;
}

/* Recent Items */
.recent-items {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.recent-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    transition: background-color 0.2s;
}

.recent-item:hover {
    background: #e9ecef;
}

.recent-item .item-image {
    position: relative;
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.recent-item .item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.recent-item .item-status {
    position: absolute;
    top: 4px;
    right: 4px;
    padding: 2px 6px;
    border-radius: 8px;
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

.item-info {
    flex: 1;
    min-width: 0;
}

.item-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #212529;
}

.item-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.item-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.8rem;
    color: #6c757d;
}

.meta-location {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.item-stats {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-right: 1rem;
}

.item-stats .stat {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.8rem;
    color: #6c757d;
}

.item-actions {
    flex-shrink: 0;
}

.empty-items {
    text-align: center;
    padding: 3rem 1rem;
    color: #6c757d;
}

.empty-items i {
    font-size: 3rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.empty-items p {
    margin-bottom: 1rem;
}

/* Crop Modal */
.crop-container {
    max-height: 400px;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f8f9fa;
    border-radius: 8px;
}

.crop-info {
    background: #f8f9fa;
    padding: 0.75rem;
    border-radius: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-stats {
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
    }
    
    .profile-stats .stat-number {
        font-size: 1.2rem;
    }
    
    .activity-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    .detail-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .recent-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .recent-item .item-image {
        width: 100%;
        height: 150px;
    }
    
    .item-stats {
        flex-direction: row;
        gap: 1rem;
        margin-right: 0;
    }
    
    .item-actions {
        align-self: stretch;
    }
    
    .item-actions .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .profile-card {
        padding: 1.5rem;
    }
    
    .profile-avatar {
        width: 100px;
        height: 100px;
    }
    
    .profile-name {
        font-size: 1.25rem;
    }
    
    .profile-section {
        padding: 1rem;
    }
}
</style>

<!-- Include Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
@endpush

@push('scripts')
<!-- Include Cropper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<script>
let cropper;
let currentFile;

// Trigger file input when avatar badge is clicked
function triggerAvatarUpload() {
    document.getElementById('avatarInput').click();
}

// Handle file selection
document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    
    if (!file) return;
    
    // Validate file type
    if (!file.type.startsWith('image/')) {
        alert('Please select an image file.');
        return;
    }
    
    // Validate file size (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
        alert('File size must be less than 5MB.');
        return;
    }
    
    currentFile = file;
    
    // Read file and show in modal
    const reader = new FileReader();
    reader.onload = function(e) {
        const cropImage = document.getElementById('cropImage');
        cropImage.src = e.target.result;
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('avatarCropModal'));
        modal.show();
        
        // Initialize cropper when modal is shown
        document.getElementById('avatarCropModal').addEventListener('shown.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
            }
            
            cropper = new Cropper(cropImage, {
                aspectRatio: 1,
                viewMode: 2,
                guides: false,
                center: false,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        });
    };
    
    reader.readAsDataURL(file);
});

// Handle crop and upload
document.getElementById('cropAndUpload').addEventListener('click', function() {
    if (!cropper) return;
    
    // Get cropped canvas
    const canvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high'
    });
    
    // Convert to blob
    canvas.toBlob(function(blob) {
        uploadAvatar(blob);
    }, 'image/jpeg', 0.9);
});

// Upload avatar function
// REPLACE bagian script uploadAvatar di profile/show.blade.php

// Upload avatar function with better debugging
function uploadAvatar(blob) {
    console.log('Starting avatar upload...', blob);
    
    const formData = new FormData();
    formData.append('avatar', blob, 'avatar.jpg');
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'PUT'); // Tambahkan method PUT untuk Laravel
    
    // Show loading state
    const avatarContainer = document.querySelector('.profile-avatar');
    const avatarLoading = document.getElementById('avatarLoading');
    
    avatarContainer.classList.add('uploading');
    avatarLoading.style.display = 'flex';
    
    // Upload via AJAX dengan method PUT
    fetch('{{ route("profile.update") }}', {
        method: 'POST', // Tetap POST, tapi dengan _method=PUT
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            return response.text().then(text => {
                console.error('Non-JSON response:', text);
                throw new Error('Server returned non-JSON response');
            });
        }
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            // Update avatar image
            const avatarImage = document.getElementById('avatarImage');
            avatarImage.src = data.avatar_url + '?t=' + Date.now();
            
            // Update navbar avatar if exists
            const navbarAvatar = document.querySelector('.navbar .rounded-circle');
            if (navbarAvatar) {
                navbarAvatar.src = data.avatar_url + '?t=' + Date.now();
            }
            
            // Hide modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('avatarCropModal'));
            modal.hide();
            
            // Show success message
            showToast('Avatar updated successfully!', 'success');
        } else {
            let errorMessage = 'Upload failed';
            
            if (data.errors) {
                const errorMessages = Object.values(data.errors).flat();
                errorMessage = errorMessages.join(', ');
            } else if (data.message) {
                errorMessage = data.message;
            }
            
            throw new Error(errorMessage);
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        showToast('Failed to update avatar: ' + error.message, 'error');
    })
    .finally(() => {
        // Hide loading state
        avatarContainer.classList.remove('uploading');
        avatarLoading.style.display = 'none';
        
        // Reset file input
        document.getElementById('avatarInput').value = '';
    });
}
// Helper function to show toast messages
function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 5000);
}

// Clean up cropper when modal is hidden
document.getElementById('avatarCropModal').addEventListener('hidden.bs.modal', function() {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
});
</script>
@endpush