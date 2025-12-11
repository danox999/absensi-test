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
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama hari libur
            $table->date('date'); // Tanggal hari libur
            $table->text('description')->nullable(); // Deskripsi/keterangan
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();
            
            $table->unique('date'); // Satu tanggal hanya bisa satu hari libur
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
