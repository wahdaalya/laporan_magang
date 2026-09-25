<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kampuses', function (Blueprint $table) {
            $table->string('kode_kampus')->nullable()->unique()->after('id');
        });

        Schema::table('kampuses', function (Blueprint $table) {
            $table->renameColumn('nama_universitas', 'nama_kampus');
            $table->renameColumn('alamat', 'alamat_kampus');
        });

        Schema::table('kampuses', function (Blueprint $table) {
            $table->text('alamat_kampus')->nullable()->change();
        });

        if (Schema::hasColumn('kampuses', 'fakultas') || Schema::hasColumn('kampuses', 'prodi')) {
            Schema::table('kampuses', function (Blueprint $table) {
                $columns = array_filter(['fakultas', 'prodi'], fn ($col) => Schema::hasColumn('kampuses', $col));
                if (! empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('kampuses', function (Blueprint $table) {
            $table->string('fakultas')->nullable();
            $table->string('prodi')->nullable();
        });

        Schema::table('kampuses', function (Blueprint $table) {
            $table->renameColumn('nama_kampus', 'nama_universitas');
            $table->renameColumn('alamat_kampus', 'alamat');
        });

        Schema::table('kampuses', function (Blueprint $table) {
            $table->dropUnique(['kode_kampus']);
            $table->dropColumn('kode_kampus');
        });
    }
};