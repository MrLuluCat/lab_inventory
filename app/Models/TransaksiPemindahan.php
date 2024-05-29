<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPemindahan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_pemindahan';

    protected $fillable = [
        'id_ruangan_asal',
        'id_ruangan_tujuan',
        'tanggal_pemindahan',
    ];

    public function ruanganAsal()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan_asal');
    }

    public function ruanganTujuan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan_tujuan');
    }

    public function penempatanBarangs()
    {
        return $this->hasMany(PenempatanBarang::class, 'id_transaksi_pemindahan');
    }
}

