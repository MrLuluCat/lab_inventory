<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenempatanBarang extends Model
{
    use HasFactory;

    protected $table = 'penempatan_barang';

    protected $fillable = [
        'id_transaksi_pemindahan',
        'id_detail_barang',
        'pc_number'
    ];

    public function transaksiPemindahan()
    {
        return $this->belongsTo(TransaksiPemindahan::class, 'id_transaksi_pemindahan');
    }

    public function detailBarang()
    {
        return $this->belongsTo(DetailBarang::class, 'id_detail_barang');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }
}
