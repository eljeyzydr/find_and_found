@extends('layouts.app')

@section('title', 'Daftar - Find & Found')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h1 class="h3 fw-bold text-primary">Bergabung dengan Find & Found</h1>
                        <p class="text-muted">Buat akun dan bantu sesama menemukan barang hilang</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autocomplete="name" 
                                   autofocus
                                   placeholder="Masukkan nama lengkap Anda">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email"
                                   placeholder="Masukkan email Anda">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon <span class="text-muted">(Opsional)</span></label>
                            <input type="tel" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   autocomplete="tel"
                                   placeholder="Contoh: 081234567890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Nomor telepon akan membantu dalam proses komunikasi</div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Minimal 6 karakter">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Password minimal 6 karakter</div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Ulangi password Anda">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="password_confirmation-icon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('terms') is-invalid @enderror" 
                                       type="checkbox" 
                                       id="terms" 
                                       name="terms" 
                                       required
                                       {{ old('terms') ? 'checked' : '' }}>
                                <label class="form-check-label" for="terms">
                                    Saya setuju dengan <a href="{{ route('terms') }}" target="_blank">Syarat & Ketentuan</a> 
                                    dan <a href="{{ route('privacy') }}" target="_blank">Kebijakan Privasi</a>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted mb-2">Sudah punya akun?</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk di Sini
                        </a>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="row mt-4 text-center">
                <div class="col-4">
                    <div class="text-primary">
                        <i class="fas fa-shield-alt fa-2x mb-2"></i>
                        <div class="fw-bold">Aman</div>
                        <small class="text-muted">Data terlindungi</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-success">
                        <i class="fas fa-clock fa-2x mb-2"></i>
                        <div class="fw-bold">Cepat</div>
                        <small class="text-muted">Pendaftaran mudah</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-info">
                        <i class="fas fa-gift fa-2x mb-2"></i>
                        <div class="fw-bold">Gratis</div>
                        <small class="text-muted">Tidak ada biaya</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('password-strength');
    const strengthText = document.getElementById('password-strength-text');
    
    if (!strengthBar) return;
    
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
    
    if (strengthText) strengthText.textContent = text;
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    const terms = document.getElementById('terms').checked;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Password dan konfirmasi password tidak cocok!');
        return false;
    }
    
    if (!terms) {
        e.preventDefault();
        alert('Anda harus menyetujui syarat dan ketentuan!');
        return false;
    }
});
</script>
@endpush