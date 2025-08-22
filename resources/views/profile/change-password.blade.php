@extends('layouts.app')

@section('title', 'Change Password - Find & Found')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Page Header -->
            <div class="password-header mb-4">
                <div class="text-center">
                    <div class="header-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h1 class="page-title">Change Password</h1>
                    <p class="page-subtitle">Update your account password for better security</p>
                </div>
            </div>

            <!-- Security Tips -->
            <div class="security-tips mb-4">
                <h6><i class="fas fa-shield-alt me-2"></i>Password Security Tips</h6>
                <ul class="tips-list">
                    <li>Use at least 8 characters</li>
                    <li>Include uppercase and lowercase letters</li>
                    <li>Add numbers and special characters</li>
                    <li>Avoid common words or personal information</li>
                </ul>
            </div>

            <!-- Change Password Form -->
            <div class="password-form">
                <form action="{{ route('profile.update-password') }}" method="POST" id="changePasswordForm">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div class="mb-4">
                        <label for="current_password" class="form-label">
                            Current Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   required
                                   placeholder="Enter your current password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye" id="current_password-icon"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            New Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required
                                   placeholder="Enter your new password"
                                   minlength="6">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="password-icon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        
                        <!-- Password Strength Indicator -->
                        <div class="password-strength mt-2">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthBar"></div>
                            </div>
                            <div class="strength-text" id="strengthText">Enter a password</div>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">
                            Confirm New Password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required
                                   placeholder="Confirm your new password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye" id="password_confirmation-icon"></i>
                            </button>
                        </div>
                        <div class="password-match mt-2" id="passwordMatch"></div>
                    </div>

                    <!-- Security Notice -->
                    <div class="security-notice mb-4">
                        <div class="notice-content">
                            <i class="fas fa-info-circle text-info"></i>
                            <div class="notice-text">
                                <strong>Security Notice:</strong> After changing your password, you'll remain logged in on this device. 
                                However, you'll need to log in again on other devices.
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Profile
                            </a>
                            <button type="submit" class="btn btn-primary" id="changePasswordBtn">
                                <i class="fas fa-lock me-2"></i>Change Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Last Password Change -->
            <div class="password-info mt-4">
                <div class="info-card">
                    <div class="info-content">
                        <i class="fas fa-history text-muted"></i>
                        <div class="info-text">
                            <strong>Last password change:</strong>
                            <span class="text-muted">{{ auth()->user()->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Page Header */
.password-header {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.header-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    font-size: 2rem;
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

/* Security Tips */
.security-tips {
    background: #e3f2fd;
    border-radius: 12px;
    padding: 1.5rem;
    border-left: 4px solid #2563eb;
}

.security-tips h6 {
    color: #1565c0;
    margin-bottom: 1rem;
    font-weight: 600;
}

.tips-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tips-list li {
    padding: 0.5rem 0;
    color: #1565c0;
    position: relative;
    padding-left: 1.5rem;
}

.tips-list li::before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #2563eb;
    font-weight: bold;
}

/* Password Form */
.password-form {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e5e7eb;
    padding: 0.75rem 1rem;
    transition: all 0.2s;
}

.form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.input-group .btn {
    border-radius: 0 8px 8px 0;
    border: 2px solid #e5e7eb;
    border-left: none;
}

/* Password Strength */
.password-strength {
    margin-top: 0.5rem;
}

.strength-bar {
    width: 100%;
    height: 4px;
    background: #e5e7eb;
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 0.25rem;
}

.strength-fill {
    height: 100%;
    width: 0;
    transition: all 0.3s ease;
    border-radius: 2px;
}

.strength-text {
    font-size: 0.8rem;
    font-weight: 500;
}

/* Strength levels */
.strength-weak .strength-fill {
    width: 25%;
    background: #ef4444;
}

.strength-fair .strength-fill {
    width: 50%;
    background: #f59e0b;
}

.strength-good .strength-fill {
    width: 75%;
    background: #3b82f6;
}

.strength-strong .strength-fill {
    width: 100%;
    background: #10b981;
}

.strength-weak .strength-text { color: #ef4444; }
.strength-fair .strength-text { color: #f59e0b; }
.strength-good .strength-text { color: #3b82f6; }
.strength-strong .strength-text { color: #10b981; }

/* Password Match */
.password-match {
    font-size: 0.8rem;
    font-weight: 500;
}

.password-match.match {
    color: #10b981;
}

.password-match.no-match {
    color: #ef4444;
}

/* Security Notice */
.security-notice {
    background: #fef3c7;
    border-radius: 8px;
    padding: 1rem;
    border-left: 4px solid #f59e0b;
}

.notice-content {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.notice-content i {
    font-size: 1.2rem;
    margin-top: 0.1rem;
}

.notice-text {
    color: #92400e;
    font-size: 0.9rem;
    line-height: 1.5;
}

/* Form Actions */
.form-actions {
    border-top: 1px solid #e5e7eb;
    padding-top: 1.5rem;
}

/* Password Info */
.password-info {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.info-card {
    display: flex;
    align-items: center;
}

.info-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    width: 100%;
}

.info-content i {
    font-size: 1.2rem;
}

.info-text {
    font-size: 0.9rem;
}

/* Loading State */
.btn.loading {
    pointer-events: none;
    opacity: 0.7;
}

/* Responsive */
@media (max-width: 768px) {
    .password-header {
        padding: 1.5rem;
    }
    
    .header-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .password-form {
        padding: 1.5rem;
    }
    
    .form-actions .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthContainer = document.querySelector('.password-strength');
    const strengthText = document.getElementById('strengthText');
    
    if (password.length === 0) {
        strengthContainer.className = 'password-strength';
        strengthText.textContent = 'Enter a password';
        return;
    }
    
    let score = 0;
    let feedback = [];
    
    // Length check
    if (password.length >= 8) score += 1;
    else feedback.push('at least 8 characters');
    
    // Uppercase check
    if (/[A-Z]/.test(password)) score += 1;
    else feedback.push('uppercase letters');
    
    // Lowercase check
    if (/[a-z]/.test(password)) score += 1;
    else feedback.push('lowercase letters');
    
    // Number check
    if (/[0-9]/.test(password)) score += 1;
    else feedback.push('numbers');
    
    // Special character check
    if (/[^A-Za-z0-9]/.test(password)) score += 1;
    else feedback.push('special characters');
    
    // Update strength indicator
    strengthContainer.className = 'password-strength';
    
    if (score <= 2) {
        strengthContainer.classList.add('strength-weak');
        strengthText.textContent = 'Weak - Add ' + feedback.slice(0, 2).join(', ');
    } else if (score === 3) {
        strengthContainer.classList.add('strength-fair');
        strengthText.textContent = 'Fair - Add ' + feedback.slice(0, 1).join(', ');
    } else if (score === 4) {
        strengthContainer.classList.add('strength-good');
        strengthText.textContent = 'Good - Almost there!';
    } else {
        strengthContainer.classList.add('strength-strong');
        strengthText.textContent = 'Strong - Great password!';
    }
    
    // Check password match
    checkPasswordMatch();
});

// Password confirmation checker
document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);

function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    const matchIndicator = document.getElementById('passwordMatch');
    
    if (confirmation.length === 0) {
        matchIndicator.textContent = '';
        matchIndicator.className = 'password-match';
        return;
    }
    
    if (password === confirmation) {
        matchIndicator.textContent = '✓ Passwords match';
        matchIndicator.className = 'password-match match';
    } else {
        matchIndicator.textContent = '✗ Passwords do not match';
        matchIndicator.className = 'password-match no-match';
    }
}

// Form validation
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    
    // Check if all fields are filled
    if (!currentPassword || !newPassword || !confirmPassword) {
        e.preventDefault();
        alert('Please fill in all password fields');
        return false;
    }
    
    // Check password length
    if (newPassword.length < 6) {
        e.preventDefault();
        alert('New password must be at least 6 characters long');
        return false;
    }
    
    // Check password match
    if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert('New password and confirmation do not match');
        return false;
    }
    
    // Check if new password is different from current
    if (currentPassword === newPassword) {
        e.preventDefault();
        alert('New password must be different from current password');
        return false;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('changePasswordBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Changing Password...';
    submitBtn.classList.add('loading');
});

// Caps lock detection
document.addEventListener('keydown', function(e) {
    const capsLockOn = e.getModifierState && e.getModifierState('CapsLock');
    const activeElement = document.activeElement;
    
    if (capsLockOn && activeElement.type === 'password') {
        showCapsLockWarning(activeElement);
    } else {
        hideCapsLockWarning();
    }
});

function showCapsLockWarning(element) {
    // Remove existing warning
    hideCapsLockWarning();
    
    const warning = document.createElement('div');
    warning.id = 'capsLockWarning';
    warning.className = 'alert alert-warning alert-sm mt-1 mb-0';
    warning.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Caps Lock is on';
    
    element.parentNode.insertBefore(warning, element.nextSibling);
}

function hideCapsLockWarning() {
    const warning = document.getElementById('capsLockWarning');
    if (warning) {
        warning.remove();
    }
}

// Auto-focus first field
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('current_password').focus();
});

// Password visibility toggle keyboard shortcut
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'h') {
        e.preventDefault();
        const activeElement = document.activeElement;
        if (activeElement.type === 'password' || activeElement.type === 'text') {
            const fieldId = activeElement.id;
            togglePassword(fieldId);
        }
    }
});

// Prevent password managers from auto-filling if not wanted
document.getElementById('password').addEventListener('focus', function() {
    // Clear any auto-filled confirmation field
    document.getElementById('password_confirmation').value = '';
    checkPasswordMatch();
});
</script>
@endpush