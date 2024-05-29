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
        Schema::create('transaksi_pemindahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_ruangan_asal')->constrained('ruangan')->onDelete('cascade');
            $table->foreignId('id_ruangan_tujuan')->constrained('ruangan')->onDelete('cascade');
            $table->date('tanggal_pemindahan');
            $table->integer('jumlah_item');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pemindahan');
    }
};
