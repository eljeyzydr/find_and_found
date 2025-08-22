.emoji {
        font-size: 2rem;
        margin-bottom: 1rem;
        display: inline-block;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
    }@extends('layouts.app')

@section('title', 'Find and Found - Selamat Datang')

@section('content')
<style>
    :root {
        --primary-orange: #FE9346;
        --light-white: #FDFDFF;
        --pure-black: #000000;
        --orange-light: #FEB576;
        --orange-dark: #E8843D;
        --shadow-color: rgba(254, 147, 70, 0.2);
    }

    .hero-section {
        background: linear-gradient(135deg, var(--light-white) 0%, #FEF4EC 100%);
        border: 2px solid var(--primary-orange);
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(254, 147, 70, 0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .display-4 {
        color: var(--pure-black);
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(254, 147, 70, 0.3);
    }

    .display-4 strong {
        color: var(--primary-orange);
        background: linear-gradient(45deg, var(--primary-orange), var(--orange-dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .lead {
        color: var(--pure-black);
        font-size: 1.3rem;
        font-weight: 500;
    }

    .custom-divider {
        border: none;
        height: 3px;
        background: linear-gradient(90deg, transparent, var(--primary-orange), transparent);
        margin: 2rem 0;
    }

    .btn-custom-primary {
        background: linear-gradient(135deg, var(--primary-orange), var(--orange-dark));
        border: none;
        color: var(--light-white);
        font-weight: 600;
        padding: 12px 30px;
        border-radius: 50px;
        box-shadow: 0 4px 15px var(--shadow-color);
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-custom-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px var(--shadow-color);
        background: linear-gradient(135deg, var(--orange-dark), var(--primary-orange));
        color: var(--light-white);
    }

    .btn-custom-outline {
        background: transparent;
        border: 2px solid var(--primary-orange);
        color: var(--primary-orange);
        font-weight: 600;
        padding: 10px 28px;
        border-radius: 50px;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-custom-outline:hover {
        background: var(--primary-orange);
        color: var(--light-white);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px var(--shadow-color);
    }

    .feature-card {
        background: var(--light-white);
        border: 1px solid rgba(254, 147, 70, 0.2);
        border-radius: 15px;
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-orange), var(--orange-light));
        transition: all 0.5s ease;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(254, 147, 70, 0.15);
        border-color: var(--primary-orange);
    }

    .feature-card:hover::before {
        left: 0;
    }

    .feature-card .card-body {
        padding: 2rem;
    }

    .feature-card h3 {
        color: var(--pure-black);
        font-weight: 700;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .feature-card p {
        color: #666;
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .welcome-text {
        color: var(--pure-black);
        font-size: 1.2rem;
        font-weight: 500;
    }

    .welcome-text strong {
        color: var(--primary-orange);
    }

    .emoji {
        font-size: 2rem;
        margin-bottom: 1rem;
        display: inline-block;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
    }

    .container {
        max-width: 1200px;
    }

    @media (max-width: 768px) {
        .hero-section {
            margin: 1rem;
            padding: 2rem 1rem;
        }
        
        .display-4 {
            font-size: 2rem;
        }
        
        .btn-custom-primary,
        .btn-custom-outline {
            display: block;
            margin: 0.5rem auto;
            max-width: 200px;
        }
    }
</style>

<div class="container mt-4">
    <div class="hero-section text-center p-5 shadow-lg">
        <div class="hero-content">
            <h1 class="display-4 mb-4">
                Selamat Datang di <strong>Find and Found</strong>
            </h1>
            <p class="lead mb-4">
                Platform inovatif untuk membantu orang menemukan barang hilang atau melaporkan barang yang ditemukan dengan mudah dan aman.
            </p>
            <hr class="custom-divider">
            @guest
                <p class="welcome-text mb-4">
                    Mulai perjalanan Anda dengan membuat akun atau login untuk memposting barang hilang/ditemukan.
                </p>
                <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                    <a class="btn btn-custom-primary btn-lg" href="{{ route('register') }}" role="button">
                        <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                    </a>
                    <a class="btn btn-custom-outline btn-lg" href="{{ route('login') }}" role="button">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </a>
                </div>
            @else
                <p class="welcome-text mb-4">
                    Hai <strong>{{ Auth::user()->name }}</strong>! Selamat datang kembali. Yuk cek postingan terbaru atau bagikan temuan Anda.
                </p>
                <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                    <a class="btn btn-custom-primary btn-lg" href="{{ route('items.index') }}" role="button">
                        <i class="fas fa-search me-2"></i>Lihat Barang
                    </a>
                    <a class="btn btn-custom-outline btn-lg" href="{{ route('items.create') }}" role="button">
                        <i class="fas fa-plus me-2"></i>Posting Barang
                    </a>
                </div>
            @endguest
        </div>
    </div>

    <div class="row mt-5 g-4">
        <div class="col-md-4">
            <div class="feature-card shadow-sm">
                <div class="card-body text-center">
                    <h3>Cari Barang</h3>
                    <p>Gunakan fitur pencarian canggih & filter lokasi untuk menemukan barang Anda dengan cepat dan efisien.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card shadow-sm">
                <div class="card-body text-center">
                    <h3>Laporkan Ditemukan</h3>
                    <p>Posting barang yang Anda temukan dengan mudah agar pemilik bisa segera mengambilnya kembali.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card shadow-sm">
                <div class="card-body text-center">
                    <h3>Chat & Komentar</h3>
                    <p>Hubungi pemilik atau penemu barang dengan aman dan nyaman melalui sistem komunikasi terintegrasi.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection