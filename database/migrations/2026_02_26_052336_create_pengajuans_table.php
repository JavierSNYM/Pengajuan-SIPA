<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel pengajuans
     */
    public function up(): void
    {
        Schema::create('pengajuans', function (Blueprint $table) {
            // 1. KOLOM UTAMA PENGAJUAN
            $table->id();
            // Menghubungkan pengajuan dengan id mahasiswa di tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->string('jenis_surat');
            $table->string('email_aktif');
            $table->string('status')->default('Menunggu Validasi Admin');
            $table->string('file_path')->nullable(); // Tempat menyimpan nama file berkas

            // 2. KOLOM TAMBAHAN DINAMIS (Aturan Seleksi Berdasarkan Data Lapangan)
            // Semua dibuat ->nullable() agar tidak error saat mahasiswa mengajukan jenis surat lain
            $table->string('keperluan')->nullable();      // BPJS / Beasiswa / Tunjangan
            $table->string('nama_ortu')->nullable();      // Khusus Tunjangan Gaji Ortu
            $table->string('nip_ortu')->nullable();       // Khusus Tunjangan Gaji Ortu
            $table->string('pangkat_ortu')->nullable();   // Khusus Tunjangan Gaji Ortu
            $table->string('pekerjaan_ortu')->nullable(); // Khusus Tunjangan Gaji Ortu
            
            $table->string('semester_cuti')->nullable();  // Khusus Surat Cuti
            $table->string('tahun_akademik')->nullable(); // Khusus Surat Cuti / Aktif Kuliah
            $table->string('perusahaan')->nullable();     // Khusus Surat PKL / KKL

            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi (Hapus tabel)
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};