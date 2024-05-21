<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $fillable = [
        'nama_ruangan',
        'status_ruangan',
        'kapasitas',
        'keterangan',
    ];

    public function detailBarangs()
    {
        return $this->hasMany(DetailBarang::class, 'lokasi');
    }

    public function penempatanBarangs()
    {
        return $this->hasMany(PenempatanBarang::class, 'id_ruangan');
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
