@extends('layout.app')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Admin')

@section('content')
<!-- Gaya Kustom untuk Konsistensi dengan Homepage -->
<style>
    body {
        font-family: 'Poppins', sans-serif;
    }
    .cursor-pointer, button {
        cursor: pointer;
    }
    /* Custom scrollbar */
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
    
    /* Efek hover yang lebih modern */
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: translateY(-5px);
    }
    
    /* Animasi untuk tombol */
    .btn-hover {
        transition: all 0.3s ease;
    }
    .btn-hover:hover {
        transform: scale(1.05);
    }
    
    /* Animasi untuk ikon */
    .icon-hover {
        transition: all 0.3s ease;
    }
    .icon-hover:hover {
        transform: scale(1.1);
    }
</style>

<!-- Main Content Dashboard dengan Background Gradien -->
<main class="min-h-screen bg-gradient-to-br from-white to-blue-50">
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Dashboard -->
        <div class="bg-white rounded-3xl shadow-xl border border-blue-100 p-8 mb-10 card-hover">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div>
                    <h2 class="text-4xl font-extrabold text-gray-900 mb-2">Selamat Datang, Admin</h2>
                    <p class="text-lg text-gray-600 font-light">Monitor dan kelola sistem antrian Universitas Terbuka</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                    <div class="relative">
                        <select class="appearance-none bg-gray-50 border border-gray-300 rounded-xl py-3 px-6 pr-10 focus:outline-none focus:border-blue-500 font-medium w-full">
                            <option>Hari Ini</option>
                            <option>Minggu Ini</option>
                            <option>Bulan Ini</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    <button class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-blue-800 rounded-xl shadow-lg hover:bg-blue-900 transition-all duration-300 transform hover:scale-105 w-full sm:w-auto btn-hover">
                        <i class="fas fa-download w-4 h-4 mr-2"></i>
                        Export Laporan
                    </button>
                </div>
            </div>
        </div>

        <!-- Kartu Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
            <!-- Total Antrian Hari Ini -->
            <div class="bg-white rounded-3xl shadow-xl border border-blue-100 p-8 text-center card-hover">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <i class="fas fa-users text-3xl text-blue-700"></i>
                </div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">127</h3>
                <p class="text-gray-600 font-medium mb-3">Total Antrian Hari Ini</p>
                <div class="flex items-center justify-center text-green-600">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span class="text-sm font-semibold">12% dari kemarin</span>
                </div>
            </div>

            <!-- Antrian Tidak Hadir -->
            <div class="bg-white rounded-3xl shadow-xl border border-red-100 p-8 text-center card-hover">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <i class="fas fa-user-slash text-3xl text-red-700"></i>
                </div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">8</h3>
                <p class="text-gray-600 font-medium mb-3">Antrian Tidak Hadir</p>
                <div class="flex items-center justify-center text-red-600">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span class="text-sm font-semibold">3% dari kemarin</span>
                </div>
            </div>

            <!-- Total Loket -->
            <div class="bg-white rounded-3xl shadow-xl border border-purple-100 p-8 text-center card-hover">
                <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <i class="fas fa-desktop text-3xl text-purple-700"></i>
                </div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">7</h3>
                <p class="text-gray-600 font-medium mb-3">Total Loket</p>
                <div class="flex items-center justify-center text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    <span class="text-sm font-semibold">Tersedia</span>
                </div>
            </div>

            <!-- Jumlah Layanan -->
            <div class="bg-white rounded-3xl shadow-xl border border-indigo-100 p-8 text-center card-hover">
                <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <i class="fas fa-clipboard-list text-3xl text-indigo-700"></i>
                </div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">245</h3>
                <p class="text-gray-600 font-medium mb-3">Jumlah Layanan</p>
                <div class="flex items-center justify-center text-green-600">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span class="text-sm font-semibold">8% dari kemarin</span>
                </div>
            </div>
        </div>

        <!-- Grafik dan Tabel -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Grafik Tren Antrian -->
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-xl border border-blue-100 p-8 card-hover">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h3 class="text-2xl font-bold text-gray-900">Data Antrian</h3>
                    <div class="flex space-x-2">
                        <button class="px-4 py-2 text-sm font-semibold bg-blue-800 text-white rounded-xl">Harian</button>
                        <button class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Mingguan</button>
                        <button class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Bulanan</button>
                    </div>
                </div>
                <div class="h-80 bg-gray-50 rounded-2xl p-4">
                    <canvas id="queueChart"></canvas>
                </div>
            </div>

            <!-- Distribusi Antrian -->
            <div class="bg-white rounded-3xl shadow-xl border border-blue-100 p-8 card-hover">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Status Laporan</h3>
                <div class="h-80 bg-gray-50 rounded-2xl p-4">
                    <canvas id="distributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Script untuk grafik -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Tren Antrian
    const queueCtx = document.getElementById('queueChart').getContext('2d');
    const queueChart = new Chart(queueCtx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Total Antrian',
                data: [120, 135, 125, 145, 160, 110, 90],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointRadius: 5,
            }, {
                label: 'Antrian Selesai',
                data: [110, 125, 115, 135, 150, 100, 85],
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: 'rgb(16, 185, 129)',
                pointRadius: 5,
            }, {
                label: 'Antrian Tidak Hadir',
                data: [5, 8, 6, 9, 7, 4, 3],
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: 'rgb(239, 68, 68)',
                pointRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            family: 'Poppins',
                            size: 12,
                            weight: 'bold'
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: { family: 'Poppins' }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { family: 'Poppins' }
                    }
                }
            }
        }
    });

    // Grafik Distribusi Loket
    const distCtx = document.getElementById('distributionChart').getContext('2d');
    const distributionChart = new Chart(distCtx, {
        type: 'doughnut',
        data: {
            labels: ['Selesai', 'Perlu Tindak Lanjut'],
            datasets: [{
                data: [85, 15],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(251, 146, 60, 0.8)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            family: 'Poppins',
                            size: 12,
                            weight: 'bold'
                        },
                        padding: 20
                    }
                }
            }
        }
    });
</script>
@endsection