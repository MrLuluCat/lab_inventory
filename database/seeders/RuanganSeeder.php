<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder
{
    public function run()
    {
        DB::table('ruangan')->insert(
            [
                'nama_ruangan' => 'Gudang',
                'status_ruangan' => 'Tersedia',
                'kapasitas' => 0,
                'keterangan' => 'Ruangan default untuk penyimpanan barang',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'nama_ruangan' => 'Gudang Barang Tidak Terpakai',
                'status_ruangan' => 'Tersedia',
                'kapasitas' => 0,
                'keterangan' => 'Ruangan untuk penyimpanan barang yang tidak terpakai atau rusak',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
