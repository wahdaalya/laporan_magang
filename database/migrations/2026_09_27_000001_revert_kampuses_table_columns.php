<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kampuses', function (Blueprint $table) {
            if (! Schema::hasColumn('kampuses', 'nama_universitas')) {
                $table->string('nama_universitas')->nullable();
            }
            if (! Schema::hasColumn('kampuses', 'alamat')) {
                $table->string('alamat')->nullable();
            }
            if (! Schema::hasColumn('kampuses', 'fakultas')) {
                $table->string('fakultas')->nullable();
            }
            if (! Schema::hasColumn('kampuses', 'prodi')) {
                $table->string('prodi')->nullable();
            }
        });

        $toDrop = array_filter(
            ['kode_kampus', 'nama_kampus', 'alamat_kampus'],
            fn ($col) => Schema::hasColumn('kampuses', $col)
        );

        if (! empty($toDrop)) {
            Schema::table('kampuses', function (Blueprint $table) use ($toDrop) {
                $table->dropColumn($toDrop);
            });
        }
    }

    public function down(): void
    {
        // Sengaja dikosongkan.
    }
};