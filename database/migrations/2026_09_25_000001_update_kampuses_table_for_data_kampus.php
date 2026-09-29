<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('kampuses', 'kode_kampus')) {
            Schema::table('kampuses', function (Blueprint $table) {
                $table->string('kode_kampus')->nullable()->unique()->after('id');
            });
        }

        if (Schema::hasColumn('kampuses', 'nama_universitas') && ! Schema::hasColumn('kampuses', 'nama_kampus')) {
            Schema::table('kampuses', function (Blueprint $table) {
                $table->renameColumn('nama_universitas', 'nama_kampus');
            });
        }

        if (Schema::hasColumn('kampuses', 'alamat') && ! Schema::hasColumn('kampuses', 'alamat_kampus')) {
            Schema::table('kampuses', function (Blueprint $table) {
                $table->renameColumn('alamat', 'alamat_kampus');
            });
        }

        if (Schema::hasColumn('kampuses', 'alamat_kampus')) {
            Schema::table('kampuses', function (Blueprint $table) {
                $table->text('alamat_kampus')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        // Sengaja dikosongkan.
    }
};