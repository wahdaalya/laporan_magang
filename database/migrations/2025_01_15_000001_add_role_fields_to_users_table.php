<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['ADMIN', 'MAHASISWA', 'DOSEN', 'MENTOR'])->default('MAHASISWA')->after('email');
            $table->string('nim_nip')->nullable()->after('role');
            $table->string('instansi')->nullable()->after('nim_nip');
            $table->string('unit')->nullable()->after('instansi');
            $table->string('foto')->nullable()->after('unit');
            $table->foreignId('dosen_id')->nullable()->after('foto')->constrained('users')->nullOnDelete();
            $table->foreignId('mentor_id')->nullable()->after('dosen_id')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['dosen_id']);
            $table->dropForeign(['mentor_id']);
            $table->dropColumn(['role', 'nim_nip', 'instansi', 'unit', 'foto', 'dosen_id', 'mentor_id']);
        });
    }
};
