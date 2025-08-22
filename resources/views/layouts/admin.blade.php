<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Find & Found')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Admin CSS -->
    <style>
        :root {
            --admin-primary: #3b82f6;
            --admin-secondary: #6b7280;
            --admin-success: #10b981;
            --admin-danger: #ef4444;
            --admin-warning: #f59e0b;
            --admin-info: #06b6d4;
            --admin-dark: #1f2937;
            --admin-sidebar: #374151;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }

        .admin-sidebar {
            background-color: var(--admin-sidebar);
            min-height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s;
        }

        .admin-sidebar .nav-link {
            color: #d1d5db;
            padding: 0.75rem 1.5rem;
            border-radius: 0;
            transition: all 0.2s;
        }

        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            background-color: rgba(59, 130, 246, 0.1);
            color: #60a5fa;
        }

        .admin-sidebar .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }

        .admin-content {
            margin-left: 250px;
            padding: 1.5rem;
            transition: all 0.3s;
        }

        .admin-header {
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 1.5rem;
            margin: -1.5rem -1.5rem 1.5rem -1.5rem;
        }

        .stats-card {
            background: linear-gradient(135deg, var(--admin-primary), #2563eb);
            color: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: none;
        }

        .stats-card.success {
            background: linear-gradient(135deg, var(--admin-success), #059669);
        }

        .stats-card.warning {
            background: linear-gradient(135deg, var(--admin-warning), #d97706);
        }

        .stats-card.danger {
            background: linear-gradient(135deg, var(--admin-danger), #dc2626);
        }

        .table-actions .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                margin-left: -250px;
            }
            
            .admin-sidebar.show {
                margin-left: 0;
            }
            
            .admin-content {
                margin-left: 0;
            }
        }

        .sidebar-toggle {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar-toggle {
                display: inline-block;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="admin-sidebar" id="adminSidebar">
        <div class="p-3">
            <h5 class="text-white mb-0">
                <i class="fas fa-cogs me-2"></i>Admin Panel
            </h5>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <hr class="dropdown-divider mx-3 my-2" style="border-color: #4b5563;">
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i>Kelola User
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.items.*') ? 'active' : '' }}" href="{{ route('admin.items.index') }}">
                    <i class="fas fa-box"></i>Kelola Item
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-tags"></i>Kelola Kategori
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                    <i class="fas fa-flag"></i>Laporan Abuse
                    @if(\App\Models\Report::pending()->count() > 0)
                        <span class="badge bg-danger ms-2">{{ \App\Models\Report::pending()->count() }}</span>
                    @endif
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}" href="{{ route('admin.comments.index') }}">
                    <i class="fas fa-comments"></i>Kelola Komentar
                    @if(\App\Models\Comment::pending()->count() > 0)
                        <span class="badge bg-warning ms-2">{{ \App\Models\Comment::pending()->count() }}</span>
                    @endif
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.chats.*') ? 'active' : '' }}" href="{{ route('admin.chats.index') }}">
                    <i class="fas fa-envelope"></i>Monitor Chat
                </a>
            </li>
            
            <li class="nav-item">
                <hr class="dropdown-divider mx-3 my-2" style="border-color: #4b5563;">
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.statistics') ? 'active' : '' }}" href="{{ route('admin.statistics') }}">
                    <i class="fas fa-chart-bar"></i>Statistik
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}" href="{{ route('admin.logs') }}">
                    <i class="fas fa-history"></i>Log Aktivitas
                </a>
            </li>
            
            <li class="nav-item">
                <hr class="dropdown-divider mx-3 my-2" style="border-color: #4b5563;">
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}" target="_blank">
                    <i class="fas fa-external-link-alt"></i>Lihat Website
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-user"></i>User Dashboard
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="admin-content">
        <!-- Header -->
        <div class="admin-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary sidebar-toggle me-3" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
            </div>
            
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="rounded-circle me-1" width="24" height="24">
                        {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Custom Admin JS -->
    <script>
        // CSRF Token Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Sidebar Toggle
        function toggleSidebar() {
            $('#adminSidebar').toggleClass('show');
        }

        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);

        // Confirm Delete
        $('.btn-delete').click(function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });

        // Bulk Actions
        $('#selectAll').change(function() {
            $('.item-checkbox').prop('checked', this.checked);
            toggleBulkActions();
        });

        $('.item-checkbox').change(function() {
            toggleBulkActions();
        });

        function toggleBulkActions() {
            const checked = $('.item-checkbox:checked').length;
            $('#bulkActions').toggle(checked > 0);
            $('#selectedCount').text(checked);
        }
    </script>
    
    @stack('scripts')
</body>
</html>