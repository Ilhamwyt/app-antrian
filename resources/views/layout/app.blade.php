<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Antriann Digital- Dashboard</title>
    <!-- Load Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Load Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* Menggunakan Poppins sebagai font utama */
        body {
            font-family: 'Poppins', sans-serif;
        }
        /* Konfigurasi warna kustom Tailwind */
        :root {
            --color-primary: #2563eb; /* Biru terang untuk aksi */
        }
        .bg-primary { background-color: var(--color-primary); }
        .hover\:bg-primary:hover { background-color: var(--color-primary); }
        .text-primary { color: var(--color-primary); }
        
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
            box-shadow: 0 15px 30px -10px rgba(0,0,0,0.2); /* Bayangan lebih kuat saat hover */
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen">
        
        <!-- Sidebar (Kiri) -->
<div id="sidebar" class="sidebar w-64 bg-slate-800 text-white flex flex-col hidden md:flex">
    <!-- Logo/Judul Aplikasi -->
    <div class="p-6 border-b border-slate-700">
        <div class="flex items-center">
            <i class="fas fa-clipboard-list text-primary text-2xl mr-3"></i>
            <span class="text-sm font-extrabold tracking-wide">Universtitas Terbuka</span>
        </div>
    </div>
    
    <!-- Navigasi Utama -->
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
        
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700' }} transition-colors duration-200">
            <i class="fas fa-home mr-3 text-lg"></i>
            <span>Dashboard</span>
        </a>
        
        <!-- Manajemen Loket -->
        <a href="{{ route('manajemenLoket') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('manajemenLoket') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700' }} transition-colors duration-200">
            <i class="fas fa-th-large mr-3 text-lg"></i>
            <span>Manajemen Loket</span>
        </a>
        
        <!-- Layanan -->
        <a href="{{ route('layanan.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('layanan.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700' }} transition-colors duration-200">
            <i class="fas fa-list-alt mr-3 text-lg"></i>
            <span>Layanan</span>
        </a>
        
        <!-- Laporan -->
        <a href="{{ route('admin.laporan.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('laporan') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-700' }} transition-colors duration-200">
            <i class="fas fa-chart-bar mr-3 text-lg"></i>
            <span>Laporan</span>
        </a>
    </nav>
    
    <!-- Logout -->
    <div class="p-4 border-t border-slate-700">
        <!-- Simulasi Form Logout (Gunakan URL #logout dan metode POST) -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-3 text-sm font-medium text-slate-300 rounded-lg hover:bg-red-600 hover:text-white transition-colors duration-200">
                <i class="fas fa-sign-out-alt mr-3 text-lg"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>

        
        <!-- Main Content Wrapper (Kanan) -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="bg-white shadow-lg sticky top-0 z-30">
                <div class="flex items-center justify-between px-4 sm:px-6 py-4">
                    <div class="flex items-center">
                        <button id="sidebarToggle" class="text-gray-500 hover:text-gray-700 focus:outline-none md:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <!-- page_title (Dashboard) -->
                        <h1 class="ml-4 text-2xl font-bold text-gray-800">@yield('page_title', 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center">
                        <div id="realtime-clock" class="text-lg font-semibold text-gray-700">
                            <i class="far fa-clock mr-2"></i>
                            <span id="current-time">00:00:00</span>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6">
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
    </script>
</body>
</html>
