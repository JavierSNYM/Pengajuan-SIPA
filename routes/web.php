<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth; 
use App\Models\Pengajuan; // TAMBAHAN WAJIB UNTUK MEMBACA DATABASE SURAT

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
    
    // Baris baru agar tidak error saat membuka form pengajuan
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    
    Route::post('/pengajuan', [PengajuanController::class, 'ajukanSurat'])->name('pengajuan.store');

    // RUTE KHUSUS ADMIN TU
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/pengajuan/{id}', [AdminController::class, 'show'])->name('admin.show');
    Route::post('/admin/pengajuan/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.updateStatus');
    Route::post('/admin/reset-sandi/{id}', [AdminController::class, 'resetPassword'])->name('admin.resetSandi');
    Route::post('/admin/reset-sandi-npm', [AdminController::class, 'resetPasswordByNpm'])->name('admin.resetSandiNpm');
    
    // RUTE BARU UNTUK BACKUP WORD
    Route::get('/admin/backup/{prodi}', [AdminController::class, 'downloadDanArsipkan'])->name('admin.backup');

    // ===================================================================
    // RUTE BARU: KELOLA DATA MAHASISWA (WHITELIST & EXCEL)
    // ===================================================================
    Route::get('/admin/mahasiswa', [AdminController::class, 'kelolaMahasiswa'])->name('admin.mahasiswa');
    Route::post('/admin/mahasiswa/import', [AdminController::class, 'importMahasiswa'])->name('admin.mahasiswa.import');
    Route::post('/admin/mahasiswa/tambah', [AdminController::class, 'storeMahasiswa'])->name('admin.mahasiswa.store');
    Route::delete('/admin/mahasiswa/{id}', [AdminController::class, 'destroyMahasiswa'])->name('admin.mahasiswa.destroy');
    Route::post('/admin/mahasiswa/hapus-massal', [AdminController::class, 'hapusMassalMahasiswa'])->name('admin.mahasiswa.hapusMassal');
});

// ROUTE PEMBERSIH CACHE
Route::get('/bersihkan-cache', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return "Cache berhasil dibersihkan, Brader!";
});

