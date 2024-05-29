<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBarang extends Model
{
    use HasFactory;

    protected $table = 'detail_barang';

    protected $fillable = [
        'no_surat',
        'id_jenis_barang',
        'merek',
        'kode_inventaris',
        'status',
        'lokasi',
    ];

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class, 'no_surat');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_jenis_barang');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'lokasi');
    }

    public function penempatanBarangs()
    {
        return $this->hasMany(PenempatanBarang::class, 'id_detail_barang');
    }

    public function transaksiPemindahan()
    {
        return $this->hasMany(TransaksiPemindahan::class, 'id_detail_barang');
    }
}
