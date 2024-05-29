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
        Schema::create('penempatan_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_transaksi_pemindahan')->constrained('transaksi_pemindahan')->onDelete('cascade');
            $table->foreignId('id_detail_barang')->constrained('detail_barang')->onDelete('cascade');
            $table->string('pc_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penempatan_barang');
    }
};
