@extends('layouts.app')

@section('title', 'Lupa Password - Find & Found')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="forgot-icon mb-3">
                            <i class="fas fa-lock fa-3x text-primary"></i>
                        </div>
                        <h1 class="h3 fw-bold text-primary">Lupa Password?</h1>
                        <p class="text-muted">Tidak masalah! Masukkan email Anda dan kami akan mengirimkan link untuk reset password.</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
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
                            <div class="form-text">Kami akan mengirimkan link reset password ke email ini</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Link Reset Password
                        </button>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Login
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

            <!-- Help Section -->
            <div class="help-section mt-4">
                <div class="card border-info">
                    <div class="card-body text-center">
                        <i class="fas fa-info-circle text-info mb-2"></i>
                        <h6 class="card-title">Butuh Bantuan?</h6>
                        <p class="card-text small">Jika Anda tidak menerima email dalam beberapa menit, periksa folder spam atau junk email Anda.</p>
                        <a href="{{ route('contact') }}" class="btn btn-outline-info btn-sm">
                            Hubungi Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.forgot-icon {
    width: 80px;
    height: 80px;
    background: rgba(37, 99, 235, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.help-section {
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

.btn-primary {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border: none;
    padding: 0.75rem;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    transform: translateY(-1px);
}

.alert-success {
    border-left: 4px solid #10b981;
    background-color: #f0fdf4;
    border-color: #bbf7d0;
    color: #065f46;
}

@media (max-width: 576px) {
    .card-body {
        padding: 2rem 1.5rem !important;
    }
    
    .forgot-icon {
        width: 60px;
        height: 60px;
    }
    
    .forgot-icon i {
        font-size: 2rem !important;
    }
}
</style>
@endpush