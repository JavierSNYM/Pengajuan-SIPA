<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth; 

// 1. RUTE DEFAULT 
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'admin' ? redirect('/admin') : redirect('/pengajuan');
    }
    return redirect('/login');
});

// 2. GRUP RUTE GUEST (Hanya bisa dibuka kalau belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// 3. GRUP RUTE AUTHENTICATED (Hanya bisa dibuka kalau SUDAH LOGIN)
Route::middleware('auth')->group(function () {
    
    // Rute Proses Keluar Sistem (Logout)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ===================================================================
    // RUTE KHUSUS MAHASISWA
    // ===================================================================
    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    
    // 🌟 INI BARIS BARU YANG SAYA TAMBAHKAN AGAR TIDAK ERROR LAGI
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    
    Route::post('/pengajuan', [PengajuanController::class, 'ajukanSurat'])->name('pengajuan.store');

    // RUTE KHUSUS ADMIN TU
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/pengajuan/{id}', [AdminController::class, 'show'])->name('admin.show');
    Route::post('/admin/pengajuan/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.updateStatus');
    Route::post('/admin/reset-sandi/{id}', [App\Http\Controllers\AdminController::class, 'resetPassword'])->name('admin.resetSandi');
    Route::post('/admin/reset-sandi-npm', [App\Http\Controllers\AdminController::class, 'resetPasswordByNpm'])->name('admin.resetSandiNpm');
    
    // 👇 RUTE BARU UNTUK BACKUP WORD 👇
    Route::get('/admin/backup/{prodi}', [AdminController::class, 'downloadDanArsipkan'])->name('admin.backup');
});
Route::get('/bersihkan-cache', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return "Cache berhasil dibersihkan, Brader!";
});