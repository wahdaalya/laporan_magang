<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pembimbings')) {
            return;
        }

        Schema::create('pembimbings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama');
            $table->string('nip')->nullable();
            $table->string('email')->nullable();
            $table->string('hp_wa')->nullable();
            $table->foreignId('departemen_id')->nullable()->constrained('departemens')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (DB::table('migrations')->where('migration', '2025_01_15_000003_create_pembimbings_table')->exists()) {
            return;
        }

        Schema::dropIfExists('pembimbings');
    }
};
