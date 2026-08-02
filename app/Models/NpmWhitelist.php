<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NpmWhitelist extends Model
{
    use HasFactory;

    // 🌟 INI KUNCINYA: Membuka gembok agar kolom bisa diisi masal
    protected $fillable = [
        'npm',
        'nama_mahasiswa',
    ];
}