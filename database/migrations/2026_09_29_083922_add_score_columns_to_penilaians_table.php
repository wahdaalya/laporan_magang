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
        Schema::table('penilaians', function (Blueprint $table) {
            $table->decimal('nilai_bimbingan', 5, 2)->nullable();
            $table->decimal('nilai_laporan_akhir', 5, 2)->nullable();
            $table->decimal('nilai_presentasi', 5, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->dropColumn(['nilai_bimbingan', 'nilai_laporan_akhir', 'nilai_presentasi']);
        });
    }
};
