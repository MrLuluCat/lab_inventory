<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenempatanBarangsTable extends Migration
{
    public function up()
    {
        Schema::create('penempatan_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_detail_barang')->constrained('detail_barang');
            $table->foreignId('id_ruangan')->constrained('ruangan');
            $table->string('pc_no');
            $table->date('tanggal_penempatan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penempatan_barang');
    }
}
