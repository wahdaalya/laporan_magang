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
        Schema::table('laporan_akhirs', function (Blueprint $table) {
            $table->boolean('status_mengetahui')->default(false)->after('catatan_revisi');
            $table->string('mengetahui_oleh')->nullable()->after('status_mengetahui');
            $table->timestamp('mengetahui_at')->nullable()->after('mengetahui_oleh');
            $table->boolean('status_setuju')->default(false)->after('mengetahui_at');
            $table->string('disetujui_oleh')->nullable()->after('status_setuju');
            $table->timestamp('disetujui_at')->nullable()->after('disetujui_oleh');
        });

        Schema::table('laporans', function (Blueprint $table) {
            $table->boolean('status_mengetahui')->default(false)->after('diperiksa_oleh');
            $table->string('mengetahui_oleh')->nullable()->after('status_mengetahui');
            $table->timestamp('mengetahui_at')->nullable()->after('mengetahui_oleh');
            $table->boolean('status_setuju')->default(false)->after('mengetahui_at');
            $table->timestamp('disetujui_at')->nullable()->after('status_setuju');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_akhirs', function (Blueprint $table) {
            $table->dropColumn([
                'status_mengetahui',
                'mengetahui_oleh',
                'mengetahui_at',
                'status_setuju',
                'disetujui_oleh',
                'disetujui_at',
            ]);
        });

        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn([
                'status_mengetahui',
                'mengetahui_oleh',
                'mengetahui_at',
                'status_setuju',
                'disetujui_at',
            ]);
        });
    }
};
