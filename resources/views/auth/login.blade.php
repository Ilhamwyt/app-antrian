<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Antrian Digital</title>
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
            --color-primary: #1e3a8a; /* Biru Tua - Blue-800 */
        }
        .bg-primary { background-color: var(--color-primary); }
        .text-primary { color: var(--color-primary); }
        .focus\:ring-primary:focus { --tw-ring-color: var(--color-primary); }
        .border-primary { border-color: var(--color-primary); }
        
        /* Animasi sederhana untuk elemen utama */
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-gray-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 animate-fade-in">
        <!-- Card Login Utama -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 sm:p-10 space-y-8 border border-gray-100">
            <!-- Logo dan Judul -->
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <!-- Ikon Antrian -->
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTfrdsbep7iJmgnurZys2VhxUURu1uocZ7YFQ&s" alt="Logo UT" class="w-12 h-12 rounded-full shadow-md object-cover" />
                </div>
                <h2 class="mt-4 text-2xl font-extrabold text-gray-900">
                    Selamat Datang Kembali
                </h2>
            </div>
            
            <!-- Form Login -->
            <form class="space-y-6" method="POST" action="{{ route('login') }}">

                @csrf
                
                <!-- Email Input -->
                <div class="space-y-1">
                    <label for="email" class="block text-sm font-semibold text-gray-700">
                        Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" required 
                            class="pl-12 w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 shadow-sm @error('email') border-red-500 @enderror" 
                            placeholder="Email Anda"
                            value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password Input -->
                <div class="space-y-1">
                    <label for="password" class="block text-sm font-semibold text-gray-700">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" required 
                            class="pl-12 w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 shadow-sm @error('password') border-red-500 @enderror" 
                            placeholder="••••••••">
                        <!-- Toggle Password Button -->
                        <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center focus:outline-none" onclick="togglePassword()">
                            <i id="toggleIcon" class="fas fa-eye text-gray-400 hover:text-gray-600 transition-colors"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button id="loginButton" type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-lg font-bold rounded-xl text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-primary transition-all duration-300 shadow-lg transform hover:scale-[1.01]">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                            <i class="fas fa-sign-in-alt text-white group-hover:text-blue-200 transition-colors"></i>
                        </span>
                        Masuk
                    </button>
                </div>
            </form>
        </div>
        <!-- Link Kembali -->
        <div class="text-center text-sm">
            <a href="{{ url('/') }}" class="font-medium text-gray-600 hover:text-primary transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Halaman Utama
            </a>
        </div>
    </div>

    <!-- Modal Pesan (Pengganti alert()) -->
    <div id="messageModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 w-full max-w-sm shadow-2xl text-center transform scale-95 transition-transform duration-300">
            <h3 id="modalTitle" class="text-xl font-bold mb-3 text-red-600">Login Gagal</h3>
            <p id="modalMessage" class="text-gray-700 mb-6">Email atau password yang Anda masukkan salah. Silakan coba lagi.</p>
            <button onclick="document.getElementById('messageModal').classList.add('hidden')" class="w-full py-2 bg-primary text-white rounded-lg font-semibold hover:bg-blue-700 transition">Tutup</button>
        </div>
    </div>

    <script>
        // Fungsi untuk menampilkan/menyembunyikan password
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
