<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('kelompok_id')->nullable()->after('mentor_id')->constrained('kelompoks')->nullOnDelete();
            $table->string('hp_wa')->nullable()->after('kelompok_id');
            $table->string('fakultas')->nullable()->after('hp_wa');
            $table->string('prodi')->nullable()->after('fakultas');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kelompok_id']);
            $table->dropColumn(['kelompok_id', 'hp_wa', 'fakultas', 'prodi']);
        });
    }
};