<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Antrian - Universitas Terbuka</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800;900&display=swap" rel="stylesheet">
    
    <!-- ResponsiveVoice API Key provided by user -->
    <script src="https://code.responsivevoice.org/responsivevoice.js?key=RJIwPqiI"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            position: relative; 
        }
        /* Animasi pulsa border */
        @keyframes pulse-border {
            0% { border-color: #fde047; box-shadow: 0 0 20px #fde047; } /* Warna kuning untuk lebih mencolok */
            50% { border-color: #fcd34d; box-shadow: 0 0 40px #fcd34d; }
            100% { border-color: #fde047; box-shadow: 0 0 20px #fde047; }
        }
        .animate-border-pulse {
            animation: pulse-border 2s infinite;
        }
        /* Animasi pulsa nomor antrian */
        @keyframes number-pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .animate-number {
            animation: number-pulse 2s infinite;
        }
        
        /* YouTube video: Menerapkan rasio aspek 16:9 agar video lebih besar */
        .video-wrapper-16-9 {
            width: 100%;
            /* Perubahan: 9 / 16 * 100% = 56.25% (Rasio 16:9) */
            padding-bottom: 56.25%; 
            position: relative;
            height: 0; /* Penting untuk teknik padding-bottom */
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.4);
        }
        .video-wrapper-16-9 iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        /* Glassmorphism styling */
        .glass-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border: 3px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.3);
            transition: border-color 0.5s ease-in-out;
        }
        
        /* Main Content Layout: Menggunakan Flexbox untuk 70/30 horizontal */
        .main-content-layout {
            height: auto; 
            gap: 1rem;
            /* Menggunakan py-2 (0.5rem top/bottom) untuk kerapatan vertikal */
            padding-top: 0.5rem; 
            padding-bottom: 0.5rem; 
        }
        /* Pembagian layar 70% Video (Dominan) */
        .video-section {
            flex: 0 0 70%; 
            max-width: 70%;
            height: auto; 
        }
        /* Pembagian layar 30% Antrian */
        .queue-section {
            flex: 0 0 30%; 
            max-width: 30%;
            height: auto; 
        }
        
        /* Panel Kanan: Menggunakan Grid untuk 1/3 Jam dan 2/3 Antrian */
        .right-panel-grid {
            display: grid;
            grid-template-rows: 1fr 2fr; /* Pembagian vertikal yang presisi */
            gap: 1rem;
            height: 100%; /* Penting agar grid mengisi tinggi yang disetel JS */
        }

        /* Responsif untuk layar kecil */
        @media (max-width: 1024px) {
            .main-content-layout {
                flex-direction: column;
                height: auto;
                padding-bottom: 1rem;
            }
            /* Pada mobile, kembali ke 100% lebar untuk kedua bagian */
            .video-section, .queue-section {
                flex: 1 1 100%;
                max-width: 100%;
            }
            .video-wrapper-16-9 {
                padding-bottom: 56.25%; 
            }
        }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-blue-900 to-blue-600 text-white min-h-screen overflow-auto lg:overflow-hidden">
    
    <!-- HEADER (Hanya Judul) - Margin Bawah Dihapus -->
    <header class="glass-container rounded-b-3xl flex justify-center items-center px-8 py-3">
        <h1 class="text-3xl lg:text-4xl font-black whitespace-nowrap">
            <i class="fas fa-university mr-3"></i>
            UNIVERSITAS TERBUKA - MONITOR ANTRIAN
        </h1>
    </header>

    <!-- Main Content Layout Container (Video & Kanan Split) -->
    <div class="main-content-layout flex flex-col lg:flex-row px-4">
        
        <!-- Left Side - YouTube Video (70% width) -->
        <div class="video-section px-2"> 
            <div class="video-wrapper-16-9" id="video-wrapper"> 
                <iframe 
                    src="https://www.youtube.com/embed/UEg0Tmsu9i4?autoplay=1&mute=1&loop=1&playlist=UEg0Tmsu9i4&controls=0&showinfo=0&rel=0&modestbranding=1" 
                    frameborder="0" 
                    allow="autoplay; encrypted-media" 
                    allowfullscreen>
                </iframe>
            </div>
        </div>

        <!-- Right Side - Split Vertically (30% width) -->
        <div class="queue-section px-2" id="queue-section-wrapper">
            
            <div class="right-panel-grid">
                
                <!-- 1. KANAN ATAS: Jam Real-time (1/3 Tinggi) -->
                <div class="flex justify-center items-center">
                    <div class="glass-container rounded-3xl p-6 text-center w-full h-full flex flex-col justify-center items-center">
                        <div class="text-4xl lg:text-5xl font-black text-yellow-300" id="current-time">
                            --:--:--
                        </div>
                        <div class="text-lg lg:text-xl font-semibold mt-2" id="current-date">
                            -- --- ----
                        </div>
                    </div>
                </div>

                <!-- 2. KANAN BAWAH: Display Antrian Saat Ini (2/3 Tinggi) -->
                <div class="flex justify-center items-center">
                    <div class="glass-container rounded-3xl p-6 text-center w-full h-full flex flex-col justify-center items-center">
                        <h2 class="text-3xl lg:text-4xl font-black mb-6 border-b-4 border-white pb-2 w-3/4">
                            ANTRIAN SAAT INI
                        </h2>
                        <div class="text-7xl lg:text-8xl font-black mb-4 text-yellow-300 transition duration-500" id="current-queue-number">
                            ---
                        </div>
                        <div class="text-2xl lg:text-3xl font-semibold text-white mt-4">
                            SILAHKAN MENUJU KE
                        </div>
                        <div class="text-3xl lg:text-4xl font-extrabold text-blue-300" id="counter-name">
                            Loket A
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
     <!-- JAVASCRIPT UNTUK MONITOR -->
     <script>
         // Fungsi untuk mengupdate jam dan tanggal real-time
         function updateClock() {
             const now = new Date();
             const time = now.toLocaleTimeString('id-ID', { 
                 hour12: false,
                 hour: '2-digit',
                 minute: '2-digit',
                 second: '2-digit'
             });
             const date = now.toLocaleDateString('id-ID', {
                 weekday: 'long',
                 day: 'numeric',
                 month: 'long',
                 year: 'numeric'
             });
             
             document.getElementById('current-time').textContent = time;
             document.getElementById('current-date').textContent = date;
         }
         
         // Fungsi untuk menyesuaikan tinggi panel kanan agar sesuai dengan tinggi video (rasio 16:9 yang baru)
         function adjustLayoutHeight() {
             const videoWrapper = document.getElementById('video-wrapper');
             const queueSection = document.getElementById('queue-section-wrapper');
             
             if (!videoWrapper || !queueSection || window.innerWidth < 1024) {
                  // Untuk mobile (di bawah 1024px), biarkan tinggi otomatis
                 if (window.innerWidth < 1024 && queueSection) {
                     queueSection.style.height = 'auto';
                 }
                 return;
             }

             // Dapatkan tinggi elemen video yang dihitung oleh padding-bottom
             const actualVideoHeight = videoWrapper.offsetHeight; 

             // Terapkan tinggi yang SAMA PERSIS ke wrapper panel kanan.
             queueSection.style.height = `${actualVideoHeight}px`;
         }

         // Update jam setiap detik
         setInterval(updateClock, 1000);

         // Variabel untuk tracking antrian terakhir
         let lastQueueId = null;
         let lastQueueUpdate = null;

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

         /**
          * Mengucapkan nomor antrian menggunakan ResponsiveVoice.
          */
         function speakQueueNumber(queueNumber, counterName) {
             const formattedNumber = String(queueNumber).padStart(3, '0');
             const digits = formattedNumber.split('').join(', '); 
             const text = `Nomor antrian, ${digits}, silahkan menuju ke, ${counterName}`;
             
             if (typeof responsiveVoice !== 'undefined' && responsiveVoice.isPlaying() === false) {
                 responsiveVoice.speak(text, "Indonesian Female", { rate: 0.9, volume: 1 });
             } else {
                 console.warn('ResponsiveVoice not ready or already speaking. Skipping speech for Q' + queueNumber);
             }
         }

         /**
          * Mengupdate tampilan di monitor dan memicu suara/animasi.
          */
         function updateMonitor(queueNumber, counterName, isNewCall = false) {
             const queueNumberEl = document.getElementById('current-queue-number');
             const counterNameEl = document.getElementById('counter-name');
             const queueContainer = queueNumberEl.closest('.glass-container');
             
             queueNumberEl.textContent = String(queueNumber).padStart(3, '0');
             counterNameEl.textContent = counterName;

             // Hanya mainkan suara dan animasi jika ini panggilan baru
             if (isNewCall) {
                 queueContainer.classList.remove('animate-border-pulse');
                 queueNumberEl.classList.remove('animate-number');

                 void queueContainer.offsetWidth; 
                 void queueNumberEl.offsetWidth;

                 queueContainer.classList.add('animate-border-pulse');
                 queueNumberEl.classList.add('animate-number');

                 // Mainkan suara bell dan voice announcement
                 playBell().then(() => {
                     speakQueueNumber(queueNumber, counterName);
                 });

                 setTimeout(() => {
                     queueContainer.classList.remove('animate-border-pulse');
                     queueNumberEl.classList.remove('animate-number');
                 }, 5000);
             }
         }

         /**
          * AJAX polling untuk mengecek antrian yang dipanggil
          */
         function checkForQueueUpdates() {
             fetch('{{ route("queue.currentCalled") }}', {
                 method: 'GET',
                 headers: {
                     'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
                 }
             })
             .then(response => response.json())
             .then(data => {
                 if (data.success && data.has_queue) {
                     const currentQueueId = data.queue.id;
                     const currentUpdateTime = data.queue.updated_at;
                     
                     // Cek apakah ini antrian baru atau update
                     const isNewCall = lastQueueId !== currentQueueId || 
                                     lastQueueUpdate !== currentUpdateTime;
                     
                     if (isNewCall) {
                         console.log('Antrian baru dipanggil:', data);
                         updateMonitor(data.queue.queue_number, data.counter.nama_loket, true);
                         
                         // Update tracking variables
                         lastQueueId = currentQueueId;
                         lastQueueUpdate = currentUpdateTime;
                     }
                 } else {
                     // Tidak ada antrian yang dipanggil
                     if (lastQueueId !== null) {
                         console.log('Tidak ada antrian yang dipanggil');
                         updateMonitor(0, "Menunggu Panggilan", false);
                         lastQueueId = null;
                         lastQueueUpdate = null;
                     }
                 }
             })
             .catch(error => {
                 console.error('Error checking queue updates:', error);
             });
         }

         // Inisialisasi saat halaman dimuat
         window.onload = function() {
             updateClock();
             adjustLayoutHeight(); 
             
             // Set tampilan awal
             updateMonitor(0, "Menunggu Panggilan", false);
             
             // Mulai AJAX polling setiap 2 detik
             setInterval(checkForQueueUpdates, 2000);
             
             // Cek sekali saat halaman dimuat
             checkForQueueUpdates();
         }
         
         window.addEventListener('resize', adjustLayoutHeight);
     </script>
</body>
</html>
