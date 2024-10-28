<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $fillable = ['nama_ruangan', 'kapasitas', 'keterangan'];

    public function detailBarang()
    {
        return $this->hasMany(DetailBarang::class, 'lokasi');
    }

    public function transaksiPemindahanAsal()
    {
        return $this->hasMany(TransaksiPemindahan::class, 'id_ruangan_asal');
    }

    public function transaksiPemindahanTujuan()
    {
        return $this->hasMany(TransaksiPemindahan::class, 'id_ruangan_tujuan');
    }
}
 