<?php

namespace App\Imports;

use App\Models\NpmWhitelist;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Cek apakah data NPM dan Nama ada di excel (mencegah error baris kosong)
        if (!isset($row['npm']) || !isset($row['nama_mahasiswa'])) {
            return null;
        }

        // Cek apakah NPM sudah ada di database, jika sudah ada lewati saja
        $exists = NpmWhitelist::where('npm', $row['npm'])->first();
        if ($exists) {
            return null;
        }

        return new NpmWhitelist([
            'npm' => $row['npm'],
            'nama_mahasiswa' => $row['nama_mahasiswa'],
        ]);
    }
}