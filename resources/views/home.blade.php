<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Meta tags dasar untuk konfigurasi halaman -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Judul halaman -->
    <title>Universitas Terbuka - Sistem Antrian Digital</title>
    
    <!-- Load Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Load Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts (Poppins) untuk tipografi -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Konfigurasi dan Gaya Kustom -->
    <style>
        /* Menggunakan Poppins sebagai font utama */
        body {
            font-family: 'Poppins', sans-serif;
        }
        /* Mengatur kursor pada elemen interaktif */
        .cursor-pointer, button {
            cursor: pointer;
        }
        /* Custom scrollbar for better look */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-thumb {
            background: #a8a8a8;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
    </style>
</head>

<body class="antialiased bg-gray-50 text-gray-900">
    <!-- Navbar - Navigasi utama di bagian atas halaman -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo & Title - Branding Universitas Terbuka -->
                <div class="flex items-center space-x-4">
                    <!-- Logo UT - Menggunakan placeholder image -->
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTfrdsbep7iJmgnurZys2VhxUURu1uocZ7YFQ&s" alt="Logo UT" class="w-12 h-12 rounded-full shadow-md object-cover" />
                    <div>
                        <h1 class="text-2xl font-extrabold text-black-900">UNIVERSITAS TERBUKA</h1>
                        <p class="text-sm text-gray-500 font-medium">Sistem Antrian Digital</p>
                    </div>
                </div>

                <!-- Login Button - Tombol untuk masuk ke sistem -->
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-blue-800 rounded-xl shadow-lg hover:bg-blue-900 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-sign-in-alt w-4 h-4 mr-2"></i>
                    Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content - Konten utama halaman -->
    <main class="min-h-screen py-24 bg-gradient-to-br from-white to-blue-50">
        <div class="max-w-7xl mx-auto px-8">
            <!-- Hero Section - Bagian pembuka dengan judul besar -->
            <div class="text-center mb-24">
                <h2 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-6 tracking-tight">Layanan Antrian Digital</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto font-light">
                    Sistem antrian modern untuk kemudahan dan efisiensi layanan akademik di Universitas Terbuka.
                </p>
            </div>

            <!-- Service Cards - Kartu layanan utama -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                
                <!-- Card 1: Panggil Antrian (Untuk Petugas/Operator) -->
                <div onclick="openModal('modalLoket')" class="bg-white rounded-3xl shadow-xl border border-blue-100 p-10 text-center hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 cursor-pointer">
                    <!-- Ikon -->
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                        <i class="fas fa-volume-up text-3xl text-blue-700"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Panggil Antrian</h3>
                    <p class="text-gray-600 mb-8 font-light">Akses cepat untuk petugas dalam mengelola nomor antrian.</p>
                    <button onclick="openModal('modalLoket')" class="inline-flex items-center justify-center px-6 py-3 w-full text-sm font-semibold text-white bg-blue-800 rounded-xl shadow-lg hover:bg-blue-900 transition-all duration-300">Masuk</button>
                </div>

                <!-- Card 2: Ambil Antrian (Untuk Pengunjung/Mahasiswa) -->
                <div onclick="openModal('modalQueue')" class="bg-white rounded-3xl shadow-xl border border-blue-100 p-10 text-center hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 cursor-pointer">
                    <!-- Ikon -->
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                        <i class="fas fa-ticket-alt text-3xl text-blue-700"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Ambil Antrian</h3>
                    <p class="text-gray-600 mb-8 font-light">Dapatkan nomor antrian Anda dengan mudah dan cepat.</p>
                    <button onclick="openModal('modalQueue')" class="inline-flex items-center justify-center px-6 py-3 w-full text-sm font-semibold text-white bg-blue-800 rounded-xl shadow-lg hover:bg-blue-900 transition-all duration-300">Masuk</button>
                </div>

                <!-- Card 3: Monitor Antrian (Untuk Layar Monitor) -->
                <div class="bg-white rounded-3xl shadow-xl border border-blue-100 p-10 text-center hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 cursor-pointer">
                    <!-- Ikon -->
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                        <i class="fas fa-desktop text-3xl text-blue-700"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Monitor Antrian</h3>
                    <p class="text-gray-600 mb-8 font-light">Pantau status, nomor antrian, dan loket yang aktif secara real-time.</p>
                    <button onclick="console.log('Navigasi ke halaman monitor antrian...')" class="inline-flex items-center justify-center px-6 py-3 w-full text-sm font-semibold text-white bg-blue-800 rounded-xl shadow-lg hover:bg-blue-900 transition-all duration-300">Lihat Monitor</button>
                </div>
            </div>
        </div>
    </main>
    

    <!-- MODAL 1: Panggil Antrian (Untuk Operator/Petugas) -->
    <div id="modalLoket" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden transition-opacity duration-300">
        <div class="bg-white rounded-2xl p-8 w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300">
            <!-- Tombol close -->
            <button onclick="closeModal('modalLoket')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 p-2 rounded-full hover:bg-gray-100 transition-colors">
                <i class="fas fa-times w-5 h-5"></i>
            </button>
            
            <!-- Judul dan deskripsi modal -->
            <h2 class="text-3xl font-extrabold mb-2 text-blue-800">Panggil Antrian</h2>
            <p class="text-gray-600 mb-6">Pilih loket untuk memulai pelayanan.</p>
            
            <!-- Daftar loket dari database -->
            <div class="grid grid-cols-1 gap-4 mb-6" id="loket-panggil-list">
                @forelse($counters as $counter)
                <div class="bg-blue-50 p-4 rounded-xl flex justify-between items-center border-2 border-blue-200">
                    <div>
                        <span class="font-bold text-xl text-blue-800 block">{{ $counter->nama_loket }}</span>
                        <span class="text-sm text-gray-600">Layanan {{ $counter->nama_loket }}</span>
                    </div>
                    <a href="{{ route('loket.show', $counter->id) }}" class="text-sm px-4 py-2 bg-blue-800 text-white rounded-lg font-semibold shadow-md hover:bg-blue-900 transition-colors">Pilih & Masuk</a>
                </div>
                @empty
                <div class="bg-yellow-50 p-4 rounded-xl border-2 border-yellow-200 text-center">
                    <span class="text-yellow-700">Belum ada loket yang tersedia</span>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- MODAL 2: Ambil Antrian (Untuk Pengunjung/Mahasiswa) -->
    <div id="modalQueue" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden transition-opacity duration-300">
        <div class="bg-white rounded-2xl p-8 w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300">
            <!-- Tombol close -->
            <button onclick="closeModal('modalQueue')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 p-2 rounded-full hover:bg-gray-100 transition-colors">
                <i class="fas fa-times w-5 h-5"></i>
            </button>
            
            <!-- Judul dan deskripsi modal -->
            <h2 class="text-3xl font-extrabold mb-2 text-blue-800">Ambil Nomor Antrian</h2>
            <p class="text-gray-600 mb-6">Silakan pilih loket yang Anda butuhkan:</p>
            
            <!-- Daftar loket dari database untuk pengambilan antrian -->
            <div class="grid grid-cols-1 gap-4 mb-6" id="loket-queue-list">
                @forelse($counters as $index => $counter)
                <?php $queueCode = chr(65 + $index); ?>
                <button onclick="takeQueue('{{ $queueCode }}', '{{ $counter->nama_loket }}');" data-counter-id="{{ $counter->id }}" class="flex justify-between items-center p-4 bg-white border-2 border-blue-300 rounded-xl shadow-lg hover:bg-blue-50 transition-all text-left">
                    <div>
                        <span class="block text-xl font-bold text-blue-800">{{ $counter->nama_loket }}</span>
                    </div>
                    <i class="fas fa-arrow-right text-2xl text-blue-500"></i>
                </button>
                @empty
                <div class="bg-yellow-50 p-4 rounded-xl border-2 border-yellow-200 text-center">
                    <span class="text-yellow-700">Belum ada loket yang tersedia</span>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- MODAL 3: Nomor Antrian (Hasil Pengambilan) -->
    <div id="modalQueueNumber" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden transition-opacity duration-300">
        <div class="bg-white rounded-2xl p-8 w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300">
            <!-- Tombol close -->
            <button onclick="closeModal('modalQueueNumber')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 p-2 rounded-full hover:bg-gray-100 transition-colors">
                <i class="fas fa-times w-5 h-5"></i>
            </button>
            
            <div class="text-center">
                <!-- Judul modal -->
                <h2 class="text-3xl font-extrabold mb-2 text-blue-800">Nomor Antrian Anda</h2>
                <p class="text-gray-600 mb-6">Silakan tunggu di ruang tunggu sampai nomor Anda dipanggil.</p>
                
                <!-- Tampilan nomor antrian -->
                <div class="bg-blue-100 rounded-2xl p-8 mb-6 border-4 border-blue-300 shadow-inner">
                    <p class="text-sm font-semibold text-blue-800" id="display-loket-name">Loket Layanan</p>
                    <div class="text-8xl font-black text-blue-900 animate-pulse mt-2" id="display-queue-number">A001</div>
                </div>
                
                <!-- Informasi tambahan (Dummy data) -->
                <div class="text-base text-gray-600 mb-8 p-3 bg-gray-50 rounded-lg">
                    <p class="font-semibold text-blue-700 mb-1">Anda berada di antrian ke- <span id="queue-count" class="font-extrabold text-red-600">6</span></p>
                    <p class="text-sm">Estimasi waktu tunggu: <span class="font-medium">10-20 menit</span></p>
                </div>
                
                <!-- Tombol tutup -->
                <button onclick="closeModal('modalQueueNumber')" class="w-full bg-blue-800 text-white py-3 rounded-xl font-semibold hover:bg-blue-900 transition shadow-lg">Tutup</button>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Modal dan Antrian -->
    <script>
        // Fungsi untuk membuka modal
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            setTimeout(() => {
                document.getElementById(modalId).querySelector('.transform').classList.remove('scale-95');
                document.getElementById(modalId).querySelector('.transform').classList.add('scale-100');
            }, 10);
        }

        // Fungsi untuk menutup modal
        function closeModal(modalId) {
            document.getElementById(modalId).querySelector('.transform').classList.remove('scale-100');
            document.getElementById(modalId).querySelector('.transform').classList.add('scale-95');
            setTimeout(() => {
                document.getElementById(modalId).classList.add('hidden');
            }, 300);
        }

        // Fungsi untuk mengambil nomor antrian
        function takeQueue(queueType, loketName) {
            // Dapatkan counter_id dari tombol yang diklik
            const counterIdElement = document.querySelector(`button[onclick="takeQueue('${queueType}', '${loketName}');"]`);
            const counterId = counterIdElement.getAttribute('data-counter-id');
            
            // Tutup modal queue dan buka modal hasil
            closeModal('modalQueue');
            openModal('modalQueueNumber');
            
            // Tampilkan loading di modal
            document.getElementById('display-queue-number').innerText = 'Mengambil nomor...';
            document.getElementById('display-loket-name').innerText = loketName;
            
            // Panggil API untuk mengambil nomor antrian
            fetch('/api/queue/take', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    counter_id: counterId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Tampilkan nomor antrian
                    document.getElementById('display-queue-number').innerText = data.queue_number;
                    
                    // Hapus tombol lama jika sudah ada
                    const existingButton = document.querySelector('#modalQueueNumber a.bg-green-600');
                    if (existingButton) {
                        existingButton.remove();
                    }
                    
                    // Tambahkan tombol untuk melihat status antrian
                    const viewStatusButton = document.createElement('a');
                    viewStatusButton.href = `/loket/${counterId}?queue_number=${data.queue_number}`;
                    viewStatusButton.className = 'w-full bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition shadow-lg mt-4 block text-center';
                    viewStatusButton.innerText = 'Lihat Status Antrian';
                    
                    // Tambahkan tombol setelah tombol tutup
                    const closeButton = document.querySelector('#modalQueueNumber button.w-full');
                    closeButton.parentNode.insertBefore(viewStatusButton, closeButton.nextSibling);
                    
                    // Update jumlah antrian
                    fetch(`/api/queue/counter/${counterId}`)
                        .then(response => response.json())
                        .then(queueData => {
                            const waitingCount = queueData.filter(q => q.status === 'waiting').length;
                            document.getElementById('queue-count').innerText = waitingCount;
                        });
                } else {
                    // Tampilkan pesan error
                    document.getElementById('display-queue-number').innerText = 'Error';
                    alert('Gagal mengambil nomor antrian');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('display-queue-number').innerText = 'Error';
                alert('Terjadi kesalahan saat mengambil nomor antrian');
            });
        }

        // Event listener untuk tombol reset
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('confirmResetButton')) {
                document.getElementById('confirmResetButton').addEventListener('click', function() {
                    // Reset semua nomor antrian (simulasi)
                    const queueElements = document.querySelectorAll('[id^="current-queue-"]');
                    queueElements.forEach(element => {
                        const queueType = element.id.split('-').pop();
                        element.innerText = queueType + '000';
                    });
                    
                    // Tutup modal konfirmasi
                    closeModal('modalResetConfirmation');
                    
                    // Tampilkan pesan sukses (opsional)
                    alert('Semua nomor antrian telah direset!');
                });
            }
        });
    </script>
</body>
</html>
