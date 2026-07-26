<?php

namespace App\Http\Controllers;

// 🌟 SEMUA USE WAJIB BERKUMPUL DI SINI (DI ATAS CLASS) 🌟
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon; 

class AuthController extends Controller
{
    // 1. MENAMPILKAN HALAMAN LOGIN
    public function showLogin()
    {
        return view('auth.login'); 
    }

    // 2. PROSES LOG IN 
    public function login(Request $request)
    {
        // Validasi input wajib diisi
        $request->validate([
            'npm'      => 'required',
            'password' => 'required'
        ], [
            'npm.required'      => 'NPM wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Cari user berdasarkan NPM
        $user = User::where('npm', $request->npm)->first();

        // Cek apakah user ada DAN password-nya cocok (Bisa Bcrypt Hash ATAU Teks Polosan untuk lokal)
        if ($user && (Hash::check($request->password, $user->password) || $request->password === $user->password)) {
            
            // Berikan izin masuk ke dalam sistem
            Auth::login($user);
            
            // Pengecekan Hak Akses (Role-Based)
            if ($user->npm === 'admin' || $user->role === 'admin') {
                // Jika Admin
                return redirect()->intended('/admin')->with('success', 'Selamat datang, Admin TU.');
            } else {
                // Jika Mahasiswa
                return redirect()->intended('/pengajuan')->with('success', 'Login berhasil. Silakan ajukan surat Anda.');
            }
        }

        // Jika gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors(['npm' => 'NPM atau Kata Sandi yang Anda masukkan salah.'])->withInput();
    }

    // 3. MENAMPILKAN HALAMAN REGISTER
    public function showRegister()
    {
        return view('auth.register'); 
    }

    // 4. PROSES REGISTER MAHASISWA BARU (Batas Minimal Usia 17 Tahun)
    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            // Gembok dipasang di sini: wajib unik di tabel users DAN wajib ada di tabel npm_whitelists
            'npm'           => 'required|string|unique:users,npm|exists:npm_whitelists,npm',
            'tempat_lahir'  => 'required|string|max:100', 
            
            // 🌟 VALIDASI MINIMAL USIA 17 TAHUN KEBELAKANG 🌟
            'tanggal_lahir' => [
                'required',
                'date',
                'before:' . Carbon::now()->subYears(17)->format('Y-m-d')
            ],           
            
            'password'      => 'required|min:8|confirmed', 
        ], [
            'npm.unique'             => 'NPM ini sudah terdaftar di sistem.',
            'npm.exists'             => 'Akses Ditolak: NPM tidak terdaftar dalam sistem!',
            'tempat_lahir.required'  => 'Tempat lahir wajib diisi!',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi!',
            'tanggal_lahir.before'   => 'Tanggal lahir tidak valid! Minimal usia mahasiswa adalah 17 tahun.',
            'password.confirmed'     => 'Ulangi kata sandi tidak cocok!',
        ]);

        // Membuat user baru di database jika lolos validasi di atas
        User::create([
            'name'          => $request->name,
            'npm'           => $request->npm,
            'tempat_lahir'  => $request->tempat_lahir,  
            'tanggal_lahir' => $request->tanggal_lahir, 
            'password'      => Hash::make($request->password),
            'role'          => 'mahasiswa',
        ]);

        return redirect('/login')->with('success', 'Akun berhasil dibuat. Silakan masuk menggunakan NPM Anda.');
    }

    // 5. PROSES KELUAR SISTEM (LOGOUT)
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Hancurkan session biar aman
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}