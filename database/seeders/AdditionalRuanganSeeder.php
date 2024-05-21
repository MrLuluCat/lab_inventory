<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AdditionalRuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ruangan')->insert([
            [
                'nama_ruangan' => 'gudang_barang_tidak_terpakai',
                'status_ruangan' => 'Tersedia',
                'kapasitas' => 0,
                'keterangan' => 'Ruangan untuk penyimpanan barang yang tidak terpakai atau rusak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
