<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('npm_whitelists', function (Blueprint $table) {
        $table->id();
        $table->string('npm')->unique(); // Menyimpan daftar NPM resmi
        $table->string('nama_mahasiswa')->nullable(); // Opsional, untuk data admin
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('npm_whitelists');
    }
};
