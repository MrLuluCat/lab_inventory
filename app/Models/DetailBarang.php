<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBarang extends Model
{
    use HasFactory;

    protected $table = 'detail_barang';

    protected $fillable = [
        'id_jenis_barang',
        'merek',
        'kode_inventaris',
        'no_surat',
        'status',
        'lokasi'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_jenis_barang');
    }

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class, 'no_surat');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'lokasi');
    }

    public function penempatanBarang()
    {
        return $this->hasMany(PenempatanBarang::class, 'id_detail_barang');
    }
}
