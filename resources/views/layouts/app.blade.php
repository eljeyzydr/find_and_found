<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Find & Found - Platform Barang Hilang & Ditemukan')</title>
    <meta name="description" content="@yield('description', 'Platform untuk membantu menghubungkan orang yang kehilangan barang dengan yang menemukannya')">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Leaflet CSS (for maps) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #06b6d4;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .card {
            border: none;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem;
        }

        .status-lost {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .status-found {
            background-color: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.75rem;
            min-width: 18px;
            text-align: center;
        }

        .item-card {
            transition: transform 0.2s;
        }

        .item-card:hover {
            transform: translateY(-2px);
        }

        .chat-message {
            max-width: 70%;
            margin-bottom: 1rem;
        }

        .chat-message.sent {
            margin-left: auto;
            background-color: var(--primary-color);
            color: white;
        }

        .chat-message.received {
            background-color: #e5e7eb;
            color: #374151;
        }

        .map-container {
            height: 400px;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .map-container {
                height: 300px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-search me-2"></i>Find & Found
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('items.index') }}">Cari Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('browse') }}">Jelajahi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('map') }}">Peta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}">Kategori</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <!-- Notifications -->
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                @if(auth()->user()->getUnreadNotificationsCount() > 0)
                                    <span class="notification-badge">{{ auth()->user()->getUnreadNotificationsCount() }}</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                                <li><h6 class="dropdown-header">Notifikasi</h6></li>
                                <!-- TODO: Load recent notifications via AJAX -->
                                <li><a class="dropdown-item" href="{{ route('notifications.index') }}">Lihat Semua</a></li>
                            </ul>
                        </li>
                        
                        <!-- Messages -->
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('chats.index') }}">
                                <i class="fas fa-comments"></i>
                                @if(auth()->user()->getUnreadChatsCount() > 0)
                                    <span class="notification-badge">{{ auth()->user()->getUnreadChatsCount() }}</span>
                                @endif
                            </a>
                        </li>
                        
                        <!-- Add Item -->
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm me-2" href="{{ route('items.create') }}">
                                <i class="fas fa-plus me-1"></i>Tambah Item
                            </a>
                        </li>
                        
                        <!-- User Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="rounded-circle me-1" width="24" height="24">
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('items.my-items') }}"><i class="fas fa-box me-2"></i>Item Saya</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                                @if(auth()->user()->isAdmin())
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-cog me-2"></i>Admin Panel</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
            <div class="container">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
            <div class="container">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show m-0" role="alert">
            <div class="container">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-search me-2"></i>Find & Found</h5>
                    <p class="text-">Platform untuk membantu menghubungkan orang yang kehilangan barang dengan yang menemukannya dengan aman dan mudah.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>Navigasi</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-light text-decoration-none">Beranda</a></li>
                        <li><a href="{{ route('items.index') }}" class="text-light text-decoration-none">Cari Barang</a></li>
                        <li><a href="{{ route('browse') }}" class="text-light text-decoration-none">Jelajahi</a></li>
                        <li><a href="{{ route('map') }}" class="text-light text-decoration-none">Peta</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>Bantuan</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('faq') }}" class="text-light text-decoration-none">FAQ</a></li>
                        <li><a href="{{ route('contact') }}" class="text-light text-decoration-none">Kontak</a></li>
                        <li><a href="{{ route('about') }}" class="text-light text-decoration-none">Tentang</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>Legal</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('terms') }}" class="text-light text-decoration-none">Syarat & Ketentuan</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-light text-decoration-none">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>Sosial Media</h6>
                    <div>
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-light mb-0">&copy; {{ date('Y') }} Find & Found. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-light mb-0">Made with <i class="fas fa-heart text-danger"></i> in Indonesia</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // CSRF Token Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);

        // Notification polling (for real-time updates)
        @auth
        function pollNotifications() {
            // TODO: Implement notification polling
        }
        
        // Poll every 30 seconds
        setInterval(pollNotifications, 30000);
        @endauth
    </script>
    
    @stack('scripts')
</body>
</html>