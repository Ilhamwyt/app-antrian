<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CounterController;

Route::get('/', [CounterController::class, 'home'])->name('home');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'ShowFormLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/monitor', function () { return view('monitor.monitor');})->name('monitor');
    Route::get('/get-counters', [CounterController::class, 'getCounters'])->name('getCounters');
});

// Halaman Loket untuk Panggil Antrian (tanpa autentikasi)
Route::get('/loket/{id}', [CounterController::class, 'show'])->name('loket.show');

// API Antrian
Route::post('/api/queue/take', [\App\Http\Controllers\QueueController::class, 'take'])->name('queue.take');
Route::get('/api/queue/counter/{counterId}', [\App\Http\Controllers\QueueController::class, 'getByCounter'])->name('queue.getByCounter');
Route::put('/api/queue/{id}/status', [\App\Http\Controllers\QueueController::class, 'updateStatus'])->name('queue.updateStatus');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () { return view('admin.dashboard');})->name('dashboard');
    Route::get('/manajemenLoket', [CounterController::class, 'index'])->name('manajemenLoket');
    Route::get('/laporan', function () { return view('admin.laporan');})->name('laporan');
    
    // CRUD Loket
    Route::post('/loket', [CounterController::class, 'store'])->name('loket.store');
    Route::get('/loket/{id}/edit', [CounterController::class, 'edit'])->name('loket.edit');
    Route::put('/loket/{id}', [CounterController::class, 'update'])->name('loket.update');
    Route::delete('/loket/{id}', [CounterController::class, 'destroy'])->name('loket.destroy');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});