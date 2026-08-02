<?php

namespace App\Imports;

use App\Models\NpmWhitelist;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow; // 🌟 KITA GANTI JADI INI

class MahasiswaImport implements ToModel, WithStartRow
{
    /**
     * 🌟 Kita suruh sistem mulai membaca dari Baris ke-2 
     * (Baris ke-1 diabaikan saja apapun isi teks judulnya)
     */
    public function startRow(): int
    {
        return 2; 
    }

    public function model(array $row)
    {
        // $row[0] adalah Kolom A (NPM)
        // $row[1] adalah Kolom B (Nama Mahasiswa)

        // 1. Jika Kolom A (NPM) kosong, lewati baris ini
        if (!isset($row[0]) || empty(trim($row[0]))) {
            return null;
        }

        // Bersihkan spasi tersembunyi dari data Excel
        $npmBersih = trim($row[0]);
        $namaBersih = isset($row[1]) ? trim($row[1]) : 'Tidak Ada Nama';

        // 2. Cek apakah NPM sudah ada di database (Mencegah Duplicate)
        $cekData = NpmWhitelist::where('npm', $npmBersih)->first();
        if ($cekData) {
            return null; // Jika sudah ada, lewati
        }

        // 3. Simpan data ke database
        return new NpmWhitelist([
            'npm'            => $npmBersih,
            'nama_mahasiswa' => $namaBersih,
        ]);
    }
}