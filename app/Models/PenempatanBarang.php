<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenempatanBarang extends Model
{
    use HasFactory;

    protected $table = 'penempatan_barang';

    protected $fillable = [
        'id_detail_barang',
        'id_ruangan',
        'pc_no',
        'tanggal_penempatan',
    ];

    public function detailBarang()
    {
        return $this->belongsTo(DetailBarang::class, 'id_detail_barang');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }
}
