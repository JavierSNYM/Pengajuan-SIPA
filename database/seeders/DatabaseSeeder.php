<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Pembuatan Akun Admin Resmi
        User::updateOrCreate(
            ['npm' => 'admin'],
            [
                'name' => 'Admin TU FTIK',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Pembuatan Akun Mahasiswa Dummy
        User::updateOrCreate(
            ['npm' => '6622600065'],
            [
                'name' => 'Maulana Javier',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
            ]
        );
    }
}