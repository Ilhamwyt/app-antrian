<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Antrian Digital - Dashboard</title>
    <!-- Load Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Load Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Menggunakan Poppins sebagai font utama */
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Tambahkan transisi untuk sidebar di mode mobile */
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }
        @media (max-width: 767px) {
            .sidebar {
                position: fixed;
                z-index: 50;
                height: 100%;
                top: 0;
                left: 0;
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
        }

        /* Gaya tambahan untuk kartu dashboard agar terlihat lebih premium */
        .dashboard-card {
            transition: all 0.3s ease-in-out;
        }
        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 30px -10px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen">
        
        <!-- Sidebar (Kiri) -->
<div id="sidebar" class="sidebar w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
    <!-- Logo/Judul Aplikasi -->
    <div class="p-6 border-b border-slate-700">
        <div class="flex items-center">
            <img src="{{ asset('images/logout.png') }}" alt="Logo Universitas Terbuka" class="w-15 h-10 rounded-lg mr-3">
            <h1 class="text-base font-bold whitespace-nowrap">Universitas Terbuka</h1>
        </div>
    </div>
    
    <!-- Navigasi Utama -->
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="nav-link group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 ease-in-out
            {{ request()->routeIs('dashboard') ? 'bg-slate-800/50 text-blue-400 border-l-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
            <i class="fas fa-home mr-3 text-lg w-5 {{ request()->routeIs('dashboard') ? 'text-blue-400' : 'text-slate-400 group-hover:text-white' }}"></i>
            <span>Dashboard</span>
        </a>
        
        <!-- Manajemen Loket -->
        <a href="{{ route('manajemenLoket') }}" class="nav-link group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 ease-in-out
            {{ request()->routeIs('manajemenLoket') ? 'bg-slate-800/50 text-blue-400 border-l-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
            <i class="fas fa-th-large mr-3 text-lg w-5 {{ request()->routeIs('manajemenLoket') ? 'text-blue-400' : 'text-slate-400 group-hover:text-white' }}"></i>
            <span>Manajemen Loket</span>
        </a>
        
        <!-- Layanan -->
        <a href="{{ route('layanan.index') }}" class="nav-link group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 ease-in-out
            {{ request()->routeIs('layanan.*') ? 'bg-slate-800/50 text-blue-400 border-l-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
            <i class="fas fa-list-alt mr-3 text-lg w-5 {{ request()->routeIs('layanan.*') ? 'text-blue-400' : 'text-slate-400 group-hover:text-white' }}"></i>
            <span>Layanan</span>
        </a>
        
        <!-- Laporan -->
        <a href="{{ route('admin.laporan.index') }}" class="nav-link group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 ease-in-out
            {{ request()->routeIs('admin.laporan.index') ? 'bg-slate-800/50 text-blue-400 border-l-4 border-blue-500' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
            <i class="fas fa-chart-bar mr-3 text-lg w-5 {{ request()->routeIs('admin.laporan.index') ? 'text-blue-400' : 'text-slate-400 group-hover:text-white' }}"></i>
            <span>Laporan</span>
        </a>
    </nav>
    
    <!-- Logout -->
    <div class="p-4 border-t border-slate-700">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="group flex items-center w-full px-4 py-3 text-sm font-medium text-slate-400 rounded-lg hover:bg-slate-800/50 hover:text-white transition-all duration-200 ease-in-out">
                <i class="fas fa-sign-out-alt mr-3 text-lg w-5 text-slate-400 group-hover:text-white"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>

        
        <!-- Main Content Wrapper (Kanan) -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center justify-between px-4 sm:px-6 py-4">
                    <div class="flex items-center">
                        <button id="sidebarToggle" class="text-gray-500 hover:text-gray-700 focus:outline-none md:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <!-- page_title (Dashboard) -->
                        <h1 class="ml-4 text-2xl font-semibold text-gray-800">@yield('page_title', 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div id="realtime-clock" class="text-sm font-medium text-gray-600 hidden sm:block">
                            <i class="far fa-clock mr-2"></i>
                            <span id="current-time">00:00:00</span>
                        </div>
                        
                        <!-- User Profile Dropdown -->
                        <div class="relative">
                            <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Open user menu</span>
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                            </button>
                            <!-- Dropdown Profile -->
                            <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                                <div class="py-1" role="none">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Profil Saya</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Pengaturan</a>
                                    <form action="{{ route('logout') }}" method="POST" role="none">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Keluar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        // Toggle sidebar in mobile view
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebar').classList.toggle('hidden');
        });
        
        // Fungsi untuk menampilkan jam realtime
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds}`;
        }
        
        // Update jam setiap detik
        updateClock();
        setInterval(updateClock, 1000);

        // Simple dropdown for user menu
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.querySelector('#user-menu-button + div');

        userMenuButton.addEventListener('click', function(event) {
            event.preventDefault();
            userMenu.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>