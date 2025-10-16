<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Meta tags dasar untuk konfigurasi halaman -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Judul halaman -->
    <title>Universitas Terbuka - Sistem Antrian Digital | Loket {{ $counter->nama_loket }}</title>
    
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
        /* Animasi untuk panggilan antrian */
        @keyframes pulse-border {
            0% { border-color: #3b82f6; }
            50% { border-color: #60a5fa; }
            100% { border-color: #3b82f6; }
        }
        .animate-border-pulse {
            animation: pulse-border 2s infinite;
        }
        
        /* Animasi untuk nomor antrian */
        @keyframes number-pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .animate-number {
            animation: number-pulse 2s infinite;
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

                <!-- Informasi Loket -->
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <h2 class="text-xl font-bold text-blue-800">{{ $counter->nama_loket }}</h2>
                    </div>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-xl shadow-lg hover:bg-red-700 transition-all duration-300">
                        <i class="fas fa-sign-out-alt w-4 h-4 mr-2"></i>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content - Konten utama halaman -->
    <main class="py-10 bg-gradient-to-br from-white to-blue-50">
        <div class="max-w-7xl mx-auto px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Kolom 1: Antrian Saat Ini -->
                <div class="col-span-1">
                    <div class="bg-white rounded-3xl shadow-xl border border-blue-100 p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Antrian Saat Ini</h2>
                        
                        <!-- Nomor Antrian Aktif -->
                        <div class="bg-blue-100 rounded-2xl p-8 mb-6 border-4 border-blue-300 shadow-inner text-center pulse-animation" id="current-queue">
                            <p class="text-sm font-semibold text-blue-800">Nomor Antrian</p>
                            <div class="text-8xl font-black text-blue-900 mt-2" id="current-queue-number">-</div>
                        </div>
                        
                        <!-- Tombol Aksi -->
                        <div class="grid grid-cols-2 gap-4">
                            <button onclick="callQueue()" class="flex items-center justify-center px-4 py-3 bg-blue-800 text-white rounded-xl font-semibold shadow-md hover:bg-blue-900 transition-colors">
                                <i class="fas fa-volume-up mr-2"></i>
                                Panggil
                            </button>
                            <button onclick="recallQueue()" class="flex items-center justify-center px-4 py-3 bg-yellow-500 text-white rounded-xl font-semibold shadow-md hover:bg-yellow-600 transition-colors">
                                <i class="fas fa-redo-alt mr-2"></i>
                                Panggil Ulang
                            </button>
                            <button onclick="markAbsent()" class="flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-xl font-semibold shadow-md hover:bg-red-700 transition-colors">
                                <i class="fas fa-times mr-2"></i>
                                Tidak Hadir
                            </button>
                            <button onclick="serveQueue()" class="flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-xl font-semibold shadow-md hover:bg-green-700 transition-colors">
                                <i class="fas fa-check mr-2"></i>
                                Layani
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Kolom 2 & 3: Daftar Antrian -->
                <div class="col-span-1 lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-xl border border-blue-100 p-8 h-full">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Daftar Antrian</h2>
                            <div class="flex space-x-2">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-lg text-sm font-medium">
                                    Total: <span id="total-queue">0</span>
                                </span>
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-lg text-sm font-medium">
                                    Selesai: <span id="completed-queue">0</span>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Tabel Antrian -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-xl overflow-hidden">
                                <thead class="bg-blue-800 text-white">
                                    <tr>
                                        <th class="py-3 px-4 text-left rounded-tl-xl">No. Antrian</th>
                                        <th class="py-3 px-4 text-left">Status</th>
                                        <th class="py-3 px-4 text-left">Waktu</th>
                                        <th class="py-3 px-4 text-left rounded-tr-xl">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="queue-list">
                                    <!-- Data antrian akan diisi melalui JavaScript -->
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-gray-500">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            Belum ada data antrian
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Konfirmasi -->
    <div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden transition-opacity duration-300">
        <div class="bg-white rounded-2xl p-8 w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300">
            <!-- Tombol close -->
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 p-2 rounded-full hover:bg-gray-100 transition-colors">
                <i class="fas fa-times w-5 h-5"></i>
            </button>
            
            <!-- Judul dan deskripsi modal -->
            <h2 class="text-3xl font-extrabold mb-2 text-blue-800" id="modal-title">Konfirmasi</h2>
            <p class="text-gray-600 mb-6" id="modal-message">Apakah Anda yakin ingin melakukan tindakan ini?</p>
            
            <!-- Tombol aksi -->
            <div class="flex space-x-4">
                <button onclick="closeModal()" class="flex-1 py-3 rounded-xl font-semibold border border-gray-300 hover:bg-gray-100 transition">Batal</button>
                <button id="confirm-action" class="flex-1 bg-blue-800 text-white py-3 rounded-xl font-semibold hover:bg-blue-900 transition shadow-lg">Konfirmasi</button>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Antrian dan Modal -->
    <script>
        // Data antrian
        let queueData = [];
        let currentQueueIndex = -1;
        let totalServed = 0;
        
        // Inisialisasi halaman
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil data antrian dari API
            fetchQueueData();
            
            // Cek apakah ada nomor antrian dari URL (dari halaman home)
            const urlParams = new URLSearchParams(window.location.search);
            const queueNumber = urlParams.get('queue_number');
            
            if (queueNumber) {
                // Highlight antrian yang baru diambil
                setTimeout(() => {
                    highlightQueue(queueNumber);
                }, 1000);
            }
            
            // Set interval untuk refresh data setiap 30 detik
            setInterval(fetchQueueData, 30000);
        });
        
        // Fungsi untuk mengambil data antrian dari API
        function fetchQueueData() {
            const counterId = {{ $counter->id }};
            
            fetch(`/api/queue/counter/${counterId}`)
                .then(response => response.json())
                .then(data => {
                    queueData = data.map(queue => ({
                        id: queue.id,
                        number: queue.queue_number,
                        status: queue.status,
                        time: new Date(queue.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})
                    }));
                    
                    // Update UI
                    updateQueueTable();
                    updateCounters();
                    
                    // Set current queue jika ada yang sedang dipanggil
                    const calledQueue = queueData.findIndex(q => q.status === 'called');
                    if (calledQueue >= 0) {
                        currentQueueIndex = calledQueue;
                        updateCounters();
                    }
                })
                .catch(error => {
                    console.error('Error fetching queue data:', error);
                });
        }
        
        // Fungsi untuk memperbarui tabel antrian
        function updateQueueTable() {
            const tableBody = document.getElementById('queue-list');
            
            if (queueData.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500">
                            <i class="fas fa-info-circle mr-2"></i>
                            Belum ada data antrian
                        </td>
                    </tr>
                `;
                return;
            }
            
            let html = '';
            
            queueData.forEach((queue, index) => {
                // Status badge
                let statusBadge = '';
                let actionButton = '';
                
                switch(queue.status) {
                    case 'waiting':
                        statusBadge = '<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-lg text-xs font-medium">Menunggu</span>';
                        actionButton = `<button onclick="callSpecificQueue(${index})" class="px-3 py-1 bg-blue-800 text-white rounded-lg text-xs font-medium hover:bg-blue-900 transition-colors">Panggil</button>`;
                        break;
                    case 'called':
                        statusBadge = '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-lg text-xs font-medium">Dipanggil</span>';
                        actionButton = `<div class="flex space-x-1">
                            <button onclick="recallSpecificQueue(${index})" class="px-2 py-1 bg-yellow-500 text-white rounded-lg text-xs font-medium hover:bg-yellow-600 transition-colors">Ulang</button>
                            <button onclick="markSpecificAbsent(${index})" class="px-2 py-1 bg-red-600 text-white rounded-lg text-xs font-medium hover:bg-red-700 transition-colors">Absen</button>
                            <button onclick="serveSpecificQueue(${index})" class="px-2 py-1 bg-green-600 text-white rounded-lg text-xs font-medium hover:bg-green-700 transition-colors">Layani</button>
                        </div>`;
                        break;
                    case 'absent':
                        statusBadge = '<span class="px-2 py-1 bg-red-100 text-red-800 rounded-lg text-xs font-medium">Tidak Hadir</span>';
                        actionButton = `<button onclick="callSpecificQueue(${index})" class="px-3 py-1 bg-blue-800 text-white rounded-lg text-xs font-medium hover:bg-blue-900 transition-colors">Panggil Ulang</button>`;
                        break;
                    case 'served':
                        statusBadge = '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-lg text-xs font-medium">Dilayani</span>';
                        actionButton = '-';
                        break;
                }
                
                // Highlight baris yang sedang aktif
                const rowClass = index === currentQueueIndex ? 'bg-blue-50' : (index % 2 === 0 ? 'bg-gray-50' : 'bg-white');
                
                html += `
                    <tr class="${rowClass} hover:bg-blue-50 transition-colors">
                        <td class="py-3 px-4 border-b border-gray-100 font-medium">${queue.number}</td>
                        <td class="py-3 px-4 border-b border-gray-100">${statusBadge}</td>
                        <td class="py-3 px-4 border-b border-gray-100">${queue.time}</td>
                        <td class="py-3 px-4 border-b border-gray-100">${actionButton}</td>
                    </tr>
                `;
            });
            
            tableBody.innerHTML = html;
        }
        
        // Fungsi untuk memperbarui counter
        function updateCounters() {
            document.getElementById('total-queue').textContent = queueData.length;
            document.getElementById('completed-queue').textContent = totalServed;
            
            // Update nomor antrian saat ini
            if (currentQueueIndex >= 0) {
                document.getElementById('current-queue-number').textContent = queueData[currentQueueIndex].number;
            } else {
                document.getElementById('current-queue-number').textContent = '-';
            }
        }
        
        // Fungsi untuk memanggil antrian berikutnya
        function callQueue() {
            // Cari antrian berikutnya yang statusnya 'waiting'
            const nextIndex = queueData.findIndex(q => q.status === 'waiting');
            
            if (nextIndex >= 0) {
                currentQueueIndex = nextIndex;
                const queueId = queueData[nextIndex].id;
                
                // Update status di API
                updateQueueStatus(queueId, 'called');
                
                // Animasi panggilan
                const currentQueueElement = document.getElementById('current-queue');
                currentQueueElement.classList.add('animate-border-pulse');
                setTimeout(() => {
                    currentQueueElement.classList.remove('animate-border-pulse');
                }, 2000);
                
                // Tampilkan notifikasi
                showNotification(`Memanggil nomor antrian ${queueData[nextIndex].number}`);
            } else {
                showNotification('Tidak ada antrian yang menunggu', 'warning');
            }
        }
        
        // Fungsi untuk memanggil ulang antrian saat ini
        function recallQueue() {
            if (currentQueueIndex >= 0 && queueData[currentQueueIndex].status === 'called') {
                // Animasi panggilan
                const currentQueueElement = document.getElementById('current-queue');
                currentQueueElement.classList.add('animate-border-pulse');
                setTimeout(() => {
                    currentQueueElement.classList.remove('animate-border-pulse');
                }, 2000);
                
                // Animasi nomor antrian
                animateQueueNumber(queueData[currentQueueIndex].number);
                
                // Tampilkan notifikasi
                showNotification(`Memanggil ulang nomor antrian ${queueData[currentQueueIndex].number}`);
            } else {
                showNotification('Tidak ada antrian aktif untuk dipanggil ulang', 'warning');
            }
        }
        
        // Fungsi untuk menandai antrian saat ini sebagai tidak hadir
        function markAbsent() {
            if (currentQueueIndex >= 0 && queueData[currentQueueIndex].status === 'called') {
                const queueId = queueData[currentQueueIndex].id;
                const queueNumber = queueData[currentQueueIndex].number;
                
                // Update status di API
                updateQueueStatus(queueId, 'absent');
                
                // Reset antrian saat ini
                currentQueueIndex = -1;
                
                // Tampilkan notifikasi
                showNotification(`Antrian ${queueNumber} ditandai tidak hadir`);
            } else {
                showNotification('Tidak ada antrian aktif untuk ditandai tidak hadir', 'warning');
            }
        }
        
        // Fungsi untuk melayani antrian saat ini
        function serveQueue() {
            if (currentQueueIndex >= 0 && queueData[currentQueueIndex].status === 'called') {
                const queueId = queueData[currentQueueIndex].id;
                const queueNumber = queueData[currentQueueIndex].number;
                
                // Update status di API
                updateQueueStatus(queueId, 'served');
                
                // Increment counter antrian yang dilayani
                totalServed++;
                
                // Reset current queue
                currentQueueIndex = -1;
                
                // Tampilkan notifikasi
                showNotification(`Antrian ${queueNumber} telah dilayani`);
            } else {
                showNotification('Tidak ada antrian aktif untuk dilayani', 'warning');
            }
        }
        
        // Fungsi untuk mengupdate status antrian ke database
        function updateQueueStatus(queueId, status) {
            fetch(`/api/queue/${queueId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                // Refresh data antrian
                fetchQueueData();
            })
            .catch(error => {
                console.error('Error updating queue status:', error);
                showNotification('Gagal mengupdate status antrian', 'error');
            });
        }
        
        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type = 'info') {
            // Implementasi sederhana dengan alert
            alert(message);
            
            // Dalam implementasi nyata, gunakan library notifikasi yang lebih baik
            // seperti toastr, sweetalert2, dll.
        }
        
        // Fungsi untuk highlight antrian tertentu
        function highlightQueue(queueNumber) {
            const index = queueData.findIndex(q => q.number === queueNumber);
            if (index >= 0) {
                // Scroll ke antrian yang dimaksud
                const tableRows = document.querySelectorAll('#queue-list tr');
                if (tableRows[index]) {
                    tableRows[index].scrollIntoView({ behavior: 'smooth', block: 'center' });
                    tableRows[index].classList.add('bg-yellow-100');
                    setTimeout(() => {
                        tableRows[index].classList.remove('bg-yellow-100');
                    }, 3000);
                }
            }
        }
        
        // Fungsi untuk animasi nomor antrian
        function animateQueueNumber(number) {
            const numberElement = document.getElementById('current-queue-number');
            numberElement.textContent = number;
            numberElement.classList.add('animate-number');
            setTimeout(() => {
                numberElement.classList.remove('animate-number');
            }, 2000);
        }
        
        // Fungsi untuk memanggil antrian tertentu
        function callSpecificQueue(index) {
            if (index >= 0 && index < queueData.length) {
                // Jika ada antrian aktif sebelumnya, reset statusnya ke waiting
                if (currentQueueIndex >= 0 && queueData[currentQueueIndex].status === 'called') {
                    queueData[currentQueueIndex].status = 'waiting';
                }
                
                currentQueueIndex = index;
                queueData[index].status = 'called';
                queueData[index].time = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                
                // Update UI
                updateQueueTable();
                updateCounters();
                
                // Animasi panggilan
                const currentQueueElement = document.getElementById('current-queue');
                currentQueueElement.classList.add('pulse-animation');
                setTimeout(() => {
                    currentQueueElement.classList.remove('pulse-animation');
                }, 2000);
                
                // Simulasi suara panggilan
                alert(`Memanggil nomor antrian ${queueData[index].number}`);
            }
        }
        
        // Fungsi untuk memanggil ulang antrian tertentu
        function recallSpecificQueue(index) {
            if (index >= 0 && index < queueData.length && queueData[index].status === 'called') {
                queueData[index].time = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                
                // Update UI
                updateQueueTable();
                
                // Animasi panggilan
                const currentQueueElement = document.getElementById('current-queue');
                currentQueueElement.classList.add('pulse-animation');
                setTimeout(() => {
                    currentQueueElement.classList.remove('pulse-animation');
                }, 2000);
                
                // Simulasi suara panggilan ulang
                alert(`Memanggil ulang nomor antrian ${queueData[index].number}`);
            }
        }
        
        // Fungsi untuk menandai antrian tertentu sebagai tidak hadir
        function markSpecificAbsent(index) {
            if (index >= 0 && index < queueData.length && queueData[index].status === 'called') {
                queueData[index].status = 'absent';
                queueData[index].time = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                
                // Jika ini adalah antrian aktif, reset
                if (currentQueueIndex === index) {
                    currentQueueIndex = -1;
                }
                
                // Update UI
                updateQueueTable();
                updateCounters();
            }
        }
        
        // Fungsi untuk melayani antrian tertentu
        function serveSpecificQueue(index) {
            if (index >= 0 && index < queueData.length && queueData[index].status === 'called') {
                queueData[index].status = 'served';
                queueData[index].time = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                
                // Increment counter antrian yang dilayani
                totalServed++;
                
                // Jika ini adalah antrian aktif, reset
                if (currentQueueIndex === index) {
                    currentQueueIndex = -1;
                }
                
                // Update UI
                updateQueueTable();
                updateCounters();
            }
        }
        
        // Fungsi untuk modal konfirmasi
        function openModal(title, message, confirmAction) {
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-message').textContent = message;
            document.getElementById('confirm-action').onclick = confirmAction;
            
            const modal = document.getElementById('confirmationModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('.transform').classList.remove('scale-95');
                modal.querySelector('.transform').classList.add('scale-100');
            }, 10);
        }
        
        function closeModal() {
            const modal = document.getElementById('confirmationModal');
            modal.querySelector('.transform').classList.remove('scale-100');
            modal.querySelector('.transform').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</body>
</html>