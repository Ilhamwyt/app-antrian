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
    
    <!-- ResponsiveVoice API untuk fitur suara -->
    <script src="https://code.responsivevoice.org/responsivevoice.js?key=RJIwPqiW"></script>

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
                        <h1 class="text-2xl font-extrabold text-gray-900">UNIVERSITAS TERBUKA</h1>
                        <p class="text-sm text-gray-500 font-medium">Sistem Antrian Digital</p>
                    </div>
                </div>

                <!-- Informasi Loket -->
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <h2 class="text-xl font-bold text-blue-800">{{ $counter->nama_loket }}</h2>
                    </div>
                    <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-xl shadow-lg hover:bg-red-700 transition-all duration-300">
                        <i class="fas fa-sign-out-alt w-4 h-4 mr-2"></i>
                        Logout
                    </a>
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
                        <div class="bg-blue-100 rounded-2xl p-8 mb-6 border-4 border-blue-300 shadow-inner text-center animate-border-pulse" id="current-queue"
                             @php
                                 $currentQueue = $queues->where('status', 'called')->first();
                             @endphp
                             @if($currentQueue)
                                 data-queue-id="{{ $currentQueue->id }}"
                             @endif>
                            <p class="text-sm font-semibold text-blue-800">Nomor Antrian</p>
                            <div class="text-8xl font-black text-blue-900 mt-2" id="current-queue-number">
                                {{ $currentQueue ? $currentQueue->queue_number : '-' }}
                            </div>
                        </div>
                        
                        <!-- Tombol Aksi -->
                        <div class="grid grid-cols-2 gap-4">
                            <button onclick="callQueue()" class="flex items-center justify-center px-4 py-3 bg-blue-800 text-white rounded-xl font-semibold shadow-md hover:bg-blue-900 transition-colors">
                                <i class="fas fa-volume-up mr-2"></i>
                                Panggil
                            </button>
                            <button onclick="recallQueue()" class="flex items-center justify-center px-4 py-3 bg-yellow-500 text-white rounded-xl font-semibold shadow-md hover:bg-yellow-600 transition-colors">
                                <i class="fas fa-redo-alt mr-2"></i>
                                Ulangi
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
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-lg text-sm font-medium">
                                    Menunggu: <span id="waiting-count">0</span>
                                </span>
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-lg text-sm font-medium">
                                    Dipanggil: <span id="called-count">0</span>
                                </span>
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-lg text-sm font-medium">
                                    Selesai: <span id="served-count">0</span>
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
                                    </tr>
                                </thead>
                                <tbody id="queue-list">
                                    @forelse ($queues->where('status', 'waiting') as $queue)
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="py-3 px-4">{{ $queue->queue_number }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-lg text-xs font-medium">Menunggu</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="py-8 text-center text-gray-500">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            Belum ada data antrian
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Modal Layani Pengunjung -->
    <div id="serveModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Data Pengunjung</h3>
                <button onclick="closeServeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="visitorForm">
                <input type="hidden" id="queue_id" name="queue_id">
                <div class="mb-4">
                    <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                    <input type="text" id="nim" name="nim" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                    <input type="text" id="phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="complaint" class="block text-sm font-medium text-gray-700 mb-1">Permasalahan</label>
                    <textarea id="complaint" name="complaint" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                </div>
                <div class="mb-4">
                    <label for="solution" class="block text-sm font-medium text-gray-700 mb-1">Solusi</label>
                    <textarea id="solution" name="solution" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="flex space-x-4">
                        <div class="flex items-center">
                            <input type="radio" id="status_selesai" name="status" value="selesai" checked 
                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" onchange="toggleForwardTo(false)">
                            <label for="status_selesai" class="ml-2 block text-sm text-gray-700">Selesai</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="status_tindak_lanjut" name="status" value="perlu_tindak_lanjut" 
                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" onchange="toggleForwardTo(true)">
                            <label for="status_tindak_lanjut" class="ml-2 block text-sm text-gray-700">Perlu Tindak Lanjut</label>
                        </div>
                    </div>
                </div>
                <div id="forward_to_container" class="mb-4 hidden">
                    <label for="forward_to" class="block text-sm font-medium text-gray-700 mb-1">Kepada Siapa</label>
                    <input type="text" id="forward_to" name="forward_to" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeServeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md mr-2">Batal</button>
                    <button type="button" onclick="submitVisitorData()" class="px-4 py-2 bg-blue-600 text-white rounded-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- JavaScript untuk Antrian dan Modal -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Token CSRF untuk request
        const csrfToken = "{{ csrf_token() }}";
        
        // ID Loket saat ini
        const counterId = "{{ $counter->id }}";
        const counterName = "{{ $counter->nama_loket }}";
        
        // Inisialisasi Pusher
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });
        
        // Subscribe ke channel counter
        const channel = pusher.subscribe('counter.' + counterId);
        
        // Listen untuk event queue.updated
        channel.bind('queue.updated', function(data) {
            console.log('Queue updated:', data);
            // Refresh halaman untuk menampilkan data terbaru
            window.location.reload();
        });
        
        // Fungsi untuk memutar suara bell
        function playBell() {
            return new Promise((resolve) => {
                const bell = new Audio('{{ asset('bell.mp3') }}');
                bell.play();
                bell.onended = function() {
                    resolve();
                };
            });
        }
        
        // Fungsi untuk mengucapkan nomor antrian
        function speakQueueNumber(queueNumber) {
            // Format nomor antrian untuk diucapkan (misalnya: 001 menjadi "nol nol satu")
            const formattedNumber = queueNumber.padStart(3, '0');
            
            // Teks yang akan diucapkan
            const text = `Nomor antrian, ${formattedNumber}, silahkan menuju ke, loket ${counterName.split(' ').pop()}`;
            
            // Menggunakan ResponsiveVoice untuk mengucapkan teks
            responsiveVoice.speak(text, "Indonesian Female", {rate: 0.9});
        }
        

        // Fungsi untuk memanggil antrian berikutnya
        function callQueue() {
            // Kirim request ke server
            fetch("{{ route('queue.callNext') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    counter_id: counterId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update tampilan antrian saat ini di halaman LOKET
                    document.getElementById('current-queue-number').textContent = data.queue.queue_number;
                    
                    // Set data-queue-id untuk recall function
                    const currentQueueElement = document.getElementById('current-queue');
                    currentQueueElement.setAttribute('data-queue-id', data.queue.id);
                    
                    // Tambahkan kelas animasi
                    currentQueueElement.classList.add('animate-border-pulse');
                    
                    // Refresh daftar antrian tanpa reload halaman
                    refreshQueueList();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memanggil antrian');
            });
        }
        
        // Fungsi untuk memanggil ulang antrian saat ini
        // Fungsi untuk memanggil ulang antrian saat ini
        function recallQueue() {
            const currentQueueNumber = document.getElementById('current-queue-number').textContent;
            if (currentQueueNumber === '-') {
                alert('Tidak ada antrian yang sedang dipanggil');
                return;
            }
            
            // *** TAMBAHKAN BAGIAN INI ***
            // Cari ID antrian yang sedang dipanggil (ini butuh sedikit trik atau data dari server)
            // Cara termudah: simpan ID antrian yang sedang dipanggil di atribut data HTML
            const currentQueueElement = document.getElementById('current-queue');
            const currentQueueId = currentQueueElement.dataset.queueId; // Misalnya, tambahkan data-queue-id di HTML

            if (!currentQueueId) {
                alert('Tidak dapat mengidentifikasi antrian yang akan diulang.');
                return;
            }

            // Kirim request ke server untuk memicu broadcast
            fetch("{{ route('queue.recall') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    queue_id: currentQueueId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Suara akan diputar di monitor, jadi tidak perlu di sini
                    console.log('Pemanggilan ulang telah dikirim ke monitor.');
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengulang pemanggilan');
            });
        }
        
        // Fungsi untuk menandai antrian tidak hadir
        function markAbsent() {
            const currentQueueNumber = document.getElementById('current-queue-number').textContent;
            if (currentQueueNumber === '-') {
                alert('Tidak ada antrian yang sedang dipanggil');
                return;
            }
            
            // Konfirmasi tindakan
            if (!confirm('Apakah Anda yakin ingin menandai antrian ini sebagai tidak hadir?')) {
                return;
            }
            
            // Ambil queue ID dari data attribute
            const currentQueueElement = document.getElementById('current-queue');
            const queueId = currentQueueElement.getAttribute('data-queue-id');
            
            if (!queueId) {
                alert('Tidak dapat mengidentifikasi antrian yang akan ditandai tidak hadir');
                return;
            }
            
            // Kirim request ke server
            fetch("{{ route('queue.markAbsent') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    queue_id: queueId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reset tampilan antrian saat ini
                    document.getElementById('current-queue-number').textContent = '-';
                    
                    // Refresh daftar antrian tanpa reload halaman
                    refreshQueueList();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menandai antrian tidak hadir');
            });
        }
        
        // Fungsi untuk melayani antrian
        function serveQueue() {
            // Cek apakah ada antrian yang sedang dipanggil
            const currentQueueElement = document.getElementById('current-queue');
            const queueId = currentQueueElement.getAttribute('data-queue-id');
            
            if (!queueId) {
                alert('Tidak ada antrian yang sedang dipanggil');
                return;
            }
            
            // Tampilkan modal dan isi queue_id
            document.getElementById('queue_id').value = queueId;
            document.getElementById('serveModal').classList.remove('hidden');
        }
    
    function closeServeModal() {
        document.getElementById('serveModal').classList.add('hidden');
        document.getElementById('visitorForm').reset();
    }
    
    // Fungsi untuk menampilkan/menyembunyikan field "Kepada Siapa"
    function toggleForwardTo(show) {
        const forwardToContainer = document.getElementById('forward_to_container');
        if (show) {
            forwardToContainer.classList.remove('hidden');
        } else {
            forwardToContainer.classList.add('hidden');
            document.getElementById('forward_to').value = '';
        }
    }
    
    function submitVisitorData() {
        const queueId = document.getElementById('queue_id').value;
        const nim = document.getElementById('nim').value;
        const name = document.getElementById('name').value;
        const phone = document.getElementById('phone').value;
        const complaint = document.getElementById('complaint').value;
        const solution = document.getElementById('solution').value;
        const status = document.querySelector('input[name="status"]:checked').value;
        const forwardTo = document.getElementById('forward_to').value;
        
        if (!nim) {
            alert('NIM harus diisi');
            return;
        }
        
        if (!name) {
            alert('Nama pengunjung harus diisi');
            return;
        }
        
        if (!phone) {
            alert('Nomor telepon harus diisi');
            return;
        }
        
        if (status === 'perlu_tindak_lanjut' && !forwardTo) {
            alert('Field "Kepada Siapa" harus diisi untuk tindak lanjut');
            return;
        }
        
        fetch('{{ route("queue.serve") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                queue_id: queueId,
                nim: nim,
                name: name,
                phone: document.getElementById('phone').value,
                complaint: complaint,
                solution: solution,
                status: status,
                forward_to: forwardTo
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeServeModal();
                
                // Reset tampilan antrian yang dipanggil
                document.getElementById('current-queue-number').textContent = '-';
                
                // Refresh daftar antrian tanpa reload halaman
                refreshQueueList();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data pengunjung');
        });
    }

    // Fungsi untuk refresh daftar antrian tanpa reload halaman
    function refreshQueueList() {
        fetch(`{{ route('queue.getByCounterAjax', $counter->id) }}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateQueueDisplay(data);
            }
        })
        .catch(error => {
            console.error('Error refreshing queue list:', error);
        });
    }

    // Fungsi untuk memicu refresh manual (dipanggil dari event atau action lain)
    function triggerRefresh() {
        console.log('Manual refresh triggered');
        refreshQueueList();
    }

    // Variabel untuk tracking perubahan
    let lastQueueCount = 0;
    let lastQueueIds = [];

    // Fungsi untuk update tampilan daftar antrian
    function updateQueueDisplay(data) {
        // Update counter
        document.getElementById('waiting-count').textContent = data.waiting_count;
        document.getElementById('called-count').textContent = data.called_count;
        document.getElementById('served-count').textContent = data.served_count;

        // Cek apakah ada antrian baru
        const currentQueueIds = data.queues.map(q => q.id);
        const hasNewQueue = currentQueueIds.length > lastQueueIds.length || 
                           !currentQueueIds.every(id => lastQueueIds.includes(id));
        
        // Update daftar antrian dalam tabel
        const queueList = document.getElementById('queue-list');
        let waitingQueues = '';
        let servedQueues = '';
        
        data.queues.forEach(queue => {
            if (queue.status === 'waiting') {
                // Highlight antrian baru dengan animasi
                const isNewQueue = !lastQueueIds.includes(queue.id);
                const highlightClass = isNewQueue ? 'animate-pulse bg-blue-50' : '';
                
                waitingQueues += `
                    <tr class="hover:bg-gray-50 border-b ${highlightClass}" id="queue-${queue.id}">
                        <td class="py-3 px-4">${queue.queue_number}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-lg text-xs font-medium">Menunggu</span>
                        </td>
                    </tr>
                `;
            } else if (queue.status === 'served') {
                servedQueues += `
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="py-3 px-4">${queue.queue_number}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-lg text-xs font-medium">Selesai</span>
                        </td>
                    </tr>
                `;
            }
        });

        // Update tabel dengan antrian menunggu
        if (waitingQueues) {
            queueList.innerHTML = waitingQueues;
        } else {
            queueList.innerHTML = `
                <tr>
                    <td colspan="2" class="py-8 text-center text-gray-500">
                        <i class="fas fa-info-circle mr-2"></i>
                        Belum ada data antrian
                    </td>
                </tr>
            `;
        }

        // Hapus highlight setelah 3 detik untuk antrian baru
        if (hasNewQueue) {
            setTimeout(() => {
                data.queues.forEach(queue => {
                    const queueElement = document.getElementById(`queue-${queue.id}`);
                    if (queueElement) {
                        queueElement.classList.remove('animate-pulse', 'bg-blue-50');
                    }
                });
            }, 3000);
        }

        // Update antrian saat ini
        if (data.current_queue) {
            document.getElementById('current-queue-number').textContent = data.current_queue.queue_number;
            const currentQueueElement = document.getElementById('current-queue');
            currentQueueElement.setAttribute('data-queue-id', data.current_queue.id);
        } else {
            document.getElementById('current-queue-number').textContent = '-';
            const currentQueueElement = document.getElementById('current-queue');
            currentQueueElement.removeAttribute('data-queue-id');
        }

        // Update tracking variables
        lastQueueIds = currentQueueIds;
        lastQueueCount = data.queues.length;
    }

    // Fungsi untuk memperbarui tampilan jam
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
    
    // AJAX polling untuk update real-time
    setInterval(refreshQueueList, 1000); // Update setiap 1 detik untuk responsivitas lebih baik
    
    // Refresh sekali saat halaman dimuat
    refreshQueueList();
    
    // Listen untuk storage events (komunikasi antar tab)
    window.addEventListener('storage', function(e) {
        if (e.key === 'queue_updated' && e.newValue) {
            console.log('Queue updated detected from another tab');
            triggerRefresh();
        }
    });
    
    // Listen untuk focus events (ketika user kembali ke tab ini)
    window.addEventListener('focus', function() {
        console.log('Window focused, refreshing queue list');
        triggerRefresh();
    });
    
    // Cek apakah ada parameter highlight dari home page
    const urlParams = new URLSearchParams(window.location.search);
    const highlightQueue = urlParams.get('highlight');
    if (highlightQueue) {
        // Scroll ke antrian yang di-highlight
        setTimeout(() => {
            const queueElement = document.querySelector(`tr:has(td:contains("${highlightQueue}"))`);
            if (queueElement) {
                queueElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                queueElement.classList.add('animate-pulse', 'bg-blue-50');
                setTimeout(() => {
                    queueElement.classList.remove('animate-pulse', 'bg-blue-50');
                }, 3000);
            }
        }, 2000);
    }
    </script>
</body>
</html>