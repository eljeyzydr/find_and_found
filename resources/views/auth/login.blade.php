@extends('layouts.app')

@section('title', 'Masuk - Find & Found')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h1 class="h3 fw-bold text-primary">Selamat Datang Kembali</h1>
                        <p class="text-muted">Masuk ke akun Find & Found Anda</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   autofocus
                                   placeholder="Masukkan email Anda">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required 
                                       autocomplete="current-password"
                                       placeholder="Masukkan password Anda">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Ingat saya
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk
                        </button>

                        <div class="text-center">
                            <a href="{{ route('password.request') }}" class="text-decoration-none">
                                <i class="fas fa-key me-1"></i>Lupa password?
                            </a>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted mb-2">Belum punya akun?</p>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row mt-4 text-center">
                <div class="col-4">
                    <div class="text-primary">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <div class="fw-bold">1000+</div>
                        <small class="text-muted">Users</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <div class="fw-bold">500+</div>
                        <small class="text-muted">Berhasil</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-info">
                        <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                        <div class="fw-bold">50+</div>
                        <small class="text-muted">Kota</small>
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
</script>
@endpush