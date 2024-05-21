<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiPemindahansTable extends Migration
{
    public function up()
    {
        Schema::create('transaksi_pemindahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_detail_barang')->constrained('detail_barang');
            $table->foreignId('id_ruangan_asal')->constrained('ruangan');
            $table->foreignId('id_ruangan_tujuan')->constrained('ruangan');
            $table->date('tanggal_pemindahan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaksi_pemindahan');
    }
}
