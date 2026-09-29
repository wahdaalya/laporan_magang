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
        Schema::create('nilai_akademiks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('dosen_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('nilai_bimbingan', 5, 2);
            $table->decimal('nilai_laporan_akhir', 5, 2);
            $table->decimal('nilai_presentasi', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_akademiks');
    }
};
