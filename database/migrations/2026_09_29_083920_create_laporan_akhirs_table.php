<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_akhirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('judul_laporan');
            $table->string('file_path');
            $table->enum('status', ['Menunggu Review', 'Revisi', 'Disetujui'])->default('Menunggu Review');
            $table->text('catatan_revisi')->nullable();
            $table->foreignId('dosen_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_akhirs');
    }
};
