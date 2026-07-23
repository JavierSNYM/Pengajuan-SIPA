<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    // Menegaskan nama tabel di database MySQL
    protected $table = 'pengajuans';

    // MEMBUKA AKSES UTK SEMUA KOLOM BARU (DATA ORTU, CUTI, RUANGAN) BIAR BISA MASUK DATABASE
    protected $guarded = [];

    /**
     * Relasi balik ke model User (Mahasiswa)
     * Menghubungkan data pengajuan dengan profil mahasiswa yang sedang login
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}