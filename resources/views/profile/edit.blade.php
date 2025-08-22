@extends('layouts.app')

@section('title', 'Edit Profile - Find & Found')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="profile-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">Edit Profile</h1>
                        <p class="page-subtitle">Update your personal information</p>
                    </div>
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Profile
                    </a>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                @csrf
                @method('PUT')

                <!-- Profile Picture -->
                <div class="profile-section mb-4">
                    <div class="section-header">
                        <h5><i class="fas fa-camera me-2"></i>Profile Picture</h5>
                    </div>
                    <div class="section-content">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center mb-3">
                                <div class="current-avatar">
                                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" 
                                         id="avatarPreview" class="avatar-image">
                                    <div class="avatar-overlay" onclick="document.getElementById('avatar').click()">
                                        <i class="fas fa-camera"></i>
                                        <span>Change</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <label for="avatar" class="form-label">Upload New Avatar</label>
                                    <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                           id="avatar" name="avatar" accept="image/*">
                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Upload JPG, PNG, or GIF. Max size: 2MB. Recommended: 400x400px
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeAvatar()">
                                    <i class="fas fa-trash me-1"></i>Remove Current Avatar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="profile-section mb-4">
                    <div class="section-header">
                        <h5><i class="fas fa-user me-2"></i>Personal Information</h5>
                    </div>
                    <div class="section-content">
                        <div class="row">
                            <!-- Full Name -->
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    This email will be used for login and notifications
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" 
                                       placeholder="e.g., 081234567890">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Optional. Will help others contact you about items
                                </div>
                            </div>

                            <!-- Member Since (Read-only) -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Member Since</label>
                                <input type="text" class="form-control" 
                                       value="{{ auth()->user()->created_at->format('M d, Y') }}" readonly>
                                <div class="form-text">Account creation date</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Settings -->
                <div class="profile-section mb-4">
                    <div class="section-header">
                        <h5><i class="fas fa-cog me-2"></i>Account Settings</h5>
                    </div>
                    <div class="section-content">
                        <div class="row">
                            <!-- Account Status -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Account Status</label>
                                <div class="form-control-plaintext">
                                    <span class="badge bg-{{ auth()->user()->status === 'active' ? 'success' : 'danger' }} fs-6">
                                        <i class="fas fa-{{ auth()->user()->status === 'active' ? 'check-circle' : 'times-circle' }} me-1"></i>
                                        {{ ucfirst(auth()->user()->status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Account Role -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Account Role</label>
                                <div class="form-control-plaintext">
                                    <span class="badge bg-primary fs-6">
                                        <i class="fas fa-{{ auth()->user()->role === 'admin' ? 'crown' : 'user' }} me-1"></i>
                                        {{ ucfirst(auth()->user()->role) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Statistics -->
                            <div class="col-md-12">
                                <label class="form-label">Account Statistics</label>
                                <div class="stats-cards">
                                    <div class="stat-card">
                                        <div class="stat-icon">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <div class="stat-info">
                                            <div class="stat-number">{{ auth()->user()->items()->count() }}</div>
                                            <div class="stat-label">Items Posted</div>
                                        </div>
                                    </div>
                                    <div class="stat-card">
                                        <div class="stat-icon">
                                            <i class="fas fa-heart"></i>
                                        </div>
                                        <div class="stat-info">
                                            <div class="stat-number">{{ auth()->user()->items()->resolved()->count() }}</div>
                                            <div class="stat-label">Successful Reunions</div>
                                        </div>
                                    </div>
                                    <div class="stat-card">
                                        <div class="stat-icon">
                                            <i class="fas fa-comments"></i>
                                        </div>
                                        <div class="stat-info">
                                            <div class="stat-number">{{ auth()->user()->comments()->count() }}</div>
                                            <div class="stat-label">Comments Made</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Privacy Settings -->
                <div class="profile-section mb-4">
                    <div class="section-header">
                        <h5><i class="fas fa-shield-alt me-2"></i>Privacy & Notifications</h5>
                    </div>
                    <div class="section-content">
                        <div class="privacy-options">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="emailNotifications" 
                                       name="email_notifications" checked>
                                <label class="form-check-label" for="emailNotifications">
                                    <strong>Email Notifications</strong>
                                    <div class="option-description">Receive email notifications for new messages and comments</div>
                                </label>
                            </div>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="showPhone" 
                                       name="show_phone">
                                <label class="form-check-label" for="showPhone">
                                    <strong>Show Phone Number</strong>
                                    <div class="option-description">Allow others to see your phone number when contacting about items</div>
                                </label>
                            </div>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="publicProfile" 
                                       name="public_profile" checked>
                                <label class="form-check-label" for="publicProfile">
                                    <strong>Public Profile</strong>
                                    <div class="option-description">Make your profile visible to other users</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Section -->
                <div class="profile-section mb-4">
                    <div class="section-header">
                        <h5><i class="fas fa-lock me-2"></i>Security</h5>
                    </div>
                    <div class="section-content">
                        <div class="security-info">
                            <div class="security-item">
                                <div class="security-icon">
                                    <i class="fas fa-key text-warning"></i>
                                </div>
                                <div class="security-content">
                                    <h6>Password</h6>
                                    <p class="text-muted">Last updated: {{ auth()->user()->updated_at->diffForHumans() }}</p>
                                </div>
                                <div class="security-action">
                                    <a href="{{ route('profile.change-password') }}" class="btn btn-outline-primary btn-sm">
                                        Change Password
                                    </a>
                                </div>
                            </div>
                            
                            <div class="security-item">
                                <div class="security-icon">
                                    <i class="fas fa-shield-alt text-success"></i>
                                </div>
                                <div class="security-content">
                                    <h6>Account Security</h6>
                                    <p class="text-muted">Your account is secured with password authentication</p>
                                </div>
                                <div class="security-action">
                                    <span class="badge bg-success">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmUpdate" required>
                            <label class="form-check-label" for="confirmUpdate">
                                I confirm that the information provided is accurate
                            </label>
                        </div>
                        <div class="actions">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="updateBtn">
                                <i class="fas fa-save me-2"></i>Save Changes
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
/* Profile Header */
.profile-header {
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

/* Profile Sections */
.profile-section {
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

/* Avatar */
.current-avatar {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 auto;
    cursor: pointer;
}

.avatar-image {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #f8f9fa;
    transition: all 0.2s;
}

.avatar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
    opacity: 0;
    transition: opacity 0.2s;
    cursor: pointer;
}

.current-avatar:hover .avatar-overlay {
    opacity: 1;
}

.avatar-overlay i {
    font-size: 1.5rem;
    margin-bottom: 0.25rem;
}

.avatar-overlay span {
    font-size: 0.8rem;
    font-weight: 600;
}

/* Stats Cards */
.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.stat-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #2563eb;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.stat-number {
    font-size: 1.25rem;
    font-weight: 700;
    color: #212529;
}

.stat-label {
    font-size: 0.8rem;
    color: #6c757d;
}

/* Privacy Options */
.privacy-options {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-check-label {
    cursor: pointer;
}

.option-description {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

/* Security Items */
.security-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.security-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.security-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.security-content {
    flex: 1;
}

.security-content h6 {
    margin-bottom: 0.25rem;
    color: #212529;
    font-weight: 600;
}

.security-content p {
    margin-bottom: 0;
    font-size: 0.9rem;
}

.security-action {
    flex-shrink: 0;
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

/* Form Controls */
.form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
}

.form-check-input:checked {
    background-color: #2563eb;
    border-color: #2563eb;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-header .d-flex {
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
    
    .stats-cards {
        grid-template-columns: 1fr;
    }
    
    .security-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .security-action {
        align-self: stretch;
    }
    
    .security-action .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .current-avatar {
        width: 100px;
        height: 100px;
    }
    
    .stat-card {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .stat-icon {
        width: 36px;
        height: 36px;
        font-size: 0.9rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Avatar preview
document.getElementById('avatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (file.size > 2 * 1024 * 1024) { // 2MB limit
            alert('File size must be less than 2MB');
            e.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

function removeAvatar() {
    if (confirm('Are you sure you want to remove your current avatar?')) {
        document.getElementById('avatarPreview').src = '{{ asset("images/default-avatar.png") }}';
        document.getElementById('avatar').value = '';
        // TODO: Add hidden input to indicate avatar removal
    }
}

// Form validation
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    
    if (!name || !email) {
        e.preventDefault();
        alert('Name and email are required');
        return false;
    }
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address');
        return false;
    }
    
    // Phone validation (if provided)
    const phone = document.getElementById('phone').value.trim();
    if (phone && !/^[\d\s\-\+\(\)]+$/.test(phone)) {
        e.preventDefault();
        alert('Please enter a valid phone number');
        return false;
    }
    
    // Show loading state
    const updateBtn = document.getElementById('updateBtn');
    updateBtn.disabled = true;
    updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
});

// Character counter for name field
document.getElementById('name').addEventListener('input', function() {
    const maxLength = 255;
    const currentLength = this.value.length;
    
    let counter = document.getElementById('nameCounter');
    if (!counter) {
        counter = document.createElement('div');
        counter.id = 'nameCounter';
        counter.className = 'form-text';
        this.parentNode.appendChild(counter);
    }
    
    counter.textContent = `${currentLength}/${maxLength} characters`;
    
    if (currentLength > maxLength - 20) {
        counter.classList.add('text-warning');
    } else {
        counter.classList.remove('text-warning');
    }
});

// Real-time email validation
document.getElementById('email').addEventListener('blur', function() {
    const email = this.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email && !emailRegex.test(email)) {
        this.setCustomValidity('Please enter a valid email address');
        this.classList.add('is-invalid');
    } else {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
    }
});

// Phone number formatting
document.getElementById('phone').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, ''); // Remove non-digits
    
    // Format Indonesian phone numbers
    if (value.startsWith('62')) {
        value = '+' + value;
    } else if (value.startsWith('08')) {
        value = value;
    }
    
    this.value = value;
});
</script>
@endpush