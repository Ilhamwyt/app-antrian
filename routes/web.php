<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\QueueController;



Route::middleware(['guest'])->group(function () {
    Route::get('/', [CounterController::class, 'home'])->name('home');

    Route::get('/login', [AuthController::class, 'ShowFormLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/monitor', function () { return view('monitor.monitor');})->name('monitor');
    Route::get('/get-counters', [CounterController::class, 'getCounters'])->name('getCounters');
    Route::get('/loket/{id}', [CounterController::class, 'show'])->name('loket.show');

    // Route untuk mengambil nomor antrian
    Route::post('/queue/take', [QueueController::class, 'take'])->name('queue.take');
    // Route untuk menampilkan hasil antrian
    Route::get('/queue/result/{queueId}', [QueueController::class, 'result'])->name('queue.result');
    // Route untuk mengambil data antrian berdasarkan loket
    Route::get('/queue/counter/{counterId}', [QueueController::class, 'getByCounter'])->name('queue.getByCounter');
    // Route untuk memanggil antrian berikutnya
Route::post('/queue/call-next', [QueueController::class, 'callNext'])->name('queue.callNext');
// Route untuk memanggil ulang antrian
Route::post('/queue/recall', [QueueController::class, 'recall'])->name('queue.recall');
// Route untuk menandai antrian sebagai tidak hadir
Route::post('/queue/mark-absent', [QueueController::class, 'markAbsent'])->name('queue.markAbsent');
    // Route untuk melayani antrian
    Route::post('/queue/serve', [QueueController::class, 'serveQueue'])->name('queue.serve');
    // Route untuk mendapatkan antrian yang sedang dipanggil (untuk monitor)
    Route::get('/queue/current-called', [QueueController::class, 'getCurrentCalledQueue'])->name('queue.currentCalled');
    // Route untuk mendapatkan daftar antrian berdasarkan counter (untuk loket)
    Route::get('/queue/counter/{counterId}', [QueueController::class, 'getQueuesByCounter'])->name('queue.getByCounterAjax');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard/data', [App\Http\Controllers\Admin\DashboardController::class, 'getData'])->name('admin.dashboard.data');
    Route::get('/manajemenLoket', [CounterController::class, 'index'])->name('manajemenLoket');
    
    // Route untuk laporan dan CRUD visitors
    Route::get('/laporan', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.laporan.index');
    Route::post('/laporan', [App\Http\Controllers\Admin\ReportController::class, 'store'])->name('admin.laporan.store');
    Route::put('/laporan/{visitor}', [App\Http\Controllers\Admin\ReportController::class, 'update'])->name('admin.laporan.update');
    Route::delete('/laporan/{visitor}', [App\Http\Controllers\Admin\ReportController::class, 'destroy'])->name('admin.laporan.destroy');
    Route::get('/laporan-export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('admin.laporan.export');
    
    // CRUD Loket
    Route::post('/loket', [CounterController::class, 'store'])->name('loket.store');
    Route::get('/loket/{id}/edit', [CounterController::class, 'edit'])->name('loket.edit');
    Route::put('/loket/{id}', [CounterController::class, 'update'])->name('loket.update');
    Route::delete('/loket/{id}', [CounterController::class, 'destroy'])->name('loket.destroy');
    
    // Rute untuk manajemen layanan
    Route::resource('layanan', App\Http\Controllers\Admin\LayananController::class);
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});