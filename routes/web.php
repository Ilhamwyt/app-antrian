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
});

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