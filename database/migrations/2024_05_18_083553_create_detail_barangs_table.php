<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailBarangsTable extends Migration
{
    public function up()
    {
        Schema::create('detail_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('no_surat')->constrained('surat_masuk');
            $table->foreignId('id_jenis_barang')->constrained('barang');
            $table->string('merek');
            $table->string('kode_inventaris')->unique();
            $table->string('kondisi');
            $table->foreignId('lokasi')->constrained('ruangan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_barang');
    }
}
