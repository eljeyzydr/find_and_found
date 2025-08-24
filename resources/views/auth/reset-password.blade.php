@extends('layouts.app')

@section('title', 'Reset Password - Find & Found')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="reset-icon mb-3">
                            <i class="fas fa-key fa-3x text-success"></i>
                        </div>
                        <h1 class="h3 fw-bold text-primary">Reset Password</h1>
                        <p class="text-muted">Masukkan password baru untuk akun Anda</p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <!-- Hidden Fields -->
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ $email ?? old('email') }}" 
                                       required 
                                       autocomplete="email"
                                       readonly>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Masukkan password baru">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Password minimal 6 karakter</div>
                            
                            <!-- Password Strength Indicator -->
                            <div class="password-strength mt-2">
                                <div class="progress" style="height: 4px;">
                                    <div id="password-strength" class="progress-bar" style="width: 0%"></div>
                                </div>
                                <small id="password-strength-text" class="text-muted"></small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Ulangi password baru">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="password_confirmation-icon"></i>
                                </button>
                            </div>
                            <div id="password-match" class="form-text"></div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mb-3" id="resetBtn">
                            <i class="fas fa-key me-2"></i>Reset Password
                        </button>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Tips -->
            <div class="security-tips mt-4">
                <div class="card border-warning">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-shield-alt text-warning me-2"></i>Tips Keamanan
                        </h6>
                        <ul class="list-unstyled mb-0 small">
                            <li><i class="fas fa-check text-success me-2"></i>Gunakan kombinasi huruf besar, kecil, angka</li>
                            <li><i class="fas fa-check text-success me-2"></i>Minimal 6 karakter</li>
                            <li><i class="fas fa-check text-success me-2"></i>Jangan gunakan password yang mudah ditebak</li>
                            <li><i class="fas fa-check text-success me-2"></i>Simpan password di tempat yang aman</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.reset-icon {
    width: 80px;
    height: 80px;
    background: rgba(16, 185, 129, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.security-tips {
    animation: slideInUp 0.5s ease-out 0.3s both;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
    padding: 0.75rem;
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    transform: translateY(-1px);
}

.password-strength .progress {
    border-radius: 2px;
}

.progress-bar {
    transition: all 0.3s ease;
}

.match-success {
    color: #10b981 !important;
}

.match-error {
    color: #ef4444 !important;
}

@media (max-width: 576px) {
    .card-body {
        padding: 2rem 1.5rem !important;
    }
    
    .reset-icon {
        width: 60px;
        height: 60px;
    }
    
    .reset-icon i {
        font-size: 2rem !important;
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
    const strengthBar = document.getElementById('password-strength');
    const strengthText = document.getElementById('password-strength-text');
    
    let strength = 0;
    let text = '';
    
    if (password.length >= 6) strength += 1;
    if (password.match(/[a-z]+/)) strength += 1;
    if (password.match(/[A-Z]+/)) strength += 1;
    if (password.match(/[0-9]+/)) strength += 1;
    if (password.match(/[$@#&!]+/)) strength += 1;
    
    switch(strength) {
        case 0:
        case 1:
            strengthBar.className = 'progress-bar bg-danger';
            strengthBar.style.width = '20%';
            text = 'Lemah';
            break;
        case 2:
            strengthBar.className = 'progress-bar bg-warning';
            strengthBar.style.width = '40%';
            text = 'Kurang';
            break;
        case 3:
            strengthBar.className = 'progress-bar bg-info';
            strengthBar.style.width = '60%';
            text = 'Sedang';
            break;
        case 4:
            strengthBar.className = 'progress-bar bg-success';
            strengthBar.style.width = '80%';
            text = 'Kuat';
            break;
        case 5:
            strengthBar.className = 'progress-bar bg-success';
            strengthBar.style.width = '100%';
            text = 'Sangat Kuat';
            break;
    }
    
    strengthText.textContent = text;
});

// Password confirmation checker
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    const matchText = document.getElementById('password-match');
    
    if (confirmation.length > 0) {
        if (password === confirmation) {
            matchText.textContent = 'Password cocok ✓';
            matchText.className = 'form-text match-success';
        } else {
            matchText.textContent = 'Password tidak cocok ✗';
            matchText.className = 'form-text match-error';
        }
    } else {
        matchText.textContent = '';
        matchText.className = 'form-text';
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    
    if (password !== confirmation) {
        e.preventDefault();
        alert('Password dan konfirmasi password tidak cocok!');
        return false;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        alert('Password minimal 6 karakter!');
        return false;
    }
    
    // Show loading state
    const resetBtn = document.getElementById('resetBtn');
    resetBtn.disabled = true;
    resetBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mereset Password...';
});
</script>
@endpush