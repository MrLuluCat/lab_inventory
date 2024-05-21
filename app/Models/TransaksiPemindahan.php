<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPemindahan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_pemindahan';

    protected $fillable = [
        'id_detail_barang',
        'id_ruangan_asal',
        'id_ruangan_tujuan',
        'tanggal_pemindahan',
    ];

    public function detailBarang()
    {
        return $this->belongsTo(DetailBarang::class, 'id_detail_barang');
    }

    public function ruanganAsal()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan_asal');
    }

    public function ruanganTujuan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan_tujuan');
    }
}
