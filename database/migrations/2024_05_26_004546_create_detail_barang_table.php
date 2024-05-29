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
        Schema::create('detail_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jenis_barang')->constrained('barang')->onDelete('cascade');
            $table->string('merek');
            $table->string('kode_inventaris')->unique();
            $table->foreignId('no_surat')->constrained('surat_masuk')->onDelete('cascade');
            $table->enum('status', ['in storage', 'in use', 'unused']);
            $table->foreignId('lokasi')->constrained('ruangan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_barang');
    }
};