// ===================================================================
// ROUTE HALAMAN VERIFIKASI QR CODE (PUBLIK)
// ===================================================================
Route::get('/verifikasi/dokumen/{kode}', function ($kode) {
    // 🌟 MENCARI BERDASARKAN KODE UNIK BUKAN ID LAGI
    $pengajuan = Pengajuan::with('user')->where('kode_verifikasi', $kode)->first();

    // Jika Kode Surat tidak ada di database / QR Code Palsu
    if (!$pengajuan) {
        return "
        <!DOCTYPE html>
        <html lang='id'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Dokumen Tidak Valid - SIPA</title>
            <script src='https://cdn.tailwindcss.com'></script>
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
        </head>
        <body class='bg-slate-100 min-h-screen flex items-center justify-center p-4 font-sans'>
            <div class='max-w-md w-full bg-white rounded-3xl shadow-xl p-8 text-center border border-rose-100'>
                <div class='w-16 h-16 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-4'>
                    <i class='fa-solid fa-triangle-exclamation'></i>
                </div>
                <h1 class='font-black text-rose-600 text-xl uppercase tracking-tight'>Dokumen Tidak Valid</h1>
                <p class='text-xs text-slate-500 mt-2 leading-relaxed'>
                    Maaf, kode keamanan dokumen ini tidak terdaftar di sistem SIPA FTIK UPS Tegal atau telah dibatalkan.
                </p>
            </div>
        </body>
        </html>";
    }

    // Deteksi Prodi Otomatis dari NPM FTIK UPS Tegal (Eksplisit 66 untuk Informatika)
    $npm = $pengajuan->user->npm ?? '';
    $kodeProdi = substr($npm, 0, 2);
    $namaProdi = match($kodeProdi) {
        '66' => 'Teknik Informatika',
        '65' => 'Teknik Sipil',
        '64' => 'Teknik Mesin',
        '63' => 'Teknik Industri',
        default => 'Program Studi FTIK'
    };

    // Tampilan Halaman Verifikasi Resmi
    return "
    <!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Verifikasi Dokumen Resmi - SIPA</title>
        <script src='https://cdn.tailwindcss.com'></script>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
    </head>
    <body class='bg-slate-50 min-h-screen flex flex-col items-center justify-center p-4 font-sans'>
        <div class='max-w-md w-full bg-white rounded-3xl shadow-xl border border-emerald-100 overflow-hidden relative'>
            
            <div class='bg-emerald-500 text-white text-center py-6 px-4 relative'>
                <div class='w-14 h-14 bg-white text-emerald-500 rounded-full flex items-center justify-center text-2xl mx-auto shadow-md mb-2 border-4 border-emerald-400'>
                    <i class='fa-solid fa-shield-check'></i>
                </div>
                <h1 class='font-black text-lg tracking-wide uppercase'>Dokumen Terverifikasi</h1>
                <p class='text-[11px] text-emerald-100 font-medium tracking-wider uppercase mt-0.5'>SIPA FTIK Universitas Pancasakti Tegal</p>
                
                <div class='mt-3 inline-block bg-emerald-700/50 backdrop-blur-sm text-yellow-300 text-[11px] font-mono font-bold px-3 py-1 rounded-md border border-emerald-400/30 tracking-widest'>
                    SECURE ID: " . $pengajuan->kode_verifikasi . "
                </div>
            </div>

            <div class='p-6 space-y-4 text-slate-700'>
                <div class='border-b border-slate-100 pb-3'>
                    <p class='text-[10px] font-bold text-slate-400 uppercase tracking-wider'>Jenis Layanan Dokumen</p>
                    <p class='font-extrabold text-slate-800 text-base flex items-center gap-2 mt-0.5'>
                        <i class='fa-solid fa-file-invoice text-emerald-500'></i> " . $pengajuan->jenis_surat . "
                    </p>
                </div>

                <div class='grid grid-cols-2 gap-4 border-b border-slate-100 pb-3'>
                    <div>
                        <p class='text-[10px] font-bold text-slate-400 uppercase tracking-wider'>Nama Mahasiswa</p>
                        <p class='font-bold text-slate-800 text-sm mt-0.5'>" . ($pengajuan->user->name ?? '-') . "</p>
                    </div>
                    <div>
                        <p class='text-[10px] font-bold text-slate-400 uppercase tracking-wider'>NPM / Prodi</p>
                        <p class='font-semibold text-slate-600 text-xs mt-0.5'>" . ($pengajuan->user->npm ?? '-') . " <br><span class='text-emerald-600 font-bold'>" . $namaProdi . "</span></p>
                    </div>
                </div>

                <div class='grid grid-cols-2 gap-4 border-b border-slate-100 pb-3'>
                    <div>
                        <p class='text-[10px] font-bold text-slate-400 uppercase tracking-wider'>Status Pengesahan</p>
                        <p class='mt-1'><span class='bg-emerald-100 text-emerald-800 text-[10px] font-black px-2.5 py-1 rounded-full uppercase border border-emerald-200 tracking-wider'><i class='fa-solid fa-circle-check mr-1'></i> " . $pengajuan->status . "</span></p>
                    </div>
                    <div>
                        <p class='text-[10px] font-bold text-slate-400 uppercase tracking-wider'>Tanggal Sah Sistem</p>
                        <p class='font-medium text-slate-600 text-xs mt-0.5'>" . $pengajuan->updated_at->format('d M Y, H:i') . " WIB</p>
                    </div>
                </div>

                <div class='bg-slate-50 p-3 rounded-xl border border-slate-200/60 flex items-start gap-2.5 text-[10px] text-slate-400 font-medium leading-relaxed'>
                    <i class='fa-solid fa-circle-info text-slate-400 text-xs mt-0.5 shrink-0'></i>
                    <span>Surat ini sah dan diterbitkan secara elektronik melalui Sistem Informasi Administrasi Akademik (SIPA) FTIK UPS Tegal menggunakan enkripsi QR Code validasi otomatis.</span>
                </div>
            </div>

            <div class='p-4 bg-slate-50 border-t border-slate-100 text-center'>
                <span class='text-xs font-bold text-slate-400'>&copy; 2026 SIPA FTIK UPS Tegal</span>
            </div>
        </div>
    </body>
    </html>";
});