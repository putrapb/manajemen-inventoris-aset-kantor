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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->index();
            $table->string('kode')->unique();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('lokasi');
            $table->string('status')->default('tersedia')->index();
            $table->date('tanggal_beli');
            $table->decimal('nilai', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
