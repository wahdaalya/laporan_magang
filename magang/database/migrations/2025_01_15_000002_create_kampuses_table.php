<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kampuses', function (Blueprint $table) {
            $table->id();
            $table->string('nama_universitas');
            $table->string('alamat')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('prodi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kampuses');
    }
};
